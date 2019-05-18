const frisby = require('frisby');
const sha1 = require('js-sha1');
const qs = require('qs');
var path = require('path');
var fs = require('fs');

const BASE_URL = "http://je.test.com:2330/project/public";


describe('图片上传测试', function() {


    var request_header = {
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded', //
        }
    };
    frisby.globalSetup({
        request: request_header
    });

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

    it('上传一个txt文件, 期待返回 MIME 错误', function() {
        var imagePath = path.resolve(__dirname, 'file.txt');
        var form = frisby.formData();

        form.append('file', fs.createReadStream(imagePath), {
            knownLength: fs.statSync(imagePath).size
        });
        frisby // Post image
            .setup({
                request: {
                    headers: {
                        'content-type': 'multipart/form-data; boundary=' + form.getBoundary(),
                        'content-length': form.getLengthSync()
                    }
                }
            })
            .post(BASE_URL + '/upload/image', {
                body: form
            })
            .expect('status', 200)
            .expect('json', 'ret', 23333)
            .expect('json', 'msg', '非法请求：参数file应该为：image/jpeg/image/png/image/gif/image/webp，但现在file = text/plain')

    });
    it('上传一个png文件', function() {
        var imagePath = path.resolve(__dirname, 'google.png');
        var form = frisby.formData();

        form.append('file', fs.createReadStream(imagePath), {
            knownLength: fs.statSync(imagePath).size
        });
        frisby // Post image
            .setup({
                request: {
                    headers: {
                        'content-type': 'multipart/form-data; boundary=' + form.getBoundary(),
                        'content-length': form.getLengthSync()
                    }
                }
            })
            .post(BASE_URL + '/upload/image', {
                body: form
            })
            .expect('json', 'ret', 200)

    });
});