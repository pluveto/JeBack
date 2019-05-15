<?php
namespace App\Domain;

use App\Model\Auth as Model;

class Auth
{

    /**
     * 生成邮箱验证码, 并储存. 然后把验证码发到邮件.
     *
     * @return void
     */
    public function sendEmailCaptch($email, $ip_ra, $ip_xf)
    {
        $model = new Model();

        //清理过期的验证码
        $model->clearExpiredCaptch();
        //TODO: 限制 ip 发送时长(一般60s), 统计发送次数, 超过5次失败则不再向该客户端发送验证码
        // 生成一个 6 位验证码
        $captch = mt_rand(100000, 999999);
        // 把验证码, ip地址, 发送时间, 邮箱存入数据库进行缓存. 请勿使用 session, 本 api 是无状态的.
        // TODO: **将来用户数增多, 请务必切换到 redis 等缓存*
        $model->insertCaptch(array(
            'type' => 0,
            'title' => $email,
            'ip_ra' => $ip_ra,
            'ip_xf' => $ip_xf,
            'captch' => $captch,
            'created_at' => time()
        ));
        (new \App\Helper\SysEmail)->sendCaptch($email, $captch);
    }

    public function getLastCaptchSendTimestamp($email)
    {
        $model = new Model();
        // 先清理过期的验证码
        $model->clearExpiredCaptch();
        $ret = $model->getLastCaptch(0, $email);
        if (array_key_exists('created_at', $ret)) {
            return intval($ret['created_at']);
        } else {
            return null;
        }
    }
}
