<?php
namespace App\Common;

use PhalApi\Filter;
use PhalApi\Exception\BadRequestException;

/**
 * 该类负责检查签名和权限。
 * @author ZhangZijing <i@pluvet.com> 2019-5-14~2019-5-16
 */
class SignFilter implements Filter
{
    public function check()
    {
        $api = \PhalApi\DI()->request->get('service'); //获取当前访问的接口
        //首先对签名进行检查
        SignFilter::_sign_check();
        //再让 Auth 模块进行权限检查
        SignFilter::_auth_check();

        return true;
    }

    private function _sign_check()
    {
        /**
         * 除了登录外, 其余的 sign 均为 sha1(timestamp + username + token)
         */
        $domain = new \App\Domain\Auth();

        $clientUsername = trim(\PhalApi\DI()->request->get('username'));
        $clientSign = \PhalApi\DI()->request->get('sign');
        $clientTimestamp = \PhalApi\DI()->request->get('timestamp');
        if (!(isset($clientUsername) && isset($clientSign) && isset($clientTimestamp))) {
            throw new BadRequestException('普通请求必须至少提供以下参数: username, timestamp, sign');
        }
        if (time() - intval($clientTimestamp) > 30) {
            throw new BadRequestException('过期请求. 请检查客户端时间设置. 服务器时间戳为: ' . time());
        }


        // 签名检查, 直接保证了用户存在, 不要再重复检查.
        $expectedSign = $domain->getSign($clientTimestamp, $clientUsername);
        if ($expectedSign != $clientSign) {
            throw new BadRequestException('无效签名');
        }
    }
    private function _auth_check()
    {
        $domain = new \App\Domain\Auth();
        //获取当前访问的接口, 此处已经是转换后的路由, 即 service.action格式
        $api = \PhalApi\DI()->request->get('service');
        if ($api == null) {
            throw new BadRequestException('错误接口');
        }
        $username = trim(\PhalApi\DI()->request->get('username'));
        if (!$domain->checkAuth($api, $username)) {
            throw new BadRequestException('权限不足');
        }
    }
}
