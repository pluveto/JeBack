<?php
namespace App\Api;

use PhalApi\Api;
use PhalApi\Exception\BadRequestException;

use \App\Domain\Score as Domain;

/**
 * 曲谱操作类
 * 
 * @author ZhangZijing <i@pluvet.com> 2019-5-15
 */
class Score extends Api
{
    public function getRules()
    {
        return array(
            'addScore' => [
                'title' => ['name' => 'title', 'require' => true, 'min' => 1, 'max' => 255, 'type' => 'string'],
                'text' => ['name' => 'title', 'require' => true, 'min' => 1, max => 10000, 'type' => 'string'],
                'alias' => ['name' => 'alias', 'require' => false, 'min' => 1, 'max' => 512, 'type' => 'string'],
                'anime' => ['name' => 'anime', 'require' => false, 'min' => 1, 'max' => 512, 'type' => 'string'],
                'key' =>   [
                    'name' => 'key', 'require' => false, 'type' => 'enum',
                    'range' => ['C', 'C#', 'D', 'D#', 'E', 'F', 'F#', 'G', 'G#', 'A', 'A#', 'B'], 'default' => 'C'
                ],
                // 类型, 默认为0, JE谱, 目前只支持这种.
                'type' =>  ['name' => 'type', 'require' => false, 'min' => 0, 'max' => 0, 'type' => 'int'],
                'description' => ['name' => 'description', 'require' => false, 'min' => 0, 'max' => 10000, 'type' => 'string'],
                'addition' => ['name' => 'addition', 'require' => false, 'min' => 1, 'max' => 255, 'type' => 'string'],
                // 为了避免未实际访问提交曲谱API, 造成垃圾文件, 
                // 先上传到临时文件夹, 提交后再转移到正式文件夹. 临时文件夹定时清理.
                'image' => ['name' => 'image', 'require' => false, 'min' => 1, 'max' => 255, 'type' => 'string'],
            ]

        );
    }
    /**
     * 添加曲谱
     * @todo 敏感词检查
     * @routine /score/add
     * @return array 刚返回的曲谱id
     */
    public function addScore()
    {
        /** ============== 格式检查 ============== */
        /** ------- 检查title ------- */


        /** ============== 正式检查 ============== */
    }
    /**
     * 更新曲谱
     * @routine /score/update
     * @return void
     */
    public function updateScore()
    { }
    /**
     * 删除曲谱
     * @routine /score/remove
     * @return void
     */
    public function removeScore()
    { }
    /**
     * 获取曲谱
     * @routine /score
     * @return void
     */
    public function getScore()
    { }
    /**
     * 获取曲谱列表
     * @routine /score/list
     * @return void
     */
    public function getScoreList()
    { }
    /**
     * 关键字搜索曲谱
     * @routine /score/search
     * @return void
     */
    public function searchScore()
    { }
}
