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
                'image' => ['name' => 'image', 'require' => false, 'min' => 1, 'max' => 255, 'type' => 'string'],

            ]

        );
    }
    /**
     * 添加曲谱
     *
     * @return array 刚返回的曲谱id
     */
    public function addScore()
    {
        /** ============== 格式检查 ============== */

        /** ============== 正式检查 ============== */
    }
    public function updateScore()
    { }
    public function removeScore()
    { }
    public function getScore()
    { }
    public function getScoreList()
    { }
    public function searchScore()
    { }
}
