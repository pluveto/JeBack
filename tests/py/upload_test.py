import time
import hashlib
import requests
import util.log as log
import json
import jsbeautifier
from pprint import pprint
from stopwatch import Stopwatch


import os
import sys

testNum = 0


def getNum():
    global testNum
    testNum += 1
    return ' #' + str(testNum) + ' '


def sha1(str):
    return hashlib.sha1(str.encode("utf-8")).hexdigest()


log.prepare()

baseUrl = 'http://127.0.0.1:2330/project/public'

log.info(" ================================ 文件上传测试 ================================ ")
print()

stopwatch = Stopwatch()
stopwatch.start()

try:

    res = requests.post(baseUrl + "/auth/nonce",
                        data={}, allow_redirects=False)
    jsonRaw = jsbeautifier.beautify(res.text)
    #log.response(jsonRaw, 1)
    jsonObj = json.loads(jsonRaw)
    jsonPass = True
    email = 'i@pluvet.com'
    password = 'apitesting'
    nonce = jsonObj['data']['nonce']
    sign = sha1(nonce + email + sha1("moeje" + password))
    log.info("sign: " + sign)
    res = requests.post(baseUrl + "/auth/login/email",
                        data={
                            'email': email,
                            'nonce': nonce,
                            'sign': sign
                        }, allow_redirects=False)
    jsonRaw = jsbeautifier.beautify(res.text)
    jsonObj = json.loads(jsonRaw)
except:
    login = True
    log.error("登录失败")
    log.response(jsonRaw, 1)

token = jsonObj['data']['token']
log.success("登录成功, token = " + token)
print()
timestamp = time.time()
username = jsonObj['data']['username']
sign = sha1(str(timestamp) + username + token)


dirname = os.path.dirname(__file__)
log.info('/upload/image  '+'上传一个png格式图片文件', 0, getNum())
with open(os.path.join(dirname, 'assets/google.png'), 'rb') as pngfile:
    res = requests.post(baseUrl + "/upload/image",
                        data={
                            'username': username,
                            'timestamp': timestamp,
                            'sign': sign,
                        },
                        files={'file': ('google.png', pngfile, 'image/png')}, allow_redirects=False)
jsonRaw = jsbeautifier.beautify(res.text)
##log.response(jsonRaw, 1)
jsonPass = False
try:
    jsonObj = json.loads(jsonRaw)
    jsonPass = True
except:
    log.error("响应 body 不是有效 json")
    log.response(jsonRaw, 1)

if jsonPass:
    log.info("ret: " + str(jsonObj['ret']), 1)
    if 'ret' in jsonObj and jsonObj['ret'] == 200:
        log.info("url: " + str(jsonObj['data']['url']), 1)
        log.success('测试通过\n', 1)

log.info('/upload/image  '+'上传一个php文件, 将mime伪造为正常png文件', 0, getNum())
with open(os.path.join(dirname, 'assets/test.php'), 'rb') as pngfile:
    res = requests.post(baseUrl + "/upload/image",
                        data={
                            'username': username,
                            'timestamp': timestamp,
                            'sign': sign,
                        },
                        files={'file': ('google.png', pngfile, 'image/png')}, allow_redirects=False)
jsonRaw = jsbeautifier.beautify(res.text)
# log.response(jsonRaw, 1)
jsonPass = False
try:
    jsonObj = json.loads(jsonRaw)
    jsonPass = True
except:
    log.error("响应 body 不是有效 json")

if jsonPass:
    log.info("ret: " + str(jsonObj['ret']), 1)
    if 'ret' in jsonObj and jsonObj['ret'] == 23333:
        log.info("msg: " + str(jsonObj['msg']), 1)
        log.success('测试通过\n', 1)

stopwatch.stop()
log.info("测试结束, 一共 " + str(testNum) + " 个测试, 用时 " + str(stopwatch))
print()
