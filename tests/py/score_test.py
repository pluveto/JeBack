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


def getNum():
    global testNum
    testNum += 1
    return ' #' + str(testNum) + ' '


def sha1(str):
    return hashlib.sha1(str.encode("utf-8")).hexdigest()


log.prepare()

baseUrl = 'http://127.0.0.1:2330/project/public'

log.info(" ================================ 曲谱测试 ================================ ")
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
api = "/score/add"
log.info(api+'    添加一个曲谱, 只指定标题和正文, 期待服务器自动处理未赋值字段, \n并返回 ret=200 和添加的曲谱的 id (%s:%d)' %
         (currentFileName, cf.f_lineno), 0, getNum())
res = requests.post(baseUrl + api, data={
    'username': username,
    'timestamp': timestamp,
    'sign': sign,

    'title': 'Test ',
    'text': '1 2 3 4 5 6 7'
}, allow_redirects=False)
jsonRaw = jsbeautifier.beautify(res.text)
# log.response(jsonRaw, 1)
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


timestamp = time.time()
sign = sha1(str(timestamp) + username + token)

api = "/upload/image"
dirname = os.path.dirname(__file__)
log.info(api + '  '+'为曲谱上传一个png格式图片文件, 期待获得图片id', 0, getNum())
with open(os.path.join(dirname, 'assets/google.png'), 'rb') as pngfile:
    res = requests.post(baseUrl + api,
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
    jsonPass = False
img_id = 0
if jsonPass:
    log.info("ret: " + str(jsonObj['ret']), 1)
    if 'ret' in jsonObj and jsonObj['ret'] == 200:
        log.info("url: " + str(jsonObj['data']['image_url']), 1)
        log.info("id: " + str(jsonObj['data']['id']), 1)
        img_id = jsonObj['data']['id']
        log.success('测试通过\n', 1)


timestamp = time.time()
sign = sha1(str(timestamp) + username + token)
api = "/score/add"
log.info(api+'    添加一个曲谱, 指定所有完整信息, 期待返回 ret=200 和添加的曲谱的 id 和正式图片 url (%s:%d)' %
         (currentFileName, cf.f_lineno), 0, getNum())
res = requests.post(baseUrl + api, data={
    'username': username,
    'timestamp': timestamp,
    'sign': sign,

    'title': 'Test With Detail',
    'text': '1 2 3 4 5 6 7',
    'temp_image_id': img_id,
    'alias': json.dumps(['Alias 1', 'Alias 2']),
    'anime': 'Anime Name',
    'key': 'C#',
    'type': 0,
    'description': 'Hello, world',
    'addition': 'Some thing to append on title'
}, allow_redirects=False)
jsonRaw = jsbeautifier.beautify(res.text)
log.response(jsonRaw, 1)
try:
    jsonObj = json.loads(jsonRaw)
    jsonPass = True
except:
    log.error("响应 body 不是有效 json")
    jsonPass = False
scoreId = 0
if jsonPass:
    log.info("ret: " + str(jsonObj['ret']), 1)
    log.info("id: " + str(jsonObj['data']['id']), 1)
    log.info("image_url: " + str(jsonObj['data']['image_url']), 1)
    scoreId = jsonObj['data']['id']
    if 'id' in jsonObj['data']:
        log.success('测试通过\n', 1)


timestamp = time.time()
sign = sha1(str(timestamp) + username + token)

api = "/upload/image"
dirname = os.path.dirname(__file__)
log.info(api + '  '+'为曲谱上传重新一个jpg格式图片文件, 期待获得图片id(%s:%d)' %
         (currentFileName, cf.f_lineno), 0, getNum())
with open(os.path.join(dirname, 'assets/bh.jpg'), 'rb') as jpgfile:
    res = requests.post(baseUrl + api,
                        data={
                            'username': username,
                            'timestamp': timestamp,
                            'sign': sign,
                        },
                        files={'file': ('bh.jpg', jpgfile, 'image/jpeg')}, allow_redirects=False)
jsonRaw = jsbeautifier.beautify(res.text)
##log.response(jsonRaw, 1)
jsonPass = False
try:
    jsonObj = json.loads(jsonRaw)
    jsonPass = True
except:
    log.error("响应 body 不是有效 json")
    log.response(jsonRaw, 1)
    jsonPass = False
img_id = 0
if jsonPass:
    log.info("ret: " + str(jsonObj['ret']), 1)
    if 'ret' in jsonObj and jsonObj['ret'] == 200:
        log.info("url: " + str(jsonObj['data']['image_url']), 1)
        log.info("id: " + str(jsonObj['data']['id']), 1)
        img_id = jsonObj['data']['id']
        log.success('测试通过\n', 1)


timestamp = time.time()
sign = sha1(str(timestamp) + username + token)
api = "/score/update"
log.info(api+'    更新刚才添加的曲谱曲谱, 指定所有完整信息, 期待返回 ret=200 & image_url (%s:%d)' %
         (currentFileName, cf.f_lineno), 0, getNum())
res = requests.post(baseUrl + api, data={
    'username': username,
    'timestamp': timestamp,
    'sign': sign,

    'id': scoreId,
    'title': 'Updated Test With Detail',
    'text': 'Updated 1 2 3 4 5 6 7',
    'temp_image_id': img_id,
    'alias': json.dumps(['Updated', 'Alias 2']),
    'anime': 'Updated Anime Name',
    'key': 'D',
    'type': 0,
    'description': 'Updated Hello, world',
    'addition': 'Updated Some thing to append on title'
}, allow_redirects=False)
jsonRaw = jsbeautifier.beautify(res.text)
log.response(jsonRaw)
try:
    jsonObj = json.loads(jsonRaw)
    jsonPass = True
except:
    log.error("响应 body 不是有效 json")
    log.response(res.text, 1)
    jsonPass = False

if jsonPass:
    log.info("ret: " + str(jsonObj['ret']), 1)
    log.info("image_url: " + str(jsonObj['data']['image_url']), 1)

    if 'id' in jsonObj['data']:
        log.success('测试通过\n', 1)


timestamp = time.time()
sign = sha1(str(timestamp) + username + token)
api = "/score"
log.info(api+'    获取刚才更新的曲谱, 期待返回 ret=200 及曲谱相关数据 (%s:%d)' %
         (currentFileName, cf.f_lineno), 0, getNum())
res = requests.post(baseUrl + api, data={
    'username': username,
    'timestamp': timestamp,
    'sign': sign,

    'id': scoreId,
}, allow_redirects=False)
jsonRaw = jsbeautifier.beautify(res.text)
log.response(jsonRaw)
try:
    jsonObj = json.loads(jsonRaw)
    jsonPass = True
except:
    log.error("响应 body 不是有效 json")
    log.response(res.text, 1)
    jsonPass = False

if jsonPass:
    log.info("ret: " + str(jsonObj['ret']), 1)
    log.info("data: " + str(jsonObj['data']), 1)

    if 'id' in jsonObj['data']:
        log.success('测试通过\n', 1)


timestamp = time.time()
sign = sha1(str(timestamp) + username + token)
api = "/score/list"
log.info(api+'    获取曲谱列表 (%s:%d)' %
         (currentFileName, cf.f_lineno), 0, getNum())
res = requests.post(baseUrl + api, data={
    'username': username,
    'timestamp': timestamp,
    'sign': sign,

    'id': scoreId,
}, allow_redirects=False)
jsonRaw = jsbeautifier.beautify(res.text)
log.response(jsonRaw)
try:
    jsonObj = json.loads(jsonRaw)
    jsonPass = True
except:
    log.error("响应 body 不是有效 json")
    log.response(res.text, 1)
    jsonPass = False

if jsonPass:
    log.info("ret: " + str(jsonObj['ret']), 1)
    #log.info("data: " + str(jsonObj['data']), 1)

    if 'id' in jsonObj['data']:
        log.success('测试通过\n', 1)


timestamp = time.time()
sign = sha1(str(timestamp) + username + token)
api = "/score/search"
log.info(api+'    以 updated 为关键字搜索 (%s:%d)' %
         (currentFileName, cf.f_lineno), 0, getNum())
res = requests.post(baseUrl + api, data={
    'username': username,
    'timestamp': timestamp,
    'sign': sign,

    'keyword': 'updated',
}, allow_redirects=False)
jsonRaw = jsbeautifier.beautify(res.text)
log.response(jsonRaw)
try:
    jsonObj = json.loads(jsonRaw)
    jsonPass = True
except:
    log.error("响应 body 不是有效 json")
    log.response(res.text, 1)
    jsonPass = False

if jsonPass:
    log.info("ret: " + str(jsonObj['ret']), 1)
    #log.info("data: " + str(jsonObj['data']), 1)

    if 'id' in jsonObj['data']:
        log.success('测试通过\n', 1)


stopwatch.stop()
log.info("测试结束, 一共 " + str(testNum) + " 个测试, 用时 " + str(stopwatch))
print()
