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
     * 检查曲谱是否存在
     *
     * @param integer $scoreId
     * @return void
     */
    public function checkScoreExist(int $scoreId)
    {
        $model = new Model();
        $score = $model->get($scoreId);
        return $score != null;
    }
    /**
     * 检查曲谱是否存在并属于某用户
     *
     * @param Type $var
     * @return void
     */
    public function checkIdOwnerMatch(int $scoreId, int $userId)
    {
        $model = new Model();
        $score = $model->get($scoreId);
        return $score && ($score['user_id'] == $userId);
    }

    public function addScore(
        string $title,
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
        return $model->insert([
            'title' => $title,
            'user_id' => \App\Domain\Auth::$currentUser['id'],
            'text' => $text,
            'alias' => $alias,
            'anime' => $anime,
            'key' => $key,
            'type' => $type,
            'description' => $description,
            'addition' => $addition,
            'image_id' => $image_id,
            'status' => 0,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
    }
    public function updateScore(
        int $id,
        string $title,
        string $text,
        string $alias,
        string $anime,
        string $key,
        int    $type,
        string $description,
        string $addition,
        int    $image_id
    ) {
        // 过滤 Addition 的换行符并简单过滤标签, 至于过滤真正的 XSS(比如我填个 " onfocus="evil() ) , 则是前端的事情
        $title = str_replace('\n', '', strip_tags($title));
        $addition = str_replace('\n', '', strip_tags($addition));

        $model = new Model();
        return $model->update($id, [
            'title' => $title,
            'text' => $text,
            'alias' => $alias,
            'anime' => $anime,
            'key' => $key,
            'type' => $type,
            'description' => $description,
            'addition' => $addition,
            'image_id' => $image_id,
            'status' => 0,
            'updated_at' => time(),
        ]);
    }
    public function removeScore($id)
    {
        $model = new Model();
        return $model->delete($id);
    }
    public function searchScoreByTitle($title, $page, $perpage)
    {
        $model = new Model();
        $items = $model->searchScoreByTitle($title, $page, $perpage);
        $total = $model->getScoreSearchCount($title);
        $return =  $this->prepareList($items, $total);
        $return['page'] = $page;
        $return['perpage'] =  $perpage;
        return $return;
    }
    private function prepareList($items, $total)
    {
        $uploadDomain = new \App\Domain\Upload();
        $userDomain = new \App\Domain\User();

        $rs = array('items' => array(), 'total' => 0);
        foreach ($items as &$item) {

            // 已经2019-5-19 01:36:10了, 肝不动了, 
            // 但是一听到废狱摇篮曲的旋律, 突然又有了写代码的动力
            $item['user'] = $userDomain->getUserSimpleInfo($item['user_id']);
            $item['user_id'] = null;


            $item['image_url'] =  $uploadDomain->getScoreImageUrl($item['id']);
            $item['image_id'] = null;

            $item = array_filter($item);
        }


        $rs['items'] = $items;
        $rs['total'] = $total;
        return $rs;
    }
    public function getScoreList($page, $perpage)
    {
        $model = new Model();
        $items = $model->getScoreListItems($page, $perpage);
        $total = $model->getScoreCount();
        return $this->prepareList($items, $total);
    }
    public function packUpScoreList($page, $perpage)
    {
        $rs = array();
        $list = $this->getScoreList($page, $perpage);

        $rs['items'] = $list['items'];
        $rs['total'] = $list['total'];
        $rs['page'] = $page;
        $rs['perpage'] =  $perpage;
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
            'title'         => $score['title'],
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
