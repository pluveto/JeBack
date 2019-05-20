<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

/**
 * Favorite Model Class
 * 
 * @author ZhangZijing <i@pluvet.com> 2019-5-19
 */




class Favorite extends NotORM
{
    public function getByUserAndScore($userId, $scoreId)
    {
        return $this->getORM()
            ->where('user_id', $userId)
            ->where('content_id', $scoreId)
            ->where('type', '0')->fetchOne();
    }
    public function getByUserAndCollection($userId, $colletionId)
    {
        return $this->getORM()
            ->where('user_id', $userId)
            ->where('content_id', $colletionId)
            ->where('type', '1')->fetchOne();
    }
    public function getScoreFavoriteList($userId, $page, $perpage)
    {
        return $this->getORM()
            ->where("type", '0') // 类型
            ->where("user_id", $userId)
            ->order('`created_at` DESC')
            ->limit(($page - 1) * $perpage, $perpage)
            ->fetchAll();
    }
    public function getScoreFavoriteCount($userId)
    {
        return intval($this->getORM()->where("type", '0')->where("user_id", $userId)->count('id'));
    }

    public function getCollectionFavoriteList($userId, $page, $perpage)
    {
        return $this->getORM()
            ->where("type", '1') // 类型
            ->where("user_id", $userId)
            ->order('`created_at` DESC')
            ->limit(($page - 1) * $perpage, $perpage)
            ->fetchAll();
    }
    public function getCollectionFavoriteCount($userId)
    {
        return intval($this->getORM()->where("type", '1')->where("user_id", $userId)->count('id'));
    }
}
