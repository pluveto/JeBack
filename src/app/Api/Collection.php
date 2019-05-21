<?php
namespace App\Api;

use PhalApi\Api;
use PhalApi\Exception\BadRequestException;

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
                'temp_image_id' => ['name' => 'temp_image_id', 'require' => false, 'min' => 0, 'max' => 255, 'type' => 'int', 'default' => 0], // 图片 id 为零表示不指定id
                'title' => array('name' => 'title'),
                'description' => array('name' => 'description')
            ),
            'getCollection' => array(
                'collectionId' => array('name' => 'collectionId')
            ),
            'list' => array(
                'page' => array('name' => 'page'),
                'pageSize' => array('name' => 'pageSize'),
                'page' => array('name' => 'page'),
                'pageSize' => array('name' => 'pageSize')
            ),
            'search' => array(
                'title' => array('name' => 'title'),
                'collectionId' => array('name' => 'collectionId')
            ),
            'update' => array(
                'collectionId' => array('name' => 'collectionId'),
                'temp_image_id' => ['name' => 'temp_image_id', 'require' => false, 'min' => 0, 'max' => 255, 'type' => 'int', 'default' => 0], // 图片 id 为零表示不指定id
                'title' => array('name' => 'title'),
                'description' => array('name' => 'description'),
                'score_id' => array('name' => 'score_id')
            ),
            'remove' => array(
                'collectionId' => array('name' => 'collectionId')
            )

        );
    }

    /**
     * 创建一个谱册
     * @param string temp_image_id 配图id
     * @param string title 谱册标题
     * @param string description 谱册描述
     */
    public function addCollection()
    {
        $domain = new Domain();
        $uploadDomain = new \App\Domain\Upload();

        /** ============== 格式检查 ============== */
        $this->title = trim($this->title);
        $this->description = trim($this->description);

        $user = \App\Domain\Auth::$currentUser;

        /** ============== 正式检查 ============== */
        // 检查临时图片id是否存在并属于当前用户(同时也检查了图片的有效性)
        $userId = $user['id'];
        if ($this->temp_image_id > 0 && !$uploadDomain->checkTempImageIdOwnerMatch($this->temp_image_id, $userId)) {
            throw new BadRequestException("图片长期未用被清理, 或者填入了错误的图片id.");
        }
        /** ============== 正式业务 ============== */
        $imageUrl = "";
        $imagePath = "";
        //如果提交了图片id, 就把图片以正式文件转存
        if ($this->temp_image_id > 0) {
            $this->image_id = $uploadDomain->saveImage($this->temp_image_id,  0, $imageUrl, $imagePath);
        } else {
            $this->image_id = 0;
        }
        if ($this->image_id == null) {
            $this->image_id = 0;
        }

        $id = $domain->createCollection($userId, $imagePath, $this->image_id, $this->title, $this->description);
        return [
            'id' => $id, //...
            //'image_url' => $imageUrl 这个不用返回到客户端，估计前端用不到
        ];
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
     * @return 返回结果
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
     * @param int page 当前页码
     * @param int pageSize 一页多少谱册
     * @return array 相关谱册
     */
    public function search()
    {
        $domain = new Domain();

        $title = $this->title;
        $page = $this->page;
        $pageSize = $this->pageSize;
        return $domain->search($title, $page, $pageSize);
    }

    /**
     * 更新谱册
     * @param int collectionId 谱册id
     * @param int temp_image_id 临时图片id
     * @param string title 谱册名
     * @param string description 描述
     * @param array score_id 谱册 曲谱全部id
     * @return int 谱册id
     */ 
    public function update()
    {
        $domain = new Domain();
        $uploadDomain = new \App\Domain\Upload();

        $user = \App\Domain\Auth::$currentUser;
        //检查当前更新谱册是否属于当前用户
        if ($domain->checkIdOwnerMatch($this->collectionId, $user['id'])) {
            throw new BadRequestException("谱册不存在, 或者你没有权限修改此谱册.");
        }
        // 检查临时图片id是否存在并属于当前用户(同时也检查了图片的有效性)
        if ($this->temp_image_id > 0 && !$uploadDomain->checkTempImageIdOwnerMatch($this->temp_image_id, $user['id'])) {
            throw new BadRequestException("图片长期未用被清理, 或者你没有权限引用此图片.");
        }

        $imageUrl = "";
        $imagePath = "";
        // 如果提交了新的图片id, 但是之前已经关联了图片, 就删除图片解除关联
        $imageOnServer = $uploadDomain->getImageByCollectionId($this->collectionId);
        if ($imageOnServer != null && $this->temp_image_id > 0) {
            $uploadDomain->removeFile($imageOnServer['id']);
            $this->image_id = $uploadDomain->saveImage($this->temp_image_id,  0, $imageUrl, $imagePath);
        } elseif ($imageOnServer != null && $this->temp_image_id == 0) {
            $this->image_id = $imageOnServer['id'];
        } elseif ($imageOnServer == null && $this->temp_image_id > 0) {
            $this->image_id = $uploadDomain->saveImage($this->temp_image_id,  0, $imageUrl, $imagePath);
        }
        // 第三四种情况无需考虑
        if ($this->image_id == null) {
            $this->image_id = 0; //image_id 为0 就表示该内容不配图
        }

        $this->title = trim($this->title);
        $this->description = trim($this->description);

        $domain->update($this->collectionId, $this->title, $this->description, $this->score_id, $this->image_id, $imagePath);

        return ['id' => $this->collectionId];
    }


    /**
     * 移出谱册(改变status),软删除)
     * Pluveto留言: 请把status改为2, 1 是对非作者隐藏, 2 是对所有用户隐藏
     * // TODO: 移除谱册时，还要移除相应的标签和评论。
     * @param int collectionId
     */
    public function remove()
    {
        $domain = new Domain();

        $user = \App\Domain\Auth::$currentUser;
        $collectionId = $this->collectionId;
        $collection = $domain->getCollection($collectionId);
        if ($user['id'] != $collection['user_id']) {
            throw error;
        }
        $domain->removeCollection($collectionId);
        return array();
    }
}
