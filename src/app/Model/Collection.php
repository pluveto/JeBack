<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

/**
 * 谱册模型类
 * @author LW <lw1020573989@live.com>
 */

class Collection extends NotORM
{
    private const RELATIONSHIP = 'collection_score';
    /**
     * 创建一个空谱册
     * @param array $data
     * @return array 返回新增的谱册id
     */
    public function createCollection($data)
    {
        return $this->getORM()->insert($data);
    }


    /**
     * 改变谱册状态状态
     * @param array data 
     */
    public function changeCollectionStatus($data)
    {
        $this->getORM()->where('id', $data['id'])->update($data);
    }

    /**
     * 一个谱子添加到谱册
     * @param array $data
     */
    public function addScoreToCollection($data)
    {
        $table = self::RELATIONSHIP;
        $ormCollection = \PhalApi\DI()->notorm->$table;
        return $ormCollection->insert($data);
    }


    /**
     * 一些谱子添加到谱册
     * @param array $data
     */
    public function addScoresToCollection($data)
    {
        $table = self::RELATIONSHIP;
        $ormCollection = \PhalApi\DI()->notorm->$table;
        return $ormCollection->insert_multi($data);
    }

    /**
     * 获取一个谱册(pluvet 注: 这个被我删了, 因为父类已经有get(id)的方法, 所以没必要重复实现)
     * @param id
     * @return 返回指定谱册
     */


    /**
     * 获取一些谱册 
     * @param int  $page 当前页码
     * @param int  $pageSize 每页显示
     * @param int  $status 谱册状态
     * @return array 返回谱册列表
     */
    public function getList($page, $pageSize, $status)
    {
        return $this->getORM()
            ->select('*')
            ->where("status", $status)
            ->order('updated_at DESC')
            ->limit(($page - 1) * $pageSize, $pageSize)
            ->fetchAll();
    }


    /**
     * 通过谱册名字搜索谱册
     * @param string 谱册名字
     * @return 返回相关谱册
     */
    public function searchByName($title, $page, $pageSize)
    {
        return $this->getORM()
            ->select('*')
            ->where('title LIKE ?', '%' . $title . '%')
            ->order('updated_at DESC')
            ->limit(($page - 1) * $pageSize, $pageSize)
            ->fetchAll();
    }


    /**
     * 根据谱册id查询 创建者id
     * @param int $id 谱册id
     * @return 返回创建者id
     */
    public function getOwnerId($id)
    {
        return $this->getORM()
            ->select('owner_id')
            ->where('id', $id)
            ->fetchOne();
    }

    /**
     * 根据谱册id 查找出所有谱子id
     * @param int $collectionId
     * @return array 返回所有谱子的id
     */
    public function getScoreIdByCollectionId($collectionId)
    {
        $table = self::RELATIONSHIP;
        $ormCollection = \PhalApi\DI()->notorm->$table;
        return $ormCollection->select('score_id')
            ->where('collection_id', $collectionId)
            ->fetchAll();
    }


    /**
     * 更新谱册
     * @param array data
     * @return int 返回更新行数
     */
    public function update($data)
    {
        //开启事物
        // $table = self::RELATIONSHIP;

        // \PhalApi\DI()->notorm->beginTransaction('db_master');
        // $add = \PhalApi\DI()->notorm->$table->insert_multi($addScore);
        // $delete = \PhalApi\DI()->notorm->$table->insert_multi($addScore);
        $table = self::RELATIONSHIP;
        $ormCollection = \PhalApi\DI()->notorm->$table;

        return $ormCollection->where('id', $data['id'])->update($data);
    }
}
