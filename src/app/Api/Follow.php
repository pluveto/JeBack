<?php
namespace App\Api;

use PhalApi\Api;
use PhalApi\Exception\BadRequestException;

use \App\Domain\Follow as Domain;

/**
 * 关注列表操作类
 * 
 * @author Ricardo2001zg <miao@ricardo2001zg.moe> 2019-5-17
 */

class Follow extends Api
{
    //创建一个关注              /follow/add
    public function addFollow()
    {
        $follow = new Follow();
        //code
    }

    //列出一些关注            /follow/list
    public function listFollow()
    {
        //code
    }

    //通过关注者查找被关注者    /follow/following
    public function followingFollow()
    {
        //code
    }

    //通过被关注者查找关注者    /follow/followed
    public function followedFollow()
    {
        //code
    }

    //删除一个关注              /follow/remove
    public function removeFollow()
    {
        //code
    }
}
