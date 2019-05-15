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
     * 通过邮件地址获取一个用户
     *
     * @param string $email
     * @return array()
     */
    public function getUserByEmail($email)
    {
        return $this->getORM()->where('email', $email)->fetchOne();
    }

    public function addUser($data)
    {
        return $this->getORM()->insert($data);
    }
}
