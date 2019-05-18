import hashlib
import requests
import util.log as log
import json
import jsbeautifier
from pprint import pprint
import os
import sys
log.prepare()

baseUrl = 'http://127.0.0.1:2330/project/public'

tests = {}

tests[1] = ''
# print('\n\n\n\n\n\n\n\n')
log.info(" ================================ 开始测试 ================================ ")
print()

log.info('/auth/nonce  '+'进行 GET 请求, 期待返回 Unexpected method 错误',0 , '#1 ')
res = requests.get(baseUrl + "/auth/nonce")
jsonRaw = jsbeautifier.beautify(res.text)
# log.response(jsonRaw, 1)
jsonPass = False
try:
    jsonObj = json.loads(jsonRaw)
    jsonPass = True
except:
    log.error("返回不是有效json")
if jsonPass:
    log.info("ret: " + str(jsonObj['ret']), 1)
    log.info("msg: " + str(jsonObj['msg']), 1)
if 'ret' in jsonObj and jsonObj['ret'] == 405:
    log.success('测试通过', 1)


log.info('/auth/nonce  '+'进行 POST 请求, 期待返回 ret=200 & nonce', 0,'#2 ')
res = requests.post(baseUrl + "/auth/nonce", data={}, allow_redirects=False)
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
    log.info("nonce: " + jsonObj['data']['nonce'], 1)
    if 'nonce' in jsonObj['data']:
        log.success('测试通过', 1)


email = 'i@pluvet.com'
password = 'apitesting'
nonce = jsonObj['data']['nonce']
sign = hashlib.sha1((nonce + email + hashlib.sha1(
    ("moeje" + password).encode("utf-8")).hexdigest()).encode("utf-8")).hexdigest(),

log.info('/auth/login/email  '+'使用 nonce 进行登录, 期待返回 ret = 200 && token', 0,'#3 ')
res = requests.post(baseUrl + "/auth/login/email",
                    data={
                        'email': email,
                        'nonce': nonce,
                        'sign': sign
                    }, allow_redirects=False)
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
    log.info("token: " + jsonObj['data']['token'], 1)
    if 'token' in jsonObj['data']:
        log.success('测试通过', 1)


log.info('/auth/login/email  '+'使用 nonce 重复登录, 期待返回 非法请求：请求随机串错误', 0,'#4 ')
res = requests.post(baseUrl + "/auth/login/email",
                    data={
                        'email': email,
                        'nonce': nonce,
                        'sign': sign
                    }, allow_redirects=False)
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

        log.success('测试通过', 1)
