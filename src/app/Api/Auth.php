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
            'loginByEmail' => array(
                'email' => array('name' => 'email')
            ),
            //请勿在此处进行正则校验, 因为这样不利于自定义异常时返回结果.
            'sendEmailCaptch' => array(
                'email' => array('name' => 'email', 'require' => true, 'min' => 5, 'max' => 255)
            ),
            'registerByEmail' => array(
                'email' => array('name' => 'email', 'require' => true, 'min' => 5, 'max' => 255),
                'captch' => array('name' => 'captch', 'require' => true, 'min' => 6, 'max' => 6),
                'password' => array('name' => 'password', 'require' => true, 'min' => 6, 'max' => 255),
            ),
            'loginByEmail' => array(
                'email' => array('name' => 'email', 'require' => true, 'min' => 5, 'max' => 255),
                'nonce' => array('name' => 'nonce', 'require' => true),
                'sign' => array('name' => 'sign', 'require' => true),
                'captch' => array('name' => 'captch', 'require' => false, 'min' => 6, 'max' => 6), // 账号异常时使用验证码登录                
            )

        );
    }

    /**
     * 默认接口服务
     * @desc 默认接口服务，当未指定接口服务时执行此接口服务
     * @return string title 标题
     * @return string content 内容
     * @return string version 版本，格式：X.X.X
     * @return int time 当前时间戳
     * @exception 400 非法请求，参数传递错误
     */
    public function index()
    {
        $base_url = \PhalApi\DI()->urlHelper->baseUrl();
        return array(
            'title' => 'Hello, Moe Auth!',
            'version' => '1.0.0',
            'time' => $_SERVER['REQUEST_TIME'],
            'urls' => array(
                'site_index_url' => $base_url . "/site/index",
            )
        );
    }

    /**
     * 获取 nonce (有效期为30s)
     *
     * @return void
     */
    public function getNonce()
    {
        $domain = new Domain();
        // 生成 nonce
        $nonce = $domain->prepareNounce();
        return array(
            'nonce' => $nonce,
        );
    }

    /**
     * 使用邮箱登录
     * 
     * 登录过程:
     * 
     * 1. 用户从服务器获取一个随机串 nonce。服务器保存 nouce+timestamp
     * 2. 用户计算 sign = sha1(sha1(email + password) + nouce).
     * 3. 提交 sign, email, nonce, type=0(表示邮箱登录)
     * 4. 服务器判断 nonce 是否过期, 如果没过期, 继续进行其他验证
     * 4. 登录成功，得到 token（有效期为30天，若主动注销则立刻过期）。
     * 5. 利用 token 可以实现免登录。
     * 6. 为了防止重播攻击，每次请求, 传入sign = sha1(sha1(timestamp) + token + username) 和 timestamp 以及其它参数
     * 
     * 所需参数: email, password, nonce, sign
     * 
     * @return void
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
        if (!$domain->checkSignByEmailAndNonce($this->email, $this->nonce, $this->sign)) {
            throw new BadRequestException('请求签名错误');
        }

        return array(
            'token' => $domain->setUpTokenByEmail($this->email, $this->nonce)
        );
    }
    /**
     * 发送邮件验证码. 不检查邮箱存在性.
     * 
     * 应用场景: 注册/异常登录验证/找回密码/更改密码/注销
     * 
     * 所需参数: email
     * 
     * 路由: /auth/login/email
     * 
     * @return void
     */
    public function sendEmailCaptch()
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
     * 所需参数: email, captch, password
     * 
     * @return void
     */
    public function registerByEmail()
    {
        $domain = new Domain();
        /** ------- email validating ------- */
        // 检查格式
        $this->email = trim($this->email);
        if (!\App\Helper\Validator::checkEmailFormat($this->email)) {
            throw new BadRequestException('邮箱格式错误');
        }
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
        $domain->registerUserByEmail($this->email, $this->password);

        \PhalApi\DI()->response->setMsg("注册成功");

        return array();
    }
}
