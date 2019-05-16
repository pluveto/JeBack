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
            'owner_id' => $user['id'],
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
     * 向谱册加入谱子
     * 
     * @param array||num scoreId
     * @param num collectionId 
     */
    public function addScoreToCollection($scoreId, $collectionId)
    {
        $collcetionModel = new CollectionModel();

        if (is_array($scoreId)) {
            for ($i = 0; $i < count($scoreId); $i++) {
                $data = array(
                    'collection_id' => $collectionId,
                    'score_id' => $scoreId[i]
                );
                $collcetionModel->addScoreToCollection($data);
            }
        }

        $data = array(
            'collection_id' => $collectionId,
            'score_id' => $scoreId
        );
        $collcetionModel->addScoreToCollection($data);
    }
}
