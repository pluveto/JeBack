<?php
namespace App\Api;

use PhalApi\Api;
use PhalApi\Exception\BadRequestException;


use \App\Domain\Favorite as Domain;
use \App\Domain\User as UserDomain;
use \App\Domain\Score as ScoreDomain;
use \App\Domain\Collection as CollectionDomain;

/**
 * 
 * 收藏接口类
 * 
 * @author ZhangZijing <i@pluvet.com> 2019-5-19
 */
class Favorite extends Api
{
    public function getRules()
    {
        return [
            'addFavoriteScore' => [
                'score_id' => ['name' => 'score_id', 'require' => true, 'type' => 'int'],
            ],
            // 白名单 API listFavoriteScore
            'listFavoriteScore' => [
                'user_id' => ['name' => 'user_id', 'require' => true, 'type' => 'int'],
                'page' => array('name' => 'page',  'require' => true, 'type' => 'int', 'min' => 1, 'default' => 1, 'desc' => '第几页'),
                'perpage' => array('name' => 'perpage', 'require' => true, 'type' => 'int', 'min' => 1, 'max' => 20, 'default' => 10, 'desc' => '分页数量'),
            ],
            'removeFavoriteScore' => [
                'score_id' => ['name' => 'score_id', 'require' => true, 'type' => 'int'],
            ],
            'addFavoriteCollection' => [
                'collection_id' => ['name' => 'collection_id', 'require' => true, 'type' => 'int'],
            ],
            // 白名单 API listFavoriteCollection
            'listFavoriteCollection' => [
                'user_id' => ['name' => 'user_id', 'require' => true, 'type' => 'int'],
                'page' => array('name' => 'page',  'require' => true, 'type' => 'int', 'min' => 1, 'default' => 1, 'desc' => '第几页'),
                'perpage' => array('name' => 'perpage', 'require' => true, 'type' => 'int', 'min' => 1, 'max' => 20, 'default' => 10, 'desc' => '分页数量'),
            ],
            'removeFavoriteCollection' => [
                'collection_id' => ['name' => 'collection_id', 'require' => true, 'type' => 'int'],
            ]
        ];
    }

    /**
     * @api {post} /favorite/score/add 收藏曲谱
     * @apiDescription 收藏曲谱.
     * @apiVersion 2.0.0
     * @apiName addFavoriteScore
     * @apiPermission user
     * @apiGroup Favorite
     *
     * @apiParam {Integer} score_id  曲谱id.
     *
     * @apiSuccess {Integer} id 该收藏项的id.
     *
     * @apiSuccessExample 成功响应:
            {
                "ret": 200,
                "data": {
                    "id": 52
                },
                "msg": ""
            }
     */
    public function addFavoriteScore()
    {
        $domain = new Domain();
        $scoreDomain = new ScoreDomain();
        // 判断是否存在
        if (!$scoreDomain->checkScoreExist($this->score_id)) {
            throw new BadRequestException("曲谱不存在");
        }
        // 判断是否重复收藏
        if ($domain->checkScoreHasAdded($this->score_id)) {
            throw new BadRequestException("曲谱已经收藏过了");
        }
        // 增加收藏
        return ['id' => $domain->addFavoriteScore($this->score_id)];
    }
    /**
     * @api {post} /favorite/score/list 获取用户收藏的曲谱
     * @apiDescription 列出用户收藏的曲谱.
     * @apiVersion 2.0.0
     * @apiName listFavoriteScore
     * @apiPermission none
     * @apiGroup Favorite
     *
     * @apiParam {Integer} user_id  用户id.
     * @apiParam {Integer} [page]  页码.
     * @apiParam {Integer} [perpage]  每页数量.
     *
     * @apiSuccess {Array} items 列表数据项.
     * @apiSuccess {Integer} total 列表数据项.
     * @apiSuccess {Integer} page 页码.
     * @apiSuccess {Integer} perpage 每页数量.
     * @apiSuccessExample 成功响应:
            {
                "ret": 200,
                "data": {
                    "items": [{
                        "id": "149",
                        "title": "Test",
                        "created_at": "1558500661",
                        "updated_at": "1558500661",
                        "anime": "单曲",
                        "key": "C",
                        "user_id": "1",
                        "username": "pluvet"
                    }, {
                        "id": "150",
                        "title": "Updated Test With Detail",
                        "created_at": "1558500661",
                        "updated_at": "1558500662",
                        "anime": "Updated Anime Name",
                        "key": "D",
                        "addition": "Updated Some thing to append on title",
                        "user_id": "1",
                        "username": "pluvet"
                    }, {
                        "id": "147",
                        "title": "Test",
                        "created_at": "1558500602",
                        "updated_at": "1558500602",
                        "anime": "单曲",
                        "key": "C",
                        "user_id": "1",
                        "username": "pluvet"
                    }, {
                        "id": "146",
                        "title": "Test",
                        "created_at": "1558500560",
                        "updated_at": "1558500560",
                        "anime": "单曲",
                        "key": "C",
                        "user_id": "1",
                        "username": "pluvet"
                    }, {
                        "id": "145",
                        "title": "Test",
                        "created_at": "1558500551",
                        "updated_at": "1558500551",
                        "anime": "单曲",
                        "key": "C",
                        "user_id": "1",
                        "username": "pluvet"
                    }, {
                        "id": "144",
                        "title": "Test",
                        "created_at": "1558500478",
                        "updated_at": "1558500478",
                        "anime": "单曲",
                        "key": "C",
                        "user_id": "1",
                        "username": "pluvet"
                    }, {
                        "id": "143",
                        "title": "Test",
                        "created_at": "1558500430",
                        "updated_at": "1558500430",
                        "anime": "单曲",
                        "key": "C",
                        "user_id": "1",
                        "username": "pluvet"
                    }, {
                        "id": "141",
                        "title": "Test",
                        "created_at": "1558500313",
                        "updated_at": "1558500313",
                        "anime": "单曲",
                        "key": "C",
                        "user_id": "1",
                        "username": "pluvet"
                    }],
                    "total": 8,
                    "page": 1,
                    "perpage": 10
                },
                "msg": ""
            }
     */
    public function listFavoriteScore()
    {
        $domain = new Domain();
        $userDomain = new UserDomain();
        // 判断用户是否存在
        if (!$userDomain->checkUserExist($this->user_id)) {
            throw new BadRequestException("该用户不存在");
        }
        // 列出该用户的曲谱收藏
        return $domain->packUpUserScoreFavorite($this->user_id, $this->page, $this->perpage);
    }
    /**
     * @api {post} /favorite/score/remove 移除对曲谱的收藏
     * @apiDescription 移除对曲谱的收藏.
     * @apiVersion 2.0.0
     * @apiName removeFavoriteScore
     * @apiPermission user
     * @apiGroup Favorite
     *
     * @apiParam {Integer} score_id  曲谱id.
     *
     * @apiSuccessExample 成功响应:
            {
                "ret": 200,
                "data": {},
                "msg": ""
            }
     */
    public function removeFavoriteScore()
    {
        $domain = new Domain();
        // 判断是否在收藏夹内
        if (!$domain->checkScoreHasAdded($this->score_id)) {
            throw new BadRequestException("你没有收藏过这个曲谱");
        }
        // 取消收藏
        $domain->removeFavoriteScore($this->score_id);
        return [];
    }
    /**
     * @api {post} /favorite/collection/add 收藏谱册
     * @apiDescription 收藏谱册.
     * @apiVersion 2.0.0
     * @apiName addFavoriteCollection
     * @apiPermission user
     * @apiGroup Favorite
     *
     * @apiParam {Integer} collection_id  谱册id.
     *
     * @apiSuccess {Integer} id 该收藏项的id.
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
    public function addFavoriteCollection()
    {
        $domain = new Domain();
        $collectionDomain = new CollectionDomain();
        // 判断是否存在
        if (!$collectionDomain->checkCollectionExist($this->collection_id)) {
            throw new BadRequestException("谱册不存在");
        }
        // 判断是否重复收藏
        if ($domain->checkCollectionHasAdded($this->collection_id)) {
            throw new BadRequestException("谱册已经收藏过了");
        }
        // 增加收藏
        return ['id' => $domain->addFavoriteCollection($this->collection_id)];
    }
    /**
     * @api {post} /favorite/collection/list 获取用户收藏的谱册
     * @apiDescription 列出用户收藏的谱册.
     * @apiVersion 2.0.0
     * @apiName listFavoriteCollection
     * @apiPermission none
     * @apiGroup Favorite
     *
     * @apiParam {Integer} user_id  用户id.
     * @apiParam {Integer} [page]  页码.
     * @apiParam {Integer} [perpage]  每页数量.
     *
     * @apiSuccess {Array} items 列表数据项.
     * @apiSuccess {Integer} total 列表数据项.
     * @apiSuccess {Integer} page 页码.
     * @apiSuccess {Integer} perpage 每页数量.
     * @apiSuccessExample 成功响应:
     {}
     * @todo lw 填坑
     */
    public function listFavoriteCollection()
    {
        $domain = new Domain();
        $userDomain = new UserDomain();
        // 判断用户是否存在
        if (!$userDomain->checkUserExist($this->user_id)) {
            throw new BadRequestException("该用户不存在");
        }
        // 列出该用户的曲谱收藏
        return $domain->packUpUserCollectionFavorite($this->user_id, $this->page, $this->perpage);
    }
    /**
     * @api {post} /favorite/collection/remove 移除对谱册的收藏
     * @apiDescription 移除对谱册的收藏.
     * @apiVersion 2.0.0
     * @apiName removeFavoriteCollection
     * @apiPermission user
     * @apiGroup Favorite
     *
     * @apiParam {Integer} collection_id  曲谱id.
     *
     * @apiSuccessExample 成功响应:
            {
                "ret": 200,
                "data": {},
                "msg": ""
            }
     */
    public function removeFavoriteCollection()
    {
        $domain = new Domain();
        // 判断是否在收藏夹内
        if (!$domain->checkCollectionHasAdded($this->collection_id)) {
            throw new BadRequestException("你没有收藏过这个谱册");
        }
        // 取消收藏
        $domain->removeFavoriteCollection($this->collection_id);
        return [];
    }
}
