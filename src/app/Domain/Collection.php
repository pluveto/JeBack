<?php
namespace App\Domain;

use App\Model\Collection as CollectionModel;
use App\Model\User as UserModel;

/**
 * Collection 谱册类
 * @author LW <lw1020573989@live.com>
 */

class Collection
{

    /**
     * 创建一个空谱册
     * 
     * @param string username
     * @param string image 图片地址
     * @param string title 谱册名
     * @param string description 描述
     */
    public function createCollection($username, $image, $title, $description)
    {
        $userModel = new UserModel();
        $collcetionModel = new CollectionModel();

        $user = $userModel->getUserByUsername($username);
        $data = array(
            'user_id' => $user['id'],
            'image' => $image,
            'title' => $title,
            'description' => $description,
            'status' => 0,
            'view_num' => 0,
            'created_at' => time(),
            'updated_at' => time()
        );

        $collcetionModel->createCollection($data);
    }


    /**
     * 向谱册加入一个谱子
     * 
     * @param int scoreId
     * @param int collectionId 
     */
    public function addScoreToCollection($scoreId, $collectionId)
    {
        $collcetionModel = new CollectionModel();

        $data = array(
            'collection_id' => $collectionId,
            'score_id' => $scoreId
        );
        $collcetionModel->addScoreToCollection($data);
    }
    /**
     * 向谱册加入一些谱子
     * 
     * @param array scoreId
     * @param int collectionId 
     */
    public function addScoresToCollection($scoreIdList, $collectionId)
    {
        $collcetionModel = new CollectionModel();

        $data = array();
        for ($i = 0; $i < count(scoreIdList); $i++) {
            $data[] = ['collection_id' => $collectionId, 'score_id' => $scoreIdList[i]];
        }
        $collcetionModel->addScoresToCollection($data);
    }

    /**
     * 获取指定谱册
     * 
     * @param int collectionId
     */
    public function getCollection($collectionId)
    {
        $collcetionModel = new CollectionModel();
        return $collcetionModel->get($collectionId);
    }

    /**
     * 删除指定谱册(软删除) 将状态从status 0 =>2
     * 0 为正常 1 是对非作者隐藏, 2 是对所有用户隐藏
     * @param int collectionId
     */
    public function removeCollection($collectionId)
    {
        $collcetionModel = new CollectionModel();
        $data = array(
            'id' => $collectionId,
            'status' => 2
        );
        $collcetionModel->changeCollectionStatus($data);
    }

    /**
     * 获取谱册列表
     * @param int page 当前页码
     * @param int pageSize 一页多少谱册
     * @ 0代表可见谱册
     */
    public function getList($page, $pageSize)
    {
        $collcetionModel = new CollectionModel();
        return $collcetionModel->getList($page, $pageSize, 0);
    }


    /**
     *  根据谱册名 进行搜索
     * @param string title
     * @return 相关谱册列表
     */
    public function search($title)
    {
        $collcetionModel = new CollectionModel();
        return $collcetionModel->searchByName($title);
    }

    /**
     * 检查曲谱是否存在
     *
     * @param integer $collectionId
     * @return void
     */
    public function checkCollectionExist(int $collectionId)
    {
        $model = new Model();
        $score = $model->get($collectionId);
        return $score != null;
    }
}
