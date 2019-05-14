<?php
namespace App\Common;

use PhalApi\Filter;
use PhalApi\Exception\BadRequestException;

/**
 * 该类负责检查签名和权限。
 * @author ZhangZijing <i@pluvet.com> 2019-5-14
 */
class SignFilter implements Filter
{
    public function check()
    {
        //首先对签名进行检查
        SignFilter::_sign_check();
        //再让 Auth 模块进行权限检查
        SignFilter::_auth_check();
    }

    private function _sign_check()
    {
        /**
         * 用户如何生成 sign: 
         *  cf：https://blog.csdn.net/qq_18495465/article/details/79248608
         * 
         *  1. 用户准备一个随机串 nonce。
         *  2. 用户计算 sign = sha1(nouce + username + password + timestamp)登录，提交 sign 和 timestamp 和 nonce，以及其它参数。
         *  3. 如果 nonce 过期则重新申请并提交。
         *  4. 登录成功，得到 token（有效期为30天，若主动注销则立刻过期）。
         *  5. 利用 token 可以实现免登录。为了防止重播攻击，每次
         */

        $client_signature = \PhalApi\DI()->request->get('signature');
        $client_timestamp = \PhalApi\DI()->request->get('timestamp');
        $user_id
        $nonce =; //todo: 从数据库获取nonce
        $tempArray = array();
        $token = 'Your Token Here ...';
        $server_signature = sha1($user_id . $token . $client_timestamp . $nonce);

        if ($server_signature != $client_signature) {
            throw new BadRequestException('Wrong sign', 1);
        }
    }
    private function _auth_check()
    {
        $api = \PhalApi\DI()->request->get('service', 'Default.Index'); //获取当前访问的接口        
        $userId = \PhalApi\DI()->request->get('user_id', 0); //获取用户id参数
        $r = \PhalApi\DI()->authLite->check($api, $userId);
        if (!$r) {
            throw new BadRequestException('权限不足');
        }
    }
}
