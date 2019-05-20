<?php
namespace App\Domain;

use App\Model\Favorite as Model;
use App\Model\Score as ScoreModel;
use App\Model\Upload as UploadModel;
use App\Model\User as UserModel;
use App\Model\Collection as CollectionModel;

/**
 * 收藏 Domain 类
 * 
 * @author ZhangZijing <i@pluvet.com> 2019-5-19
 */
class Favorite
{
    public function packUpUserScoreFavorite($userId, $page, $perpage)
    {
        $model = new Model();
        $items = $model->getScoreFavoriteList($userId, $page, $perpage);
        $rs = [];
        $rs['items'] = $items;
        $rs['total'] = $model->getScoreFavoriteCount($userId);
        $rs['page'] = $page;
        $rs['perpage'] =  $perpage;
        return $rs;
    }
    public function checkScoreHasAdded($scoreId)
    {
        $model = new Model();
        return $model->getByUserAndScore($scoreId, \App\Domain\Auth::$currentUser['id']) != null;
    }
    public function addFavoriteScore($scoreId)
    {
        $model = new Model();
        $scoreModel = new ScoreModel();
        $uploadModel = new UploadModel();
        $score = $scoreModel->get($scoreId);
        return intval($model->insert(
            [
                'content_id' => $scoreId,
                'user_id' => \App\Domain\Auth::$currentUser['id'],
                'created_at' => time(),
                'type' => 0,
                'title' => $score['title'],
                'image_path' => $uploadModel->get($score['image_id'])
            ]
        ));
    }
    public function removeFavoriteScore($scoreId)
    {
        $model = new Model();
        $userId = \App\Domain\Auth::$currentUser['id'];
        $model->removeFavoriteScore($scoreId, $userId);
    }
    public function packUpUserCollectionFavorite($userId, $page, $perpage)
    {
        $model = new Model();
        $items = $model->getCollectionFavoriteList($userId, $page, $perpage);
        $rs = [];
        $rs['items'] = $items;
        $rs['total'] = $model->getCollectionFavoriteCount($userId);
        $rs['page'] = $page;
        $rs['perpage'] =  $perpage;
        return $rs;
    }
    public function checkCollectionHasAdded($collectionId)
    {
        $model = new Model();
        return $model->getByUserAndCollection($collectionId, \App\Domain\Auth::$currentUser['id']) != null;
    }
    public function addFavoriteCollection($collectionId)
    {
        $model = new Model();
        $collectionModel = new CollectionModel();
        $uploadModel = new UploadModel();
        $collection = $collectionModel->get($collectionId);
        return intval($model->insert(
            [
                'content_id' => $collectionId,
                'user_id' => \App\Domain\Auth::$currentUser['id'],
                'created_at' => time(),
                'type' => 1,
                'title' => $collection['title'],
                'image_path' => $uploadModel->get($collection['image_id'])
            ]
        ));
    }
    public function removeFavoriteCollection($collectionId)
    {
        $model = new Model();
        $userId = \App\Domain\Auth::$currentUser['id'];
        $model->removeFavoriteCollection($collectionId, $userId);
    }
}
