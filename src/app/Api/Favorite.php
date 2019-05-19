<?php
namespace App\Api;

use PhalApi\Api;
use PhalApi\Exception\BadRequestException;

use \App\Domain\Favorite as Domain;
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
            'addFavoriteScore' =>[
                'score_id' => ['name' => 'score_id', 'require' => true, 'type' => 'int'],
            ],
            'removeFavoriteScore' => [
                'score_id' => ['name' => 'score_id', 'require' => true, 'type' => 'int'],
            ],
            'addFavoriteCollection' => [
                'collection_id' => ['name' => 'collection_id', 'require' => true, 'type' => 'int'],
            ],
            'removeFavoriteCollection' => [
                'collection_id' => ['name' => 'collection_id', 'require' => true, 'type' => 'int'],
            ],
        ];
    }

    public function addFavoriteScore()
    {
        # code...
    }

    public function removeFavoriteScore()
    {
        # code...
    }

    public function addFavoriteCollection()
    {
        # code...
    }

    public function removeFavoriteCollection()
    {
        # code...
    }    
}