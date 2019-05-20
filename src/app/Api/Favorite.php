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
            // 白名单 API listFavoriteColletion
            'listFavoriteColletion' => [
                'user_id' => ['name' => 'user_id', 'require' => true, 'type' => 'int'],
                'page' => array('name' => 'page',  'require' => true, 'type' => 'int', 'min' => 1, 'default' => 1, 'desc' => '第几页'),
                'perpage' => array('name' => 'perpage', 'require' => true, 'type' => 'int', 'min' => 1, 'max' => 20, 'default' => 10, 'desc' => '分页数量'),
            ],
            'removeFavoriteCollection' => [
                'collection_id' => ['name' => 'collection_id', 'require' => true, 'type' => 'int'],
            ]
        ];
    }


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

    public function addFavoriteCollection()
    {
        $domain = new Domain();
        $collectionDomain = new CollectionDomain();
        // 判断是否存在
        if (!$collectionDomain->checkCollectionExist($this->colletion_id)) {
            throw new BadRequestException("谱册不存在");
        }
        // 判断是否重复收藏
        if ($domain->checkCollectionHasAdded($this->colletion_id)) {
            throw new BadRequestException("谱册已经收藏过了");
        }
        // 增加收藏
        return ['id' => $domain->addFavoriteCollection($this->colletion_id)];
    }
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
    public function removeFavoriteCollection()
    {
        $domain = new Domain();
        // 判断是否在收藏夹内
        if (!$domain->checkCollectionHasAdded($this->colletion_id)) {
            throw new BadRequestException("你没有收藏过这个谱册");
        }
        // 取消收藏
        $domain->removeFavoriteCollection($this->colletion_id);
        return [];
    }
}
