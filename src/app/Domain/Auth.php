<?php
namespace App\Domain;

use App\Model\Auth as Model;
use App\Model\User as UserModel;

/**
 * Auth 校验 Domain 类
 * 
 * @author ZhangZijing <i@pluvet.com>
 */
class Auth
{


    /**
     * 获取签名
     *
     * @param string $password
     * @param string $nonce
     * @return void
     */
    public function getSign(string $password, string $nonce)
    {
        return sha1($password . $nonce);
    }
    /**
     * 删除无效的 Nonce, 然后检查 Nonce 是否存在并有效
     *
     * @return bool 是否有效. true 为是.
     */
    public function checkNonce(string $nonce)
    {
        $model = new Model();
        $model->clearExpiredNonce();
        $nonceArray = $model->getNonce($nonce);
        // PS: 筛选下的 nonce 全为有效且未过期
        if ($nonceArray == null) return false;
        // 清除此次的 nonce
        $nonceArray = $model->deleteNonce($nonce);

        return true;
    }

    public function checkSignByEmailAndNonce(string $email, string $nonce, string $signToCheck)
    {
        $model = new UserModel();

        $user = $model->getUserByEmail($email);
        $expectedSign = $this->getSign($user['password'], $nonce);
        return ($expectedSign == $signToCheck);
    }


    /**
     * 为用户创建token
     *
     * @param string $email
     * @return string token
     */
    public function setUpTokenByEmail(string $email)
    {
        $model = new UserModel();
        $token = sha1(random_bytes(40));
        return $model->updateUserTokenByEmail($email, $token);
    }

    /**
     * 生成 Nounce, 同时删除过期的 Nonce
     *
     * @return string nonce
     */
    public function prepareNounce()
    {
        $model = new Model();
        $model->clearExpiredNonce();

        $nonce = sha1(random_bytes(32));
        // PS: 还可以在其中增加 ip_ra 和 ip_xf 增加安全性.
        $model->insertNonce(array(
            'nonce' => $nonce,
            'created_at' => time(),
            'valid' => 1
        ));
        return $nonce;
    }
    /**
     * 生成邮箱验证码, 并储存. 然后把验证码发到邮件.
     *
     * @param string $email
     * @param string $ip_ra
     * @param string $ip_xf
     * @return void
     */
    public function sendEmailCaptch(string $email, string $ip_ra, string $ip_xf)
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
    /**
     * 清理过期验证码, 并获取最近一次发送到某邮箱验证码的时间戳
     *
     * @param string $email
     * @return int 时间戳, 查询不到则返回 null
     */
    public function getLastCaptchSendTimestamp(string $email)
    {
        $model = new Model();
        // 先清理过期的验证码
        $model->clearExpiredCaptch();
        // 取出创建时间
        $ret = $model->getLastCaptch(0, $email);
        if ($ret != null) {
            return intval($ret['created_at']);
        } else {
            return null;
        }
    }

    /**
     * 清理过期验证码, 并获取最近一次发送到某邮箱的验证码
     *
     * @param string $email
     * @return string 验证码, 查询不到则返回 null
     */
    public function getLastCaptch(string $email)
    {
        $model = new Model();
        // 先清理过期的验证码
        $model->clearExpiredCaptch();
        $ret = $model->getLastCaptch(0, $email);
        if ($ret != null) {
            return $ret['captch'];
        } else {
            return null;
        }
    }

    /**
     * 判断邮箱是否可用
     *
     * @param string $email
     * @return boolean 可用为 true
     */
    public function isEmailAvailable(string $email)
    {
        $model = new UserModel();
        return $model->getUserByEmail($email) == null;
    }

    public function registerUserByEmail(string $email, string $password)
    {
        $model = new UserModel();
        $salt = \PhalApi\DI()->config->get('je.security.salt');
        // 加盐防止彩虹表攻击
        // PS: 由于使用签名认证, 停止使用固定该 salt
        $passwordSalted = sha1($email . $password);
        $model->addUser(array(
            'email' => $email,
            'password' => $passwordSalted
        ));
    }
}
