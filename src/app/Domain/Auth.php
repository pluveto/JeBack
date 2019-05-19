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
     * 当前登录用户(只含有user表中各列字段)
     * 
     * 一旦用户访问, 并检查签名通过后, 就会将此处赋值.
     * 具体来说, 这个属性只在 SignFilter 进行复制
     * 
     * 禁止修改!!!
     * 
     * 不必判断这里是否为 null, 因为所有调用该属性的方法, 
     * 必然是经过前置签名检查的方法, 所以必然已经登录, 
     * 因而必然已经被赋值.
     *
     * @var array
     */
    public static $currentUser = null;

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
     * 协助 AuthLite 模块进行权限检查. 请确保用户存在.
     * AuthLite 权限检查流程说明: 
     *   获取用户需要验证的所有有效规则列表:
     *      查询用户在哪个组, 查询这个组对应了哪些规则, 对于每条数据库中存的规则:
     *          如果规则设置了 condition:
     *              把condition中的{xxx}格式的字符串替换为用户的xxx字段, 即user['xxx']
     *              然后把condition解析为php代码, 添加到内存的规则列表
     *          如果规则没设置 condition
     *              直接向规则列表添加服务名
     *      如果用户满足规则中的任何一条, 返回true(默认)
     *      或者: 如果用户满足规则中的每一条, 才返回true(参数 3 = and)
     * @param string $api
     * @param string $username
     * @return mixed 成功返回用户, 失败返回false
     */
    public function checkAuth($service, $username, $strict = false)
    {
        $model = new UserModel();
        $user = $model->getUserByUsername($username);
        $userId = $user['id'];
        $mode = 'or';
        if ($strict) {
            $mode = 'and';
        }
        if (!\PhalApi\DI()->authLite->check($service, $userId)) {
            return false;
        }
        return $user;
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
        return $expectedSign == $signToCheck;
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
     * @param string $username
     * @param string $email
     * @param string $password 明文密码
     * @param int $group 1 为普通用户, 2为管理员. 由于一个用户可以对应多组, 表不设计为单射.
     * @return void
     */
    public function registerUserByEmail(string $username, string $email, string $password, $group = 1)
    {
        $model = new UserModel();
        $salt = \PhalApi\DI()->config->get('je.security.salt');
        // 由于登录时密码非明文传输, 此处的salt实际上暴露在了客户端
        $passwordSalted = sha1("moeje" . $password);
        $user = $model->insert(array(
            'username' => $username,
            'email' => $email,
            'password' => $passwordSalted,
            'created_at' => time(),
            'updated_at' => time()
            // 注意: 用户组不在此表
        ));


        $authModel = new Model();
        $authModel->setUserGroup($user['id'], $group);
        // 删除该邮箱的验证码
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
