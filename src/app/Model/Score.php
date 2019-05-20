<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

/**
 * 
 * @author ZhangZijing <i@pluvet.com> 2019-5-17
 */




class Score extends NotORM
{
    public function getScoreListItems($page, $perpage)
    {
        return $this->getORM()
            ->select('`id`,`title`,`created_at`,`updated_at`,`anime`,`key`,`addition`,`user_id`,`username`,`image_id`')
            ->where('status', '0') //未隐藏
            ->order('`created_at` DESC')
            ->limit(($page - 1) * $perpage, $perpage)
            ->fetchAll();
    }
    public function getScoreCount()
    {
        $total = $this->getORM()
            ->count('id');

        return intval($total);
    }
    public function searchScoreByTitle($title, $page, $perpage)
    {
        return $this->getORM()
            ->where('title LIKE ?', '%' . $title . '%')
            ->order('created_at DESC')
            ->limit(($page - 1) * $perpage, $perpage)
            ->fetchAll();
    }
    public function getScoreSearchCount($title)
    {
        $total = $this->getORM()
            ->where('title LIKE ?', '%' . $title . '%')
            ->count('id');

        return intval($total);
    }
}
