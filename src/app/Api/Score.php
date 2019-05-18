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
                'alias' => ['name' => 'alias', 'require' => false, 'min' => 1, 'max' => 512, 'type' => 'array', 'format' => 'json', 'default' => '[]'],
                'anime' => ['name' => 'anime', 'require' => false, 'min' => 1, 'max' => 512, 'type' => 'string', 'default' => '单曲'],
                'key' =>   [
                    'name' => 'key', 'require' => false, 'type' => 'enum',
                    'range' => ['C', 'C#', 'D', 'D#', 'E', 'F', 'F#', 'G', 'G#', 'A', 'A#', 'B'], 'default' => 'C' //就不用int存了, 不缺这点空间
                ],
                // 类型, 默认为0, JE谱, 目前只支持这种.
                'type' =>  ['name' => 'type', 'require' => false, 'min' => 0, 'max' => 0, 'type' => 'int', 'default' => 0],
                'description' => ['name' => 'description', 'require' => false, 'min' => 0, 'max' => 10000, 'type' => 'string', 'default' => ''],
                'addition' => ['name' => 'addition', 'require' => false, 'min' => 1, 'max' => 255, 'type' => 'string', 'default' => ''],
                // 为了避免未实际访问提交曲谱API, 造成垃圾文件, 
                // 先上传到临时文件夹, 提交后再转移到正式文件夹. 临时文件夹定时清理.
                'temp_image_id' => ['name' => 'temp_image_id', 'require' => false, 'min' => 1, 'max' => 255, 'type' => 'int', 'default' => 0], // 图片 id 为零表示不指定id
            ],
            'updateScore' => [
                'id' => ['name' => 'id', 'require' => true, 'type' => 'int'],
                'title' => ['name' => 'title', 'require' => true, 'min' => 1, 'max' => 255, 'type' => 'string'],
                'text' => ['name' => 'title', 'require' => true, 'min' => 1, max => 10000, 'type' => 'string'],
                'alias' => ['name' => 'alias', 'require' => false, 'min' => 1, 'max' => 512, 'type' => 'array', 'format' => 'json', 'default' => '[]'],
                'anime' => ['name' => 'anime', 'require' => false, 'min' => 1, 'max' => 512, 'type' => 'string', 'default' => '单曲'],
                'key' =>   [
                    'name' => 'key', 'require' => false, 'type' => 'enum',
                    'range' => ['C', 'C#', 'D', 'D#', 'E', 'F', 'F#', 'G', 'G#', 'A', 'A#', 'B'], 'default' => 'C' //就不用int存了, 不缺这点空间
                ],
                'type' =>  ['name' => 'type', 'require' => false, 'min' => 0, 'max' => 0, 'type' => 'int', 'default' => 0],
                'description' => ['name' => 'description', 'require' => false, 'min' => 0, 'max' => 10000, 'type' => 'string', 'default' => ''],
                'addition' => ['name' => 'addition', 'require' => false, 'min' => 1, 'max' => 255, 'type' => 'string', 'default' => ''],
                // 图片 id 为零表示不更新id
                'temp_image_id' => ['name' => 'temp_image_id', 'require' => false, 'min' => 1, 'max' => 255, 'type' => 'int', 'default' => 0],
            ],
            'removeScore' => [
                'id' => ['name' => 'id', 'require' => true, 'type' => 'int'],
            ],
            'getScore' => [
                'id' => ['name' => 'id', 'require' => true, 'type' => 'int'],

            ],
            'getScoreList' => array(
                'page' => array('name' => 'page', 'type' => 'int', 'min' => 1, 'default' => 1, 'desc' => '第几页'),
                'perpage' => array('name' => 'perpage', 'type' => 'int', 'min' => 1, 'max' => 20, 'default' => 10, 'desc' => '分页数量'),
            ),

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
        $domain = new Domain();

        /** ============== 格式检查 ============== */
        $this->title = trim($this->title);
        $this->anime = trim($this->anime);
        $this->alias = json_encode(array_filter(json_decode($this->alias)));
        $this->addition = trim($this->addition);

        /** ============== 正式检查 ============== */
        // 检查临时图片id是否存在并属于当前用户(同时也检查了图片的有效性)
        $userId = \App\Domain\Auth::$currentUser['id'];
        if ($this->temp_image_id > 0 && !$domain->checkIdOwnerMatch($this->temp_image_id, $userId)) {
            throw new BadRequestException("图片长期未用被清理, 或者填入了错误的图片id.");
        }

        /** ============== 正式业务 ============== */
        //如果提交了图片id, 就把图片以正式文件转存
        if ($this->temp_image_id > 0) {
            $this->image_id = $domain->saveImage($this->temp_image_id,  0)['id'];
        }
        $domain->addScore(
            $this->tilte,
            $this->text,
            $this->alias,
            $this->anime,
            $this->key,
            $this->type,
            $this->description,
            $this->addition,
            $this->image_id
        );
    }
    /**
     * 更新曲谱
     * @routine /score/update
     * @return void
     */
    public function updateScore()
    {
        $domain = new Domain();
        $uploadDomain = new \App\Domain\Upload();
        /** ============== 格式检查 ============== */
        $this->title = trim($this->title);
        $this->anime = trim($this->anime);
        $this->alias = json_encode(array_filter(json_decode($this->alias)));
        $this->addition = trim($this->addition);

        /** ============== 正式检查 ============== */
        $userId = \App\Domain\Auth::$currentUser['id'];

        // 检查要更新的曲谱是否属于当前用户(同时也检查了该曲谱是否存在)
        if (!$domain->checkIdOwnerMatch($this->id, $userId)) {
            throw new BadRequestException("曲谱不存在, 或者你没有权限修改此曲谱.");
        }
        // 检查临时图片id是否存在并属于当前用户(同时也检查了图片的有效性)
        if ($this->temp_image_id > 0 && !$uploadDomain->checkIdOwnerMatch($this->temp_image_id, $userId)) {
            throw new BadRequestException("图片长期未用被清理, 或者你没有权限引用此图片.");
        }

        /** ============== 正式业务 ============== */
        // 如果提交了图片id, 就把图片以正式文件转存
        if ($this->temp_image_id > 0) {
            $this->image_id = $uploadDomain->saveImage($this->temp_image_id,  0)['id'];
        }
        // 如果提交了新的图片id, 但是之前已经关联了图片, 就删除图片解除关联
        $imageOnServer = $uploadDomain->getImageByScoreId($this->id);
        if ($imageOnServer != null && $this->temp_image_id > 0) {
            //delete old
            $uploadDomain->removeFile($imageOnServer['id']);
            $this->image_id = $uploadDomain->saveImage($this->temp_image_id,  0)['id'];
        } else
        if ($imageOnServer != null && $this->temp_image_id == 0) {
            $this->image_id = $imageOnServer['id'];
        }
        // 第三四种情况无需考虑

        $domain->updateScore(
            $this->tilte,
            $this->text,
            $this->alias,
            $this->anime,
            $this->key,
            $this->type,
            $this->description,
            $this->addition,
            $this->image_id
        );
    }
    /**
     * 删除曲谱
     * @routine /score/remove
     * @return void
     */
    public function removeScore()
    {
        $domain = new Domain();
        $uploadDomain = new \App\Domain\Upload();
        /** ============== 格式检查 ============== */

        /** ============== 正式检查 ============== */
        $userId = \App\Domain\Auth::$currentUser['id'];

        // 检查要更新的曲谱是否属于当前用户(同时也检查了该曲谱是否存在)
        if (!$domain->checkIdOwnerMatch($this->id, $userId)) {
            throw new BadRequestException("曲谱不存在, 或者你没有权限修改此曲谱.");
        }
        // 移除曲谱关联的图片
        $imageOnServer = $uploadDomain->getImageByScoreId($this->id);
        $uploadDomain->removeFile($imageOnServer['id']);
        // 移除曲谱
        $domain->removeScore($this->id);

        return [];
    }
    /**
     * 获取曲谱
     * @routine /score
     * @return void
     */
    public function getScore()
    {
        $domain = new Domain();
        $uploadDomain = new \App\Domain\Upload();
        // 检查所需曲谱是否存在
        if (!$domain->checkScoreExist($this->id)) {
            throw new BadRequestException("曲谱不存在");
        }
        // 展开曲谱信息
        return $domain->packUpScoreInfo($this->id);
    }
    /**
     * 获取曲谱列表
     * @routine /score/list
     * @return void
     */
    public function getScoreList()
    {

        $domain = new Domain();
        return $domain-> packUpScoreList($this->page, $this->perpage);
    }
    /**
     * 关键字搜索曲谱
     * @routine /score/search
     * @return void
     */
    public function searchScore()
    {
        $domain = new Domain();
        return $domain->getScoreList($this->state, $this->page, $this->perpage);
    }
}
