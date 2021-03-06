<?php
namespace App\Api;

use PhalApi\Api;
use PhalApi\Exception\BadRequestException;

use \App\Domain\Auth as Domain;

/**
 * 用户验证、会话操作类
 * 
 * @author ZhangZijing <i@pluvet.com> 2019-5-15
 */
class Auth extends Api
{
    public function getRules()
    {
        return array(
            'index' => array(),
            //请勿在此处进行正则校验, 因为这样不利于自定义异常时返回结果.
            'getCaptchByEmail' => array(
                'email' => array('name' => 'email', 'require' => true, 'min' => 5, 'max' => 255)
            ),
            'registerByEmail' => array(
                'username' => array('name' => 'username', 'require' => true, 'min' => 0, 'max' => 24),
                'email' => array('name' => 'email', 'require' => true, 'min' => 5, 'max' => 64),
                'captch' => array('name' => 'captch', 'require' => true, 'min' => 6, 'max' => 6),
                'password' => array('name' => 'password', 'require' => true, 'min' => 6, 'max' => 255)
            ),
            'loginByEmail' => array(
                'email' => array('name' => 'email', 'require' => true, 'min' => 5, 'max' => 255),
                'nonce' => array('name' => 'nonce', 'require' => true),
                'sign' => array('name' => 'sign', 'require' => true),
                'captch' => array('name' => 'captch', 'require' => false, 'min' => 6, 'max' => 6), // 账号异常时使用验证码登录                
            ),
            'logout' => array(
                'username' => array('name' => 'username')
            )

        );
    }

    /**
     * 默认接口服务
     * post
     * @desc 默认接口服务，当未指定接口服务时执行此接口服务
     * @return string title 标题
     * @return string content 内容
     * @return string version 版本，格式：X.X.X
     * @return int time 当前时间戳
     * @exception 400 非法请求，参数传递错误
     */
    public function index()
    {
        $base_url = \App\Helper\Path::baseUrl();
        return array(
            'title' => 'Hello, Moe Auth!',
            'version' => '1.0.0',
            'time' => $_SERVER['REQUEST_TIME'],
            'urls' => array(
                'site_index_url' => $base_url . "/",
            )
        );
    }
    /**
     * @api {post} /auth/nonce 获取 nonce
     * @apiName getNonce
     * @apiGroup Auth
     * @apiVersion 2.0.0
     * @apiPermission none
     * @apiSuccess {String} nonce 获得的随机串.
     * @apiSuccessExample 成功响应:
            {
                "ret": 200,
                "data": {
                    "nonce": "ffc0f836ee89e15eec0441a4ba94e92ee0ff1560"
                },
                "msg": ""
            }
     */
    public function getNonce()
    {
        $domain = new Domain();
        // 生成 nonce
        $nonce = $domain->prepareNonce();
        return array(
            'nonce' => $nonce,
        );
    }

    /**
     * 使用邮箱登录
     * 
     * 登录过程:
     * 
     * 1. 用户从服务器获取一个随机串 nonce。服务器保存 nonce+timestamp
     * 2. 用户计算 sign = sha1(nonce.email.sha1('moeje'.password)).
     *    注意: 此处的 sign 为登录专用, 其它请求的 sign 的算法为: sha1(timestamp + username + token)
     * 3. 提交 sign, email, nonce
     * 4. 服务器判断 nonce 是否过期, 如果没过期, 继续进行其他验证
     * 4. 登录成功，得到 token（有效期为30天，若主动注销则立刻过期）。
     * 5. 利用 token 可以实现免登录。
     * 
     * 所需参数: email, nonce, sign
     * 
     * @return void
     */
    /**
     * @api {post} /auth/login/email 通过邮箱登录
     * @apiVersion 2.0.0
     * @apiPermission none
     * @apiName loginByEmail
     * @apiGroup Auth
     *
     * @apiParam {String} email  邮箱地址.
     * @apiParam {String} nonce  随机串.
     * @apiParam {String} sign  登录签名, 算法为 sign = sha1(nonce + email + sha1('moeje' + password)).
     *
     * @apiSuccess {String} token 有效期为30天(注销自动过期)的token.
     *
     * @apiSuccessExample 成功响应:
            {
                "ret": 200,
                "data": {
                    "token": "9cf536625a810f538dfae11d321a0c987db63bd4"
                },
                "msg": ""
            }
     */
    public function loginByEmail()
    {
        $domain = new Domain();

        /** ------- email validating ------- */
        // 检查格式
        $this->email = trim($this->email);
        if (!\App\Helper\Validator::checkEmailFormat($this->email)) {
            throw new BadRequestException('邮箱格式错误');
        }
        // 检查可用性(重复)
        if ($domain->isEmailAvailable($this->email)) {
            throw new BadRequestException('邮箱尚未注册');
        }
        // TODO: 登录次数记录, 防止暴力破解. 多次错误之后要求验证码登录.
        /** ------- nonce validating ------- */
        if (!$domain->checkNonce($this->nonce)) {
            throw new BadRequestException('请求随机串错误');
        }
        /** ------- 密码检查 ------- */
        if (!$domain->checkLoginSignByEmailAndNonce($this->email, $this->nonce, $this->sign)) {
            throw new BadRequestException('请求签名错误');
        }
        $domain->setUpTokenByEmail($this->email, $this->nonce);
        $userDomain = new \App\Domain\User();
        $userInfo = $userDomain->getUserInfoByEmail($this->email);
        // 注意: userInfo中不得传递密码(即使是摘要密码)
        return $userInfo;
    }
    /**
     * @api {post} /auth/captch/email 发送邮件验证码
     * @apiDescription 不检查邮箱存在性. 已注册的用户也可以接收验证码. 适用于注册/异常登录验证/找回密码/更改密码/注销.
     * @apiVersion 2.0.0
     * @apiPermission none
     * @apiName getCaptchByEmail
     * @apiGroup Auth
     * 
     * @apiParam {String} email  邮箱地址.
     *
     * @apiSuccessExample 成功响应:
            {
                "ret": 200,
                "data": {},
                "msg": ""
            }
     */
    public function getCaptchByEmail()
    {
        $domain = new Domain();

        // 检查格式
        $this->email = trim($this->email);

        if (!\App\Helper\Validator::checkEmailFormat($this->email)) {
            throw new BadRequestException('邮箱格式错误');
        }


        // 如果距离上次发送时间不到 60 s, 则不允许发送验证码. 防止高频提交.
        $lastSendTime = $domain->getLastCaptchSendTimestamp($this->email);

        if (($lastSendTime != NULL)) {
            $deltaTime = time() - $lastSendTime;
            if ($deltaTime < 60) {
                throw new BadRequestException("验证码请 求频率过高, 请 " . (60 - $deltaTime) . " 秒后再试");
            }
        }
        //发送验证码
        $domain->sendEmailCaptch(
            $this->email,
            $_SERVER['REMOTE_ADDR'],
            array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : ''
        );
        //PS: 约定过期时间为 15 分钟, 请在
        return array();
    }

    /**
     * 用户邮箱注册
     * 
     * 注册时将不可避免地用明文传参, 除非用非对称加密. 所以建议开启SSL.
     * 
     * 所需参数: username, email, captch, password
     * 
     * @return void
     */
    /**
     * @api {post} /auth/register/email 邮箱注册
     * @apiDescription 注册时, 请求体将不可避免地用明文传参.所以建议开启SSL.
     * @apiVersion 2.0.0
     * @apiPermission none
     * @apiName registerByEmail
     * @apiGroup Auth
     *
     * @apiParam {String} username  用户名.
     * @apiParam {String} email  用户邮箱.
     * @apiParam {String} captch  验证码.
     * @apiParam {String} password  密码.
     *
     * @apiSuccessExample 成功响应:
            {
                "ret": 200,
                "data": {},
                "msg": ""
            }
     */
    public function registerByEmail()
    {
        $domain = new Domain();
        /** ============== 格式检查 ============== */
        /** ------- 用户名检查 ------- */
        $this->username = trim($this->username);
        if (!\App\Helper\Validator::checkUsernameFormat($this->username)) {
            throw new BadRequestException('用户名格式错误, 请检查用户名格式是否正确. 只可以含有?.@_所有中英文和emoji.');
        }
        /** ------- 邮箱检查   ------- */
        $this->email = trim($this->email);
        if (!\App\Helper\Validator::checkEmailFormat($this->email)) {
            throw new BadRequestException('邮箱格式错误, 必须形如 username@website.domain 的格式');
        }
        /** ============== 正式检查 ============== */
        // 检查可用性(重复)
        if (!$domain->isEmailAvailable($this->email)) {
            throw new BadRequestException('邮箱已被使用');
        }
        /** ------- captch validating ------- */
        $this->captch = trim($this->captch);
        $correctCaptch = $domain->getLastCaptch($this->email);
        if ($correctCaptch == null) {
            throw new BadRequestException('未发送验证码');
        }
        if ($this->captch != $correctCaptch) {
            throw new BadRequestException('验证码错误或过期');
        }
        /** ------- password validating ------- */
        // 密码不进行 trim()
        /** ------- 完成注册 ------- */
        $domain->registerUserByEmail($this->username, $this->email, $this->password, 1);

        // 目前规定, 失败才返回消息
        //\PhalApi\DI()->response->setMsg("注册成功");

        return array();
    }


    /**
     * @api {post} /auth/logout 退出登录
     * @apiDescription 退出登录, 并清除登录凭据(token).
     * @apiVersion 2.0.0
     * @apiName logout
     * @apiPermission user
     * @apiGroup Auth
     * @apiSuccessExample 成功响应:
            {
                "ret": 200,
                "data": {},
                "msg": ""
            }
     */
    public function logout()
    {
        $domain = new Domain();
        $this->username = trim($this->username);
        $domain->clearToken($this->username);
    }
}
