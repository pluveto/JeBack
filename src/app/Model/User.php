<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

/**
 * 用户模型类
 * @author ZhangZijing <i@pluvet.com> 2019-5-15
 */
class User extends NotORM
{


    /**
     * 通过用户名获取一个用户
     *
     * @author ZhangZijing <i@pluvet.com>
     * @param string $email
     * @return array
     */
    public function getUserByUsername(string $username)
    {
        return $this->getORM()->where('username', $username)->fetchOne();
    }
    /**
     * 通过邮件地址获取一个用户
     *
     * @author ZhangZijing <i@pluvet.com>
     * @param string $email
     * @return array
     */
    public function getUserByEmail(string $email)
    {
        return $this->getORM()->where('email', $email)->fetchOne();
    }

    /**
     * 更新具有指定邮箱的用户的 token
     *
     * @author ZhangZijing <i@pluvet.com>
     * @param string $email
     * @param string $token
     * @return //array user
     */
    public function updateUserTokenByEmail(string $email, string $token)
    {
        $this->getORM()->where('email', $email)->update(
            array('token' => $token)
        );
        //return $this->getORM()->where('email', $email)->fetchOne();
    }

    /**
     * 通过username 更新用户的 token
     * 
     * @param string $username
     * @param string $token
     * @author LW <lw1020573989@live.com>
     * @return void
     */
    public function updateToken(string $username, string $token)
    {
        return $this->getORM()->where('username', $username)->update(
            array('token' => $token)
        );
    }
}
