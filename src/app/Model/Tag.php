<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;
use App\Model\Score as ScoreModel;
use App\Model\Collection as CollectionModel;

/**
 * Tag Model
 * @author ZhangZijing <i@pluvet.com> 2019-5-20
 */




class Tag extends NotORM
{
    const RELATIONSHIP_TAG_TABLE = "relationship_tag";
    public function getTagByTitle($title)
    {
        return $this->getORM()->where('title', $title)->fetchOne();
    }
    public function getTagList($page, $perpage)
    {
        return $this->getORM()
            ->limit(($page - 1) * $perpage, $perpage)
            ->fetchAll();
    }
    public function getTagCount()
    {
        return intval($this->getORM()->count('id'));
    }
    public function searchTagByTitle(string $keyword, int $page, int $perpage)
    {
        return $this->getORM()
            ->where('title LIKE ?', '%' . $keyword . '%')
            ->order('created_at DESC')
            ->limit(($page - 1) * $perpage, $perpage)
            ->fetchAll();
    }
    public function getTagSearchCount(string $keyword)
    {

        return intval($this->getORM()
            ->where('title LIKE ?', '%' . $keyword . '%')
            ->count('id'));
    }
    public function getContentUnderTag(int $tagId, int $type, int $page, int $perpage)
    {
        $table = self::RELATIONSHIP_TAG_TABLE;
        $orm = \PhalApi\DI()->notorm->$table;
        return $orm->where('tag_id', $tagId)
            ->where('type', $type)
            ->limit(($page - 1) * $perpage, $perpage)
            ->fetchAll();
    }
    public function getContentUnderTagCount(int $tagId, int $type)
    {
        $table = self::RELATIONSHIP_TAG_TABLE;
        $orm = \PhalApi\DI()->notorm->$table;
        return intval($orm->where('tag_id', $tagId)
            ->where('type', $type)
            ->count('id'));
    }

    public function getRelationShipItem(int $tagId, int $contentId, int $type)
    {
        $table = self::RELATIONSHIP_TAG_TABLE;
        $orm = \PhalApi\DI()->notorm->$table;
        return $orm->where('tag_id', $tagId)
            ->where('type', $type)
            ->where('content_id', $contentId)
            ->fetchOne();
    }
    public function addTagOnContent(int $tagId, int $contentId, int $type, int $operator)
    {
        $table = self::RELATIONSHIP_TAG_TABLE;
        $orm = \PhalApi\DI()->notorm->$table;
        return intval($orm->insert([
            'tag_id' => $tagId,
            'content_id' => $contentId,
            'type' => $type,
            'operator' => $operator,
        ]));
    }
    public function getTagsOnContent(int $contentId, int $page, int $perpage, int $type)
    {
        $table = self::RELATIONSHIP_TAG_TABLE;
        $orm = \PhalApi\DI()->notorm->$table;
        return $orm->where('type', $type)
            ->where('content_id', $contentId)
            ->limit(($page - 1) * $perpage, $perpage)

            ->fetchOne();
    }
    public function getTagsOnContentCount(int $contentId, int $type)
    {
        $table = self::RELATIONSHIP_TAG_TABLE;
        $orm = \PhalApi\DI()->notorm->$table;
        return intval($orm->where('type', $type)
            ->where('content_id', $contentId)

            ->count('id'));
    }
    public function removeTagsOnContent(int $tagId, int $contentId, int $type)
    {
        $table = self::RELATIONSHIP_TAG_TABLE;
        $orm = \PhalApi\DI()->notorm->$table;
        return $orm
            ->where('tag_id', $tagId)
            ->where('type', $type)
            ->where('content_id', $contentId)
            ->delete();
    }
}
