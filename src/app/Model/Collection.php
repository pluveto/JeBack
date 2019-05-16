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
        $tabel = self::RELATIONSHIP;
        $ormCollection = \PhalApi\DI()->notorm->$tabel;
        return $ormCollection->insert($data);
    }


    /**
     * 一些谱子添加到谱册
     * @param array $data
     */
    public function addScoresToCollection($data)
    {
        $tabel = self::RELATIONSHIP;
        $ormCollection = \PhalApi\DI()->notorm->$tabel;
        return $ormCollection->insert_multi($data);
    }

    /**
     * 获取一个谱册
     * @param id
     * @return 返回指定谱册
     */
    public function getCollection($id)
    {
        return $this->getORM()->where('id', $id)->fetchOne();
    }


    /**
     * 获取一些谱册 
     * @param int 当前页码
     * @param int 每页显示
     * @param int 谱册状态
     * @return 返回谱册列表
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
    public function searchByName($name)
    {
        return $this->getORM()->where('title LIKE ?', '%' + $name + '%')->fetchAll();
    }
}
