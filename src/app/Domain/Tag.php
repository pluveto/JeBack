<?php
namespace App\Domain;

use App\Model\Tag as Model;
use App\Model\Score as ScoreModel;
use App\Model\Upload as UploadModel;

/**
 * Tag Domain 类
 * 
 * @author ZhangZijing <i@pluvet.com> 2019-5-20
 */
class Tag
{
    public function checkTagExistByTitle(string $title)
    {
        $model = new Model();
        return null != $model->getTagByTitle($title);
    }
    public function addTag($title)
    {
        $model = new Model();
        return intval($model->insert(['title' => $title]));
    }
    /**
     * 为客户端打包标签列表
     *
     * @param int $page
     * @param int $perpage
     * @return void
     */
    public function packUpTagList(int $page, int $perpage)
    {
        $model = new Model();
        $items = $model->getTagList($page, $perpage);
        $rs = [];
        $rs['items'] = $items;
        $rs['total'] = $model->getTagCount();
        $rs['page'] = $page;
        $rs['perpage'] =  $perpage;
        return $rs;
    }
    public function searchTagByTitle(string $keyword, int $page, int $perpage)
    {
        $model = new Model();
        $items = $model->searchTagByTitle($keyword, $page, $perpage);
        $total = $model->getTagSearchCount($keyword);
        $rs = [];
        $rs['items'] = $items;
        $rs['total'] = $total;
        $rs['page'] = $page;
        $rs['perpage'] =  $perpage;
        return $rs;
    }
    public function getContentIdsUnderTag($tagId, $type, $page, $perpage)
    {
        $model = new Model();
        $model->getContentUnderTag($tagId, $type, $page, $perpage);
    }
    public function removeTag($tagId)
    {
        $model = new Model();
        $model->delete($tagId);
    }
    public function checkTagExist($tagId)
    {
        $model = new Model();
        return null != $model->get($tagId);
    }
    /**
     * 检查某内容是否有某标签
     *
     * @param int $tagId
     * @param int $contentId
     * @param int $type 0 为曲谱，1 为谱册
     * @return void
     */
    public function checkRelationshipExist($tagId, $contentId, $type)
    {
        $model = new Model();
        return null != $model->getRelationShipItem($tagId, $contentId, $type);
    }
    public function addTagOnContent($tagId, $contentId, $type)
    {
        $model = new Model();
        $model->addTagOnContent($tagId, $contentId, $type, \App\Domain\Auth::$currentUser['id']);
    }
    public function packUpTagsOnContent($contentId, $page, $perpage, $type)
    {
        $model = new Model();
        $items = $model->getTagsOnContent($contentId, $page, $perpage, $type);
        $total = $model->getTagsOnContentCount($contentId, $type);
        $rs = [];
        $rs['items'] = $items;
        $rs['total'] = $total;
        $rs['page'] = $page;
        $rs['perpage'] =  $perpage;
        return $rs;
    }
    public function removeTagsOnContent($tagId, $contentId, $type)
    {
        $model = new Model();
        $model->removeTagsOnContent($tagId, $contentId, $type);
    }
}
