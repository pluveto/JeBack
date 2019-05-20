import sys
import os
from stopwatch import Stopwatch
from pprint import pprint
import jsbeautifier
import json
import util.log as log
import requests
import hashlib
import time
from inspect import currentframe, getframeinfo

cf = currentframe()
currentFileName = getframeinfo(cf).filename


testNum = 0
testPassNum = 0


def getNum():
    global testNum
    testNum += 1
    return ' #' + str(testNum) + ' '


def sha1(str):
    return hashlib.sha1(str.encode("utf-8")).hexdigest()


log.prepare()

baseUrl = 'http://127.0.0.1:2330/project/public'

log.info(" ================================ 收藏测试 ================================ ")
print()

stopwatch = Stopwatch()
stopwatch.start()

try:

    res = requests.post(baseUrl + "/auth/nonce",
                        data={}, allow_redirects=False)
    jsonRaw = jsbeautifier.beautify(res.text)
    #log.response(jsonRaw, 1)
    jsonObj = json.loads(jsonRaw)
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
    quit()

token = jsonObj['data']['token']
log.success("登录成功, token = " + token)
print()


timestamp = time.time()
username = jsonObj['data']['username']
sign = sha1(str(timestamp) + username + token)
api = "/favorite/score/add"
log.info(api+'    添加一个曲谱收藏, 曲谱id=83, 期待返回 ret=200 和添加的收藏的 id (%s:%d)' %
         (currentFileName, cf.f_lineno), 0, getNum())
res = requests.post(baseUrl + api, data={
    'username': username,
    'timestamp': timestamp,
    'sign': sign,

    'score_id': 83,
    'text': 'Comment on 83.'
}, allow_redirects=False)
jsonRaw = jsbeautifier.beautify(res.text)
log.response(jsonRaw, 1)
try:
    jsonObj = json.loads(jsonRaw)
    jsonPass = True
except:
    log.error("响应 body 不是有效 json")
    jsonPass = False

if jsonPass:
    log.info("ret: " + str(jsonObj['ret']), 1)
    log.info("id: " + str(jsonObj['data']['id']), 1)
    if 'id' in jsonObj['data']:
        log.success('测试通过\n', 1)
        testPassNum += 1
commentId = jsonObj['data']['id']
