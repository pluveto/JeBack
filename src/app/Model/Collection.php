<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

/**
 * 谱册模型类
 * @author LW <lw1020573989@live.com>
 */

class Collection extends NotORM
{
    private const COLLECTION_TABLE = 'collection';
    private const RELATIONSHIP = 'collection_score';

    /**
     * 创建一个空谱册
     * @param array $data
     */
    public function createCollection($data)
    {
        $tabel = self::COLLECTION_TABLE;
        $ormCollection = \PhalApi\DI()->notorm->$tabel;
        $ormCollection->insert($data);
    }


    /**
     * 移出谱册,改变状态
     * @param id 
     */
    public function removeCollection($id)
    {
        $tabel = self::COLLECTION_TABLE;
        $ormCollection = \PhalApi\DI()->notorm->$tabel;
        $ormCollection->where('id', $id)->update('status', 1);
    }

    /**
     * 谱子添加到谱册
     * @param array $data
     */
    public function addScoreToCollection($data)
    {
        $tabel = self::RELATIONSHIP;
        $ormCollection = \PhalApi\DI()->notorm->$tabel;
        $ormCollection->insert($data);
    }

    /**
     * 获取一个谱册
     * @param id
     */
    public function findCollection($id)
    {
        $tabel = self::COLLECTION_TABLE;
        $ormCollection = \PhalApi\DI()->notorm->$tabel;
        return $ormCollection->where('id', $id)->fetchOne();
    }
}
