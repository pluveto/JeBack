<?php
namespace App\Api;

use PhalApi\Api;
use PhalApi\Exception\BadRequestException;

use \App\Domain\Tag as Domain;
use \App\Domain\Score as ScoreDomain;
use \App\Domain\Collection as CollectionDomain;

/**
 * 
 * 标签接口类
 * 
 * @author ZhangZijing <i@pluvet.com> 2019-5-20
 */
class Comment extends Api
{
    public function getRules()
    {
        return [
            'addTag' => [
                'title' => ['name' => 'title', 'require' => true, 'min' => 1, 'max' => 255, 'type' => 'string'],
            ],
            'getTagList' => [
                'page' => array('name' => 'page',  'require' => true, 'type' => 'int', 'min' => 1, 'default' => 1, 'desc' => '第几页'),
                'perpage' => array('name' => 'perpage', 'require' => true, 'type' => 'int', 'min' => 1, 'max' => 20, 'default' => 10, 'desc' => '分页数量'),
            ],
            'searchTag' => [
                'keyword' => ['name' => 'title', 'require' => true, 'min' => 1, 'max' => 255, 'type' => 'string'],

                'page' => array('name' => 'page',  'require' => true, 'type' => 'int', 'min' => 1, 'default' => 1, 'desc' => '第几页'),
                'perpage' => array('name' => 'perpage', 'require' => true, 'type' => 'int', 'min' => 1, 'max' => 20, 'default' => 10, 'desc' => '分页数量'),
            ],
            'removeTag' => [
                'tag_id' => ['name' => 'score_id', 'require' => true, 'type' => 'int'],
            ],
            'addTagOnScore' => [
                'tag_id' => ['name' => 'score_id', 'require' => true, 'type' => 'int'],
                'score_id' => ['name' => 'score_id', 'require' => true, 'type' => 'int'],
            ],
            'getTagsOnScore' => [
                'score_id' => ['name' => 'score_id', 'require' => true, 'type' => 'int'],

                'page' => array('name' => 'page',  'require' => true, 'type' => 'int', 'min' => 1, 'default' => 1, 'desc' => '第几页'),
                'perpage' => array('name' => 'perpage', 'require' => true, 'type' => 'int', 'min' => 1, 'max' => 20, 'default' => 10, 'desc' => '分页数量'),
            ],
            'removeTagOnScore' => [
                'tag_id' => ['name' => 'score_id', 'require' => true, 'type' => 'int'],
                'score_id' => ['name' => 'score_id', 'require' => true, 'type' => 'int'],
            ],
            'addTagOnCollection' => [
                'tag_id' => ['name' => 'collection_id', 'require' => true, 'type' => 'int'],
                'collection_id' => ['name' => 'collection_id', 'require' => true, 'type' => 'int'],
            ],
            'getTagsOnCollection' => [
                'collection_id' => ['name' => 'collection_id', 'require' => true, 'type' => 'int'],

                'page' => array('name' => 'page',  'require' => true, 'type' => 'int', 'min' => 1, 'default' => 1, 'desc' => '第几页'),
                'perpage' => array('name' => 'perpage', 'require' => true, 'type' => 'int', 'min' => 1, 'max' => 20, 'default' => 10, 'desc' => '分页数量'),
            ],
            'removeTagOnCollection' => [
                'tag_id' => ['name' => 'collection_id', 'require' => true, 'type' => 'int'],
                'collection_id' => ['name' => 'collection_id', 'require' => true, 'type' => 'int'],
            ],
        ];
    }
    /**
     * 增加一个标签到标签库
     *
     * @return void
     */
    public function addTag()
    {
        $domain = new Domain();
        $this->title = trim($this->title);
        // 检查重复
        if ($domain->checkTagExistByTitle($this->title)) {
            throw new BadRequestException("标签重复");
        }
        $domain->addTag($this->title);
    }
    /**
     * 获取标签库的标签列表
     *
     * @return void
     */
    public function getTagList()
    {
        $domain = new Domain();
        return $domain->packUpTagList($this->page, $this->perpage);
    }
    /**
     * 搜索标签
     *
     * @return void
     */
    public function searchTag()
    {
        $domain = new Domain();
        return $domain->searchTagByTitle($this->keyword, $this->page, $this->perpage);
    }
    /**
     * 删除标签
     *
     * @return void
     */
    public function removeTag()
    {
        $domain = new Domain();
        // 检查标签下是否有谱册或曲谱
        if ($tags = $domain->getContentIdsUnderTag($this->tag_id) != null) {
            \PhalApi\DI()->response->setData(['contents_under_tag' => $tags]);
            throw new BadRequestException("标签被曲谱/谱册使用中, 无法删除, 具体见data部分");
        }
        // 正式删除
        $domain->removeTag($this->tag_id);
    }
    /**
     * 建立标签和曲谱的关联
     * 
     */

    public function addTagOnScore()
    {
        $domain = new Domain();
        $scoreDomain = new ScoreDomain();

        // 检查 tag 是否存在
        if (!$domain->checkTagExist($this->tag_id)) {
            throw new BadRequestException("标签不存在");
        }
        // 检查 score 是否存在
        if (!$scoreDomain->checkScoreExist($this->score_id)) {
            throw new BadRequestException("曲谱不存在");
        }
        // 检查是否已经添加过关联
        if (!$domain->checkRelationshipExist($this->tag_id, $this->score_id, 0)) {
            throw new BadRequestException("已经添加过了");
        }
        //正式添加关联
        $domain->addTagOnContent($this->tag_id, $this->score_id, 0);
    }
    /**
     * 获取曲谱的标签
     *
     * @return void
     */
    public function getTagsOnScore()
    {
        $domain = new Domain();
        $scoreDomain = new ScoreDomain();
        // 检查 score 是否存在
        if (!$scoreDomain->checkScoreExist($this->score_id)) {
            throw new BadRequestException("曲谱不存在");
        }
        return $domain->packUpTagsOnContent($this->score_id, $this->page, $this->perpage, 0);
    }

    /**
     * 注意只解除关系, 不删除标签实体
     *
     * @return void
     */
    public function removeTagsOnScore()
    {
        $domain = new Domain();
        $scoreDomain = new ScoreDomain();
        // 检查 collection 是否存在
        if (!$scoreDomain->checkScoreExist($this->score_id)) {
            throw new BadRequestException("谱册不存在");
        }
        return $domain->removeTagsOnContent($this->tag_id, $this->score_id, 0);
    }

    /**
     * 建立标签和谱册的关联
     * 
     */

    public function addTagOnCollection()
    {
        $domain = new Domain();
        $collectionDomain = new CollectionDomain();

        // 检查 tag 是否存在
        if (!$domain->checkTagExist($this->tag_id)) {
            throw new BadRequestException("标签不存在");
        }
        // 检查 collection 是否存在
        if (!$collectionDomain->checkCollectionExist($this->collection_id)) {
            throw new BadRequestException("谱册不存在");
        }
        // 检查是否已经添加过关联
        if (!$domain->checkRelationShipExist($this->tag_id, $this->collection_id, 1)) {
            throw new BadRequestException("已经添加过了");
        }
        //正式添加关联
        $domain->addTagOnContent($this->tag_id, $this->collection_id, 1);
    }
    /**
     * 获取谱册的标签
     *
     * @return void
     */
    public function getTagsOnCollection()
    {
        $domain = new Domain();
        $collectionDomain = new CollectionDomain();
        // 检查 collection 是否存在
        if (!$collectionDomain->checkCollectionExist($this->collection_id)) {
            throw new BadRequestException("谱册不存在");
        }
        return $domain->packUpTagsOnContent($this->collection_id, $this->page, $this->perpage, 1);
    }

    /**
     * 注意只解除关系, 不删除标签实体
     *
     * @return void
     */
    public function removeTagsOnCollection()
    {
        $domain = new Domain();
        $collectionDomain = new CollectionDomain();
        // 检查 collection 是否存在
        if (!$collectionDomain->checkCollectionExist($this->collection_id)) {
            throw new BadRequestException("谱册不存在");
        }
        return $domain->removeTagsOnContent($this->tag_id, $this->collection_id, 1);
    }
}
