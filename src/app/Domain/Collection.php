<?php
namespace App\Domain;

use App\Model\Collection as CollectionModel;
use App\Model\User as UserModel;

/**
 * Collection 谱册类
 * @author LW <lw1020573989@live.com>
 */

class Collection
{

	/**
	 * 创建一个空谱册
	 *
	 * @param string username
	 * @param string image 图片地址
	 * @param string title 谱册名
	 * @param string description 描述
	 */
	public function createCollection($userId, $imagePath, $imageId, $title, $description)
	{
		$userModel = new UserModel();
		$collcetionModel = new CollectionModel();

		$data = array(
			'user_id' => $userId,
			'image_path' => $imagePath,
			'image_id' => $imageId,
			'title' => $title,
			'description' => $description,
			'status' => 0,
			'view_num' => 0,
			'created_at' => time(),
			'updated_at' => time()
		);

		$collcetionModel->createCollection($data);
	}


	/**
	 * 向谱册加入一个谱子
	 *
	 * @param int scoreId
	 * @param int collectionId
	 */
	public function addScoreToCollection($scoreId, $collectionId)
	{
		$collcetionModel = new CollectionModel();

		$data = array(
			'collection_id' => $collectionId,
			'score_id' => $scoreId
		);
		$collcetionModel->addScoreToCollection($data);
	}
	/**
	 * 向谱册加入一些谱子
	 *
	 * @param array scoreId
	 * @param int collectionId
	 */
	public function addScoresToCollection($scoreIdList, $collectionId)
	{
		$collcetionModel = new CollectionModel();

		$data = array();
		for ($i = 0; $i < count(scoreIdList); $i++) {
			$data[] = ['collection_id' => $collectionId, 'score_id' => $scoreIdList[i]];
		}
		$collcetionModel->addScoresToCollection($data);
	}

	/**
	 * 获取指定谱册
	 *
	 * @param int collectionId
	 */
	public function getCollection($collectionId)
	{
		$collcetionModel = new CollectionModel();
		return $collcetionModel->get($collectionId);
	}

	/**
	 * 删除指定谱册(软删除) 将状态从status 0 =>2
	 * 0 为正常 1 是对非作者隐藏, 2 是对所有用户隐藏
	 * @param int collectionId
	 */
	public function removeCollection($collectionId)
	{
		$collcetionModel = new CollectionModel();
		$data = array(
			'id' => $collectionId,
			'status' => 2
		);
		$collcetionModel->changeCollectionStatus($data);
	}

	/**
	 * 获取谱册列表
	 * @param int page 当前页码
	 * @param int pageSize 一页多少谱册
	 * @ 0代表可见谱册
	 */
	public function getList($page, $pageSize)
	{
		$collcetionModel = new CollectionModel();
		return $collcetionModel->getList($page, $pageSize, 0);
	}


	/**
	 *  根据谱册名 进行搜索
	 * @param string title
	 * @return 相关谱册列表
	 */
	public function search($title, $page, $pageSize)
	{
		$collcetionModel = new CollectionModel();
		return $collcetionModel->searchByName($title, $page, $pageSize);
	}

	/**
	 * 检查曲谱是否存在
	 *
	 * @param integer $collectionId
	 * @return void
	 */
	public function checkCollectionExist(int $collectionId)
	{
		$model = new Model();
		$score = $model->get($collectionId);
		return $score != null;
	}


	/**
	 * 通过谱册id获得创建者的的id
	 * @param int $id
	 * @return 返回创建者id
	 */
	public function getOwnerId($id)
	{
		$model = new Model();
		return $model->getOwnerId($id);
	}

	/**
	 * 检查谱册是否存在并属于某用户
	 *
	 * @param Type $var
	 * @return void
	 */
	public function checkIdOwnerMatch(int $collectionId, int $userId)
	{
		$model = new Model();
		$collection = $model->get($collectionId);
		return $collection && ($collection['user_id'] == $userId);
	}


	/**
	 * 更新谱册
	 * @param int collectionId
	 * @param string title
	 * @param string description
	 * @param int imageId
	 * @param string imagePath
	 */
	public function update($collectionId, $title, $description, $imageId, $imagePath)
	{
		$model = new Model();

		// $scoreIdList = $model->getScoreIdByCollectionId($collectionId);
		// $addScore = array_diff($score_id, $scoreIdList);
		// $delectScore = array_diff($scoreIdList, $score_id);
		// $addData = array();
		// for ($i = 0; $i < count(addScore); $i++) {
		//     $data[] = ['collection_id' => $collectionId, 'score_id' => $addScore[i]];
		// }
		// for ($i = 0; $i < count(delectScore); $i++) {
		//     $data[] = ['collection_id' => $collectionId, 'score_id' => $delectScore[i]];
		// }
		$data[] = [
			'id' => $collectionId,
			'title' => $title,
			'description' => $description,
			'image_id' => $imageId,
			'image_path' => $imagePath,
			'updated_at' => time()
		];
		return $model->update($data);
	}
}
