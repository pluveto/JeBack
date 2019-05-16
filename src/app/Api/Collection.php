<?php
namespace App\Api;

use PhalApi\Api;

/**
 * 谱册API
 * @author LW <lw1020573989@live.com>
 */

class Collection extends Api
{
    public function getRules()
    {
        return array(
            'addCollection' => array(
                'username' => array('name' => 'username'),
                'image' => array('name' => 'image'),
                'title' => array('name' => 'title'),
                'description' => array('name' => 'description')
            )
        );
    }

    /**
     * @param string username 用户名
     * @param string image 配图地址
     * @param string title 谱册标题
     * @param string description 谱册描述
     */
    public function addCollection()
    {
        $username = $this->username;
        $image = $this->image;
        $title = $this->title;
        $description = $this->description;
    }
    public function list()
    { }
    public function search()
    { }
    public function update()
    { }
    public function remove()
    { }
}
