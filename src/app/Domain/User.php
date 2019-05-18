<?php
namespace App\Domain;

use App\Model\User as UserModel;
use App\Domain\Group as GroupDomain;

/**
 * 用户 Domain 类 (不得在本类添加本应属于Auth类的方法!!)
 * 
 * @author ZhangZijing <i@pluvet.com> 2019-5-18
 */
class User
{

    public function getUserInfo($userId)
    {
        $userModel = new UserModel();
        $user = $userModel->getUser($userId);
        return $this->packUpUserInfo($user);
    }

    public function getUserInfoByEmail($email)
    {
        $userModel = new UserModel();
        $user = $userModel->getUserByEmail($email);
        return $this->packUpUserInfo($user);
    }
    private function packUpUserInfo($user)
    {
        return [
            'id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'phone' => $user['phone'],
            'token' => $user['token'],
            'group' => $this->getUserGroups($user['id'])
        ];
    }
    public function getUserInfoByPhone($phone)
    { }

    /**
     * 根据用户id获取组,返回值为数组
     * @param  int $uid     用户id
     * @return array       用户所属的组
     */
    public function getUserGroups($uid)
    {
        static $groups = array();
        if (isset($groups[$uid])) return $groups[$uid];
        $groupDomain = new GroupDomain();
        $userGroups = $groupDomain->getUserInGroups($uid);
        $groups[$uid] = $userGroups ?: array();
        return $groups[$uid];
    }
}
