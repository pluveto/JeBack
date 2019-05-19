<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

/**
 * Comment Class
 * 
 * @author ZhangZijing <i@pluvet.com> 2019-5-19
 */




class Comment extends NotORM
{
    /**
     * 获取所有评论(不推荐)
     *
     * @param integer $scoreId
     * @return void
     */
    public function getScoreAllComment(int $scoreId)
    {
        return $this->getORM()->where("type", '0')->where("content_id", $scoreId)->fetchAll();
    }
    /**
     * 获取曲谱下的直接评论
     *
     * @param integer $scoreId
     * @return void
     */
    public function getScoreRootComment(int $scoreId, $page, $perpage)
    {
        return $this->getORM()
            ->where("type", '0') // 曲谱类型
            ->where("parent_id", '0') // 曲谱类型
            ->where("content_id", $scoreId)
            ->where('status', '0') //未隐藏
            ->order('`created_at` DESC')
            ->limit(($page - 1) * $perpage, $perpage)
            ->fetchAll();
    }
    /**
     * 时间倒序获取评论列表, 不区分是父节点还是子节点
     *
     * @param integer $scoreId
     * @return void
     */
    public function getScoreCommentList(int $scoreId, $page, $perpage)
    {
        return $this->getORM()
            ->where("type", '0') // 类型
            ->where("content_id", $scoreId)
            ->where('status', '0') //未隐藏
            ->order('`created_at` DESC')
            ->limit(($page - 1) * $perpage, $perpage)
            ->fetchAll();
    }
    public function getCollectionCommentList(int $scoreId, $page, $perpage)
    {
        return $this->getORM()
            ->where("type", '1') // 类型
            ->where("content_id", $scoreId)
            ->where('status', '0') //未隐藏
            ->order('`created_at` DESC')
            ->limit(($page - 1) * $perpage, $perpage)
            ->fetchAll();
    }
    public function getScoreCommentCount(int $scoreId)
    {
        return intval($this->getORM()->where("type", '0')->where("content_id", $scoreId)->where('status', '0')->count('id'));
    }

    public function getCollectionCommentCount(int $scoreId)
    {
        return intval($this->getORM()->where("type", '1')->where("content_id", $scoreId)->where('status', '0')->count('id'));
    }
    public function getChildrenComment(int $commentId)
    {
        return $this->getORM()->where("parent_id", $commentId)->fetchAll();
    }
    public function getChildrenCommentCount(int $commentId)
    {
        $total = $this->getORM()->where("parent_id", $commentId)->count('id');
        return intval($total);
    }
}
