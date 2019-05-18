import time
import hashlib
import requests
import util.log as log
import json
import jsbeautifier
from pprint import pprint
import os
import sys


def sha1(str):
    return hashlib.sha1(str.encode("utf-8")).hexdigest()


log.prepare()

baseUrl = 'http://127.0.0.1:2330/project/public'

log.info(" ================================ 开始测试 ================================ ")
print()

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
    sign = hashlib.sha1((nonce + email + hashlib.sha1(
        ("moeje" + password).encode("utf-8")).hexdigest()).encode("utf-8")).hexdigest(),

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
timestamp = time.time()
username = jsonObj['data']['username']
sign = sha1(str(timestamp) + username + token)


dirname = os.path.dirname(__file__)
log.info('/upload/image  '+'上传一个png格式图片文件', 0, '#4 ')
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

if jsonPass:
    log.info("ret: " + str(jsonObj['ret']), 1)
    if 'ret' in jsonObj and jsonObj['ret'] == 200:
        log.info("url: " + str(jsonObj['data']['url']), 1)
        log.success('测试通过', 1)

log.info('/upload/image  '+'上传一个php文件, 将mime伪造为正常png文件', 0, '#4 ')
with open(os.path.join(dirname, 'assets/test.php'), 'rb') as pngfile:
    res = requests.post(baseUrl + "/upload/image",
                        data={
                            'username': username,
                            'timestamp': timestamp,
                            'sign': sign,
                        },
                        files={'file': ('google.png', pngfile, 'image/png')}, allow_redirects=False)
jsonRaw = jsbeautifier.beautify(res.text)
log.response(jsonRaw, 1)
jsonPass = False
try:
    jsonObj = json.loads(jsonRaw)
    jsonPass = True
except:
    log.error("响应 body 不是有效 json")

if jsonPass:
    log.info("ret: " + str(jsonObj['ret']), 1)
    if 'ret' in jsonObj and jsonObj['ret'] == 200:
        log.info("url: " + str(jsonObj['data']['url']), 1)
        log.success('测试通过', 1)
