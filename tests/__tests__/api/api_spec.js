const frisby = require('frisby');
const sha1 = require('js-sha1');
const qs = require('qs');

var request_header = {
    headers: {
        'Content-Type': 'application/x-www-form-urlencoded', //
    }
};
frisby.globalSetup({
    request: request_header
});
const BASE_URL = "http://je.test.com:2330/project/public";



// getNonce 测试
var nonce = "";

it('获取 nonce', function() {
    return frisby.post(BASE_URL + '/auth/nonce')
        .expect('json', 'ret', 200)
        .expect('jsonTypes', 'data.nonce', frisby.Joi.string())
        .then(function(res) {
            console.log("nonce: ", res._json.data.nonce)
            nonce = res._json.data.nonce;
        });
});

// 登录测试

var token = "";
it('用户登录', async function() {

    var loginBody = qs.stringify({
        email: "i@pluvet.com",
        nonce: nonce,
        sign: sha1(nonce + "i@pluvet.com" + sha1("moeje" + "apitesting")),
    });

    return frisby.post(BASE_URL + '/auth/login/email', { body: loginBody })
        .expect('json', 'ret', 200)
        .expect('jsonTypes', 'data.token', frisby.Joi.string())
        .then(function(res) {
            if (res._json == undefined) {
                console.log(res._body);
            }
            console.log("token: ", res._json.data.token)
            token = res._json.data.token;
        });
});
it('用户注销其他用户, 期待返回签名无效错误', function() {
    var timestamp = +new Date();
    var logoutBody = qs.stringify({
        timestamp: timestamp,
        username: 'pluvet1',
        token: token,
        sign: sha1(timestamp + username + token)
    });
    return frisby.post(BASE_URL + '/auth/logout', { body: logoutBody })
        .expect('json', 'ret', 23333)
        .then(function(res) {
            if (res._json == undefined) {
                console.log(res._body);
            }
        });
});
var username = "pluvet";
it('用户注销自身', function() {
    var timestamp = +new Date();
    var logoutBody = qs.stringify({
        timestamp: timestamp,
        username: username,
        token: token,
        sign: sha1(timestamp + username + token)
    });
    return frisby.post(BASE_URL + '/auth/logout', { body: logoutBody })
        .expect('json', 'ret', 200)
        .then(function(res) {
            if (res._json == undefined) {
                console.log(res._body);
            }
        });
});

it('用户再次注销, 期待返回无效签名错误', function() {
    var timestamp = +new Date();
    var logoutBody = qs.stringify({
        timestamp: timestamp,
        username: username,
        token: token,
        sign: sha1(timestamp + username + token)
    });
    return frisby.post(BASE_URL + '/auth/logout', { body: logoutBody })
        .expect('json', 'ret', 23333)
        .then(function(res) {
            if (res._json == undefined) {
                console.log(res._body);
            }
        });
});