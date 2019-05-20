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
    public function checkTagExistByTitle($title)
    {
        $model = new Model();
        return null != $model->getTagByTitle($title);
    }
    public function addTag($title)
    {
        $model = new Model();
        return intval($model->insert(['title' => $title]));
    }
    public function packUpTagList($page, $perpage)
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
    public function searchTagByTitle($keyword, $page, $perpage)
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
    public function getContentIdsUnderTag($tagId)
    {
        $model = new Model();
        $model->getContentUnderTag($tagId, 'id');
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
    public function checkRelationshipExist($tagId, $contentId, $type)
    {
        $model = new Model();
        return null != $model->getRelationShipItem($tagId, $contentId, $type);
    }
    public function addTagOnContent($tagId, $contentId, $type)
    {
        $model = new Model();
        $model->addTagOnContent($tagId, $contentId, $type);
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
    public function removeTagOnContent($tagId, $contentId, $type)
    {
        $model = new Model();
        $model->removeTagOnContent($tagId, $contentId, $type);
    }
}
