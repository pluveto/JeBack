<?php
namespace App\Api;

use PhalApi\Api;

use \App\Domain\Collection as Domain;


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
            ),
            'getCollection' => array(
                'collectionId' => array('name' => 'collectionId')
            ),
            'list' => array(
                'page' => array('name' => 'page'),
                'pageSize' => array('name' => 'pageSize')
            ),
            'search' => array(
                'title' => array('name' => 'title'),
                'collectionId' => array('name' => 'collectionId')
            ),
            'remove' => array(
                'collectionId' => array('name' => 'collectionId'),
                'username' => array('name' => 'username')
            )

        );
    }

    /**
     * 创建一个谱册
     * @param string username 用户名
     * @param string image 配图地址
     * @param string title 谱册标题
     * @param string description 谱册描述
     */
    public function addCollection()
    {
        $domain = new Domain();

        $username = $this->username;
        $image = $this->image;
        $title = $this->title;
        $description = $this->description;

        $domain->createCollection($username, $image, $title, $description);

        return array();
    }

    /**
     * 获取指定谱册
     * @param int collectionId
     * @return 返回指定谱册
     */
    public function getCollection(int $collectionId)
    {
        $domain = new Domain();

        $collection = $domain->getCollection($collectionId);
        return $collection;
    }

    /**
     * 获取一些谱册(分页)
     * @param int page 当前页码
     * @param int pageSize 一页多少谱册
     */
    public function list()
    {
        $domain = new Domain();

        $page = $this->page;
        $pageSize = $this->pageSize;
        return $domain->getList($page, $pageSize);
    }

    /**
     * 搜索谱册
     * @param string title 谱册名字
     * @return array 相关谱册
     */
    public function search()
    {
        $domain = new Domain();

        $title = $this->title;
        return $domain->search($title);
    }


    public function update()
    { }


    /**
     * 移出谱册(改变status),软删除)
     * Pluveto留言: 请把status改为2, 1 是对非作者隐藏, 2 是对所有用户隐藏
     * @param int collectionId
     */
    public function remove()
    {
        $domain = new Domain();

        $user = \App\Domain\Auth::$currentUser;
        $collectionId = $this->collectionId;
        $collection = $domain->getCollection($collectionId);
        if ($user['id'] != $collection['owner_id']) {
            throw error;
        }
        $domain->removeCollection($collectionId);
        return array();
    }
}
