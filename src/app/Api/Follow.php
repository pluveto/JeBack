<?php
namespace App\Api;

use PhalApi\Api;
use PhalApi\Exception\BadRequestException;

use \App\Domain\Auth as AuthDomain;
use \App\Domain\Follow as Domain;

/**
 *
 * 关注列表操作类
 * 
 * @author Ricardo2001zg <miao@ricardo2001zg.moe> 2019-5-17
 * Domain层checkUserExist与getFollowingByUser未完成
 */

class Follow extends Api
{
    public function getRules()
    {
        return [
            'getFollwerByUser' => [
                'user_id' => ['name' => 'user_id', 'require' => true, 'type' => 'int'],
            ]
        ];
    }

    /**
     * 获取某用户的关注列表
     * @routine /follow/list/followingbyuser
     * @param int uid
     * @return array
     */
    public function getFollowingByUser()
    {
        $domain = new Domain();
        $authDomain = new AuthDomain();
        if (!$authDomain->checkUserExist($this->user_id)) {
            return BadRequestException("用户不存在");
        }
        $resultArray = $domain->getFollowingByUser($this->user_id);
        return [
            'following' => $resultArray,
        ];
    }

    /**
     * 获取某用户的粉丝 id 列表
     *
     * @routine /follow/list/followerbyuser
     * @param int uid
     * @return array
     */
    public function getFollwerByUser()
    {
        $domain = new Domain();
        $authDomain = new AuthDomain();
        if (!$authDomain->checkUserExist($this->user_id)) {
            return BadRequestException("用户不存在");
        }
        $resultArray = $domain->getFollowerByUser($this->user_id);
        return [
            'followers' => $resultArray,
        ];
    }

    /**
     * 令当前用户关注某用户
     * @routine /follow/add
     * @return void
     */
    public function addFollow()
    {
        $currentUser = \App\Domain\Auth::$currentUser;
    }

    /**
     * 令当前用户停止关注某用户
     * @routine /follow/remove
     * @return void
     */
    public function removeFollow()
    {
        $currentUser = \App\Domain\Auth::$currentUser;
    }

    /**
     * 获取当前用户的关注列表
     * @routine /follow/following
     * @return void
     */
    public function getFollowing()
    {
        $currentUser = \App\Domain\Auth::$currentUser;
    }

    /**
     * 获取当前用户的粉丝列表
     * @routine /follow/follower
     * @return void
     */
    public function getFollower()
    {
        $currentUser = \App\Domain\Auth::$currentUser;
    }
}
