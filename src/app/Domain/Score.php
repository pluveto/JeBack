<?php
namespace App\Domain;

use App\Model\Score as Model;
use App\Model\Upload as UploadModel;

/**
 * 曲谱 Domain 类
 * 
 * @author ZhangZijing <i@pluvet.com> 2019-5-17
 */
class Score
{
    /**
     * 检查曲谱Id是否存在并属于某用户
     *
     * @param int $scoreId
     * @param int $userId
     * @return bool
     */
    public function checkIdOwnerMatch($scoreId, $userId)
    {
        $model = new Model();
        $score = $model->getTempImage($scoreId);
        return $score && ($score['user_id'] == $userId);
    }

    public function addScore(
        string $tilte,
        string $text,
        string $alias,
        string $anime,
        string $key,
        int    $type,
        string $description,
        string $addition,
        int    $image_id
    ) {
        $model = new Model();
        $model->insert([
            'tilte' => $tilte,
            'text' => $text,
            'alias' => $alias,
            'anime' => $anime,
            'key' => $key,
            'type' => $type,
            'description' => $description,
            'addition' => $addition,
            'image_id' => $image_id
        ]);
    }
    public function removeScore($id)
    {
        $model = new Model();
        return $model->delete($id);
    }
    public function getScoreList($page, $perpage)
    {
        $rs = array('items' => array(), 'total' => 0);

        $model = new Model();
        $uploadDomain = new \App\Domain\Upload();
        $userDomain = new \App\Domain\User();
        $items = $model->getScoreListItems($page, $perpage);
        /*
           return array_filter(array(
            'id'            => $score['id'],
            'tilte'         => $score['tilte'],
            'text'          => $score['text'],
            'user'          => $userDomain->getUserSimpleInfo($score['user_id']),
            'alias'         => json_decode($score['alias']),
            'anime'         => $score['anime'],
            'key'           => $score['key'],
            'type'          => $score['type'],
            'description'   => $score['description'],
            'addition'      => $score['addition'],
            'image_url'     => $uploadDomain->getScoreImageUrl($scoreId),
        ));
        */
        foreach ($items as &$item) {


            $item['user'] = $userDomain->getUserSimpleInfo($item['user_id']);
            $item['user_id'] = null;
            

            $item['image_url'] =  $uploadDomain->getScoreImageUrl($item['id']);
            $item['image_id'] = null;

            $item = array_filter($item);
        }
        $total = $model->getScoreCount();

        $rs['items'] = $items;
        $rs['total'] = $total;
        return $rs;
    }
    public function packUpScoreList($page, $perpage)
    {
        $rs = array();
        $list = $this->getScoreList($page, $perpage);

        $rs['items'] = $list['items'];
        $rs['total'] = $list['total'];
        $rs['page'] = $this->page;
        $rs['perpage'] = $this->perpage;
        return  $rs;
    }
    public function packUpScoreInfo($scoreId)
    {
        $model = new Model();
        $uploadDomain = new \App\Domain\Upload();
        $userDomain = new \App\Domain\User();
        $score = $model->get($scoreId);
        return array_filter(array(
            'id'            => $score['id'],
            'tilte'         => $score['tilte'],
            'text'          => $score['text'],
            'user'          => $userDomain->getUserSimpleInfo($score['user_id']),
            'alias'         => json_decode($score['alias']),
            'anime'         => $score['anime'],
            'key'           => $score['key'],
            'type'          => $score['type'],
            'description'   => $score['description'],
            'addition'      => $score['addition'],
            'image_url'     => $uploadDomain->getScoreImageUrl($scoreId),
        ));
    }
}
