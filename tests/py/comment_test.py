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

log.info(" ================================ 评论测试 ================================ ")
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
api = "/comment/score/add"
log.info(api+'    添加一个评论到曲谱 id=83, 期待返回 ret=200 和添加的评论的 id (%s:%d)' %
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


timestamp = time.time()
sign = sha1(str(timestamp) + username + token)
api = "/comment/reply"
log.info(api+'    添加一个回复到刚才添加的评论, 期待返回 ret=200 和添加的评论的 id (%s:%d)' %
         (currentFileName, cf.f_lineno), 0, getNum())
res = requests.post(baseUrl + api, data={
    'username': username,
    'timestamp': timestamp,
    'sign': sign,

    'comment_id': commentId,
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

subcommentId = jsonObj['data']['id']

timestamp = time.time()
sign = sha1(str(timestamp) + username + token)
api = "/comment/remove"
log.info(api+'    删除刚才的父评论, 期待不能删除因为有子评论 (%s:%d)' %
         (currentFileName, cf.f_lineno), 0, getNum())
res = requests.post(baseUrl + api, data={
    'username': username,
    'timestamp': timestamp,
    'sign': sign,

    'comment_id': commentId,
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
    if 23333 == jsonObj['ret']:
        log.success('测试通过\n', 1)
        testPassNum += 1


timestamp = time.time()
sign = sha1(str(timestamp) + username + token)
api = "/comment/score"
log.info(api+'    获取分页评论, 每页5条, 获取第1页 (%s:%d)' %
         (currentFileName, cf.f_lineno), 0, getNum())
res = requests.post(baseUrl + api, data={
    'username': username,
    'timestamp': timestamp,
    'sign': sign,

    'score_id': 83,
    'perpage': 5,
    'page': 1,
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
    if 'items' in jsonObj['data']:
        log.success('测试通过\n', 1)
        testPassNum += 1


stopwatch.stop()
log.info("测试结束, 一共 %s 个测试, 成功 %s 个, 失败 %s 个, 用时 %s " % (str(testNum),
                                                        str(testPassNum), str(testNum-testPassNum), str(stopwatch)))
print()
