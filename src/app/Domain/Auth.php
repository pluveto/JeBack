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
     * 协助 AuthLite 模块进行权限检查. 请确保用户存在.
     *
     * @param string $api
     * @param string $username
     * @return bool 通过检查返回 true
     */
    public function checkAuth($api, $username)
    {
        $model = new UserModel();
        $user = $model->getUserByUsername($username);
        $userId = $user['id'];
        return \PhalApi\DI()->authLite->check($api, $userId);
    }
    /**
     * 计算常规签名
     *
     * @param string $timestamp
     * @param string $username
     * @return void
     */
    public function getSign(string $timestamp, string $username)
    {
        // 查找用户, 取出其 token
        $model = new UserModel();
        $user = $model->getUserByUsername($username);
        $token = $user['token'];
        return sha1($timestamp . $username . $token);
    }

    /**
     * 计算登录签名
     * 
     * @param string $password 摘要后的密码
     * @param string $title email 或 手机号
     * @param string $nonce 有效随机串
     * @return void
     */
    public function getLoginSign(string $nonce, $title, string $password)
    {
        return sha1($nonce . $title . $password);
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

    /**
     * 检查登录签名
     *
     * @param string $email
     * @param string $nonce
     * @param string $signToCheck
     * @return bool 是否和预期一致(即是否正确), 是为true
     */
    public function checkLoginSignByEmailAndNonce(string $email, string $nonce, string $signToCheck)
    {
        $model = new UserModel();

        $user = $model->getUserByEmail($email);
        $expectedSign = $this->getLoginSign($nonce, $email, $user['password']);
        return ($expectedSign == $signToCheck);
    }


    /**
     * 为用户创建 token
     *
     * @param string $email
     * @return string token
     */
    public function setUpTokenByEmail(string $email)
    {
        $model = new UserModel();
        $token = sha1(random_bytes(40));
        $model->updateUserTokenByEmail($email, $token);
        return $token;
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

        // 清理过期的验证码
        $model->clearExpiredCaptch();
        // 清理已发送到改邮箱的验证码
        $model->clearUserCaptch($email, 0);
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

    /**
     * 通过邮箱注册用户
     * 
     * @param string $email
     * @param string $password 明文密码
     * @return void
     */
    public function registerUserByEmail(string $username, string $email, string $password)
    {
        $model = new UserModel();
        $salt = \PhalApi\DI()->config->get('je.security.salt');
        // 由于登录时密码非明文传输, 此处的salt实际上暴露在了客户端
        $passwordSalted = sha1("moeje" . $password);
        $model->addUser(array(
            'username' => $username,
            'email' => $email,
            'password' => $passwordSalted,
            'created_at' => time(),
            'updated_at' => time()
        ));
        // 删除该邮箱的验证码
        $authModel = new Model();
        $authModel->clearUserCaptch($email, 0);
    }

    /**
     * 通过邮箱清理token
     */
    public function clearToken($username)
    {
        $model = new UserModel();
        $model->updateToken($username, '');
    }
}
