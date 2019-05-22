<?php
namespace App\Api;

use PhalApi\Api;
use PhalApi\Exception\BadRequestException;

use \App\Domain\Comment as Domain;
use \App\Domain\Score as ScoreDomain;
use \App\Domain\Collection as CollectionDomain;

/**
 * 
 * 评论接口类
 * 
 * @author ZhangZijing <i@pluvet.com> 2019-5-19
 */
class Comment extends Api
{
    public function getRules()
    {
        // 如果有子评论, 则不能删除
        return [
            'addCommentOnComment' => [
                'comment_id' => ['name' => 'comment_id', 'require' => true, 'type' => 'int'],
                'text' => ['name' => 'text', 'require' => true, 'min' => 1, 'max' => 10000, 'type' => 'string'],
            ],
            'removeComment' => [
                'comment_id' => ['name' => 'comment_id', 'require' => true, 'type' => 'int'],
            ],

            'addCommentOnScore' => [
                'score_id' => ['name' => 'score_id', 'require' => true, 'type' => 'int'],
                'text' => ['name' => 'text', 'require' => true, 'min' => 1, 'max' => 10000, 'type' => 'string'],
            ],
            'getCommentsOnScore' => [
                'score_id' => ['name' => 'score_id', 'require' => true, 'type' => 'int'],

                'page' => array('name' => 'page',  'require' => true, 'type' => 'int', 'min' => 1, 'default' => 1, 'desc' => '第几页'),
                'perpage' => array('name' => 'perpage', 'require' => true, 'type' => 'int', 'min' => 1, 'max' => 20, 'default' => 10, 'desc' => '分页数量'),
            ],

            'addCommentOnCollection' => [
                'collection_id' => ['name' => 'collection_id', 'require' => true, 'type' => 'int'],
                'text' => ['name' => 'text', 'require' => true, 'min' => 1, 'max' => 10000, 'type' => 'string'],
            ],
            'getCommentsOnCollection' => [
                'collection_id' => ['name' => 'collection_id', 'require' => true, 'type' => 'int'],

                'page' => array('name' => 'page',  'require' => true, 'type' => 'int', 'min' => 1, 'default' => 1, 'desc' => '第几页'),
                'perpage' => array('name' => 'perpage', 'require' => true, 'type' => 'int', 'min' => 1, 'max' => 20, 'default' => 10, 'desc' => '分页数量'),
            ],
        ];
    }
    /**
     * 回复评论
     *
     * @return void
     */
    /**
     * @api {post} /comment/replay 回复评论
     * @apiDescription 回复评论.
     * @apiVersion 2.0.0
     * @apiName addCommentOnComment
     * @apiPermission user
     * @apiGroup Comment
     *
     * @apiParam {Integer} comment_id  要回复的评论的id.
     * @apiParam {String} text  回复内容.
     *
     * @apiSuccess {Integer} id 发表的评论的id.
     *
     * @apiSuccessExample 成功响应:
            {
                "ret": 200,
                "data": {
                    "id": 32
                },
                "msg": ""
            }
     */
    public function addCommentOnComment()
    {
        $domain = new Domain();
        // 检查评论是否存在
        if (!$domain->checkCommentExist($this->comment_id)) {
            throw new BadRequestException("评论不存在");
        }
        // 添加回复
        $id = $domain->addCommentOnComment($this->comment_id, $this->text);
        return [
            'id' => $id,
        ];
    }



    /**
     * 删除评论
     *
     * @return void
     */
    /**
     * @api {post} /comment/remove 删除评论
     * @apiDescription 删除评论(目前不许删除有子评论的评论).
     * @apiVersion 2.0.0
     * @apiName name
     * @apiPermission user
     * @apiGroup Comment
     *
     * @apiParam {Integer} comment_id  所要删除的评论的id.
     *
     * @apiSuccessExample 成功响应:
            {
                "ret": 200,
                "data": {},
                "msg": ""
            }
     */
    public function removeComment()
    {
        $domain = new Domain();
        // 检查要删的评论是否存在
        $commentToDelete = $domain->checkCommentExist($this->comment_id);
        if ($commentToDelete == false) {
            throw new BadRequestException("评论不存在");
        }
        // 检查要删的评论是不是该用户的
        if ($commentToDelete['author_id'] != \App\Domain\Auth::$currentUser['id']) {
            throw new BadRequestException("不是你的评论你不要删");
        }
        //判断是否有子评论(也可以不判断, 软删除, 但目前后面使用硬删除)
        if ($domain->checkChildrenCommentExist($this->comment_id)) {
            throw new BadRequestException("评论有子评论, 无法删除");
        }
        // 删除回复
        $domain->removeComment($this->comment_id);
        return [];
    }


    /**
     * 评论曲谱
     *
     * @return void
     */
    /**
     * @api {post} /comment/score/add 评论曲谱
     * @apiDescription 评论曲谱.
     * @apiVersion 2.0.0
     * @apiName addCommentOnScore
     * @apiPermission user
     * @apiGroup Comment
     *
     * @apiParam {Integer} score_id  要评论的曲谱的id.
     * @apiParam {String} text  回复内容.
     *
     * @apiSuccess {Integer} id 发表的评论的id.
     *
     * @apiSuccessExample 成功响应:
            {
                "ret": 200,
                "data": {
                    "id": 32
                },
                "msg": ""
            }
     */
    public function addCommentOnScore()
    {
        $domain = new Domain();
        $scoreDomain = new ScoreDomain();
        // 检查曲谱是否存在
        if (!$scoreDomain->checkScoreExist($this->score_id)) {
            throw new BadRequestException("曲谱不存在");
        }
        // 添加回复        
        $id = $domain->addCommentOnScore($this->score_id, $this->text);
        return [
            'id' => $id,
        ];
    }
    /**
     * 获取对曲谱的评论
     *
     * @return void
     */
    /**
     * @api {post} /comment/score/list 获取对曲谱的评论
     * @apiDescription 获取对曲谱的评论.
     * @apiVersion 2.0.0
     * @apiName name
     * @apiPermission user
     * @apiGroup Comment
     *
     * @apiParam {Integer} score_id  曲谱id.
     * @apiParam {Integer} [page]  页码.
     * @apiParam {Integer} [perpage]  每页数量.
     *
     * @apiSuccess {Object} .
     *
     * @apiSuccessExample 成功响应:
    {}
     * @todo 填坑
    json
     */
    public function getCommentsOnScore()
    {
        $domain = new Domain();
        $scoreDomain = new ScoreDomain();
        // 检查曲谱是否存在
        if (!$scoreDomain->checkScoreExist($this->score_id)) {
            throw new BadRequestException("曲谱不存在");
        }
        // 返回评论
        return $domain->packUpCommentsOnScore($this->score_id, $this->page, $this->perpage);
    }

    /**
     * 评论谱册
     *
     * @return void
     */
    /**
     * @api {post} /comment/collection/add 评论谱册
     * @apiDescription 评论谱册.
     * @apiVersion 2.0.0
     * @apiName addCommentOnCollection
     * @apiPermission user
     * @apiGroup Comment
     *
     * @apiParam {Integer} collection_id  要评论的谱册的id.
     * @apiParam {String} text  回复内容.
     *
     * @apiSuccess {Integer} id 发表的评论的id.
     *
     * @apiSuccessExample 成功响应:
            {
                "ret": 200,
                "data": {
                    "id": 32
                },
                "msg": ""
            }
     */
    public function addCommentOnCollection()
    {
        $domain = new Domain();
        $collectionDomain = new CollectionDomain();
        // 检查曲谱是否存在
        if (!$collectionDomain->checkCollectionExist($this->collection_id)) {
            throw new BadRequestException("曲谱不存在");
        }
        // 添加回复        
        $id = $domain->addCommentOnCollection($this->collection_id, $this->text);
        return [
            'id' => $id,
        ];
    }
    /**
     * 获取对曲谱的评论
     *
     * @return void
     */
    public function getCommentsOnCollection()
    {
        $domain = new Domain();
        $collectionDomain = new CollectionDomain();
        // 检查曲谱是否存在
        if (!$collectionDomain->checkScoreExist($this->collection_id)) {
            throw new BadRequestException("曲谱不存在");
        }
        // 返回评论
        return $domain->packUpCommentsOnCollection($this->collection_id, $this->page, $this->perpage);
    }
}
