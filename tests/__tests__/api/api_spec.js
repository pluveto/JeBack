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

var nonce = "";
var base_url = "http://je.test.com:2333/project/public";
it('Should get a nonce', function() {
    return frisby.post(base_url + '/auth/nonce')
        .expect('json', 'ret', 200)
        .expect('jsonTypes', 'data.nonce', frisby.Joi.string())
        .then(function(res) {
            console.log("nonce: ", res._json.data.nonce)
            nonce = res._json.data.nonce;
        });
});


var token = "";
it('Should login by email and get a token', async function() {

    var loginBody = qs.stringify({
        email: "i@pluvet.com",
        nonce: nonce,
        sign: sha1(nonce + "i@pluvet.com" + sha1("moeje" + "apitesting")),
    });

    return frisby.post(base_url + '/auth/login/email', {
            body: loginBody
        })
        .expect('json', 'ret', 200)
        .expect('jsonTypes', 'data.token', frisby.Joi.string())
        .then(function(res) {
            console.log("token: ", res._json.data.token)
            token = res._json.data.token;
        });
});