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

    public function loginByEmail()
    {
        $base_url = \PhalApi\DI()->urlHelper->baseUrl();
        return array(
            'status' => 'Success!',
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
        return null;
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
    }
}
