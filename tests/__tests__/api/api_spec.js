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
const BASE_URL = "http://je.test.com:2333/project/public";



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
            console.log("token: ", res._json.data.token)
            token = res._json.data.token;
        });
});
var username = "pluvet";
it('用户注销', function() {
    var timestamp = +new Date();
    var logoutBody = qs.stringify({
        timestamp: timestamp,
        username: username,
        token: token,
        sign: sha1(timestamp + username + token)
    });
    return frisby.post(BASE_URL + '/auth/logout', { body: logoutBody })
        .expect('json', 'ret', 200);
});