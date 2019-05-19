<?php
namespace App\Domain;

use App\Model\Comment as Model;
use App\Model\Score as ScoreModel;
use App\Model\User as UserModel;
use App\Model\Collection as CollectionModel;

/**
 * 评论 Domain 类
 * 
 * @author ZhangZijing <i@pluvet.com> 2019-5-18
 */
class Comment
{
    /**
     * 检查评论存在性
     *
     * @param integer $commentId
     * @return bool 存在返回其评论, 不存在返回false
     */
    public function checkCommentExist(int $commentId)
    {
        $model = new Model();
        $ret = $model->get($commentId);
        return $ret == null ? false : $ret;
    }
    /**
     * 回复评论
     *
     * @param integer $commentId
     * @param string $text
     * @return int 插入项id
     */
    public function addCommentOnComment(int $commentId, string $text)
    {
        $model = new Model();
        $userModel = new UserModel();
        $parent = $model->get($commentId);

        return intval($model->insert([
            'content_id' => $parent['content_id'],
            'created_at' => time(),
            'author_id' => \App\Domain\Auth::$currentUser['id'],
            'author_username' => $userModel->get($parent['author_id'], 'username')['username'],
            'owner_id' => $parent['author_id'],
            'owner_username' => $userModel->get($parent['author_id'], 'username')['username'],
            'text' => $text,
            'status' => 0,
            'parent_id' => $parent['id'],
            'type' => $parent['type'],
        ]));
    }

    public function addCommentOnScore(int $scoreId, string $text)
    {
        $model = new Model();
        $scoreModel = new ScoreModel();
        $score = $scoreModel->get($scoreId);
        return intval($model->insert([
            'content_id' => $scoreId,
            'created_at' => time(),
            'author_id' => \App\Domain\Auth::$currentUser['id'],
            'owner_id' => $score['user_id'],
            'text' => $text,
            'status' => 0,
            'parent_id' => 0,
            'type' => 0,
        ]));
    }

    public function addCommentOnCollection(int $collectionId, string $text)
    {
        $model = new Model();
        $collectionModel = new CollectionModel();
        $collection = $collectionModel->get($collectionId);
        return intval($model->insert([
            'content_id' => $collectionId,
            'created_at' => time(),
            'author_id' => \App\Domain\Auth::$currentUser['id'],
            'owner_id' => $collection['user_id'],
            'text' => $text,
            'status' => 0,
            'parent_id' => 0,
            'type' => 1,
        ]));
    }
    /**
     * 检查子评论存在性
     *
     * @param integer $commentId
     * @return bool 存在返回 true
     */
    public function checkChildrenCommentExist(int $commentId)
    {
        $model = new Model();
        return $model->getChildrenComment($commentId) != null;
    }

    /**
     * 不递归移除曲谱评论(**硬删除**)
     *
     * @param integer $commentId
     * @return void
     */
    public function removeComment(int $commentId)
    {
        $model = new Model();
        $model->delete($commentId);
    }
    /**
     * 打包曲谱评论
     * 
     * @param integer $scoreId
     * @param integer $page
     * @param integer $perpage
     * @return array
     */
    public function packUpCommentsOnScore(int $scoreId, int  $page, int $perpage)
    {
        $model = new Model();
        // 获取最新评论
        $items = $model->getScoreCommentList($scoreId, $page, $perpage);
        // 获取每条评论的父评论(网易云实现模式)
        foreach ($items as &$item) {
            $parentId = $item['parent_id'];
            //  评论父ID = 0 表示这是根评论
            if ($parentId == 0) {
                continue;
            }
            $item['parent'] = $model->get($parentId);
        }
        $rs = [];
        $rs['items'] = $items;
        $rs['total'] = $model->getScoreCommentCount($scoreId);
        $rs['page'] = $page;
        $rs['perpage'] =  $perpage;
        return $rs;
    }

    public function packUpCommentsOnCollection(int $collectionId, int  $page, int $perpage)
    {
        $model = new Model();
        // 获取最新评论
        $items = $model->getCollectionCommentList($collectionId, $page, $perpage);
        // 获取每条评论的父评论(网易云实现模式)
        foreach ($items as &$item) {
            $parentId = $item['parent_id'];
            //  评论父ID = 0 表示这是根评论
            if ($parentId == 0) {
                continue;
            }
            $item['parent'] = $model->get($parentId);
        }
        $rs = [];
        $rs['items'] = $items;
        $rs['total'] = $model->getCollectionCommentCount($collectionId);
        $rs['page'] = $page;
        $rs['perpage'] =  $perpage;
        return $rs;
    }
}
