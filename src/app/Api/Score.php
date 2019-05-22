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
                'text' => ['name' => 'text', 'require' => true, 'min' => 1, 'max' => 10000, 'type' => 'string'],

                // 注意: json 数组的 $this 绑定值（$this->alias）已经转换为了 php 数组
                'alias' => ['name' => 'alias', 'require' => false, 'min' => 0, 'max' => 512, 'type' => 'array', 'format' => 'json', 'default' => '[]'],
                'anime' => ['name' => 'anime', 'require' => false, 'min' => 0, 'max' => 512, 'type' => 'string', 'default' => '单曲'],
                'key' =>   [
                    'name' => 'key', 'require' => false, 'type' => 'enum',
                    'range' => ['C', 'C#', 'D', 'D#', 'E', 'F', 'F#', 'G', 'G#', 'A', 'A#', 'B'], 'default' => 'C' //就不用int存了, 不缺这点空间
                ],
                // 类型, 默认为0, JE谱, 目前只支持这种.
                'type' =>  ['name' => 'type', 'require' => false, 'min' => 0, 'max' => 0, 'type' => 'int', 'default' => 0],
                'description' => ['name' => 'description', 'require' => false, 'min' => 0, 'max' => 10000, 'type' => 'string', 'default' => ''],
                'addition' => ['name' => 'addition', 'require' => false, 'min' => 0, 'max' => 255, 'type' => 'string', 'default' => ''],
                // 为了避免未实际访问提交曲谱API, 造成垃圾文件, 
                // 先上传到临时文件夹, 提交后再转移到正式文件夹. 临时文件夹定时清理.
                'temp_image_id' => ['name' => 'temp_image_id', 'require' => false, 'min' => 0, 'max' => 255, 'type' => 'int', 'default' => 0], // 图片 id 为零表示不指定id
            ],
            'updateScore' => [
                'id' => ['name' => 'id', 'require' => true, 'type' => 'int'],
                'title' => ['name' => 'title', 'require' => true, 'min' => 1, 'max' => 255, 'type' => 'string'],
                'text' => ['name' => 'text', 'require' => true, 'min' => 1, 'max' => 10000, 'type' => 'string'],
                'alias' => ['name' => 'alias', 'require' => false, 'min' => 0, 'max' => 512, 'type' => 'array', 'format' => 'json', 'default' => '[]'],
                'anime' => ['name' => 'anime', 'require' => false, 'min' => 0, 'max' => 512, 'type' => 'string', 'default' => '单曲'],
                'key' =>   [
                    'name' => 'key', 'require' => false, 'type' => 'enum',
                    'range' => ['C', 'C#', 'D', 'D#', 'E', 'F', 'F#', 'G', 'G#', 'A', 'A#', 'B'], 'default' => 'C' //就不用int存了, 不缺这点空间
                ],
                'type' =>  ['name' => 'type', 'require' => false, 'min' => 0, 'max' => 0, 'type' => 'int', 'default' => 0],
                'description' => ['name' => 'description', 'require' => false, 'min' => 0, 'max' => 10000, 'type' => 'string', 'default' => ''],
                'addition' => ['name' => 'addition', 'require' => false, 'min' => 0, 'max' => 255, 'type' => 'string', 'default' => ''],
                // 图片 id 为零表示不更新id
                'temp_image_id' => ['name' => 'temp_image_id', 'require' => false, 'min' => 0, 'max' => 255, 'type' => 'int', 'default' => 0],
            ],
            'removeScore' => [
                'id' => ['name' => 'id', 'require' => true, 'type' => 'int'],
            ],
            'getScore' => [
                'id' => ['name' => 'id', 'require' => true, 'type' => 'int'],

            ],
            'getScoreList' => array(
                'page' => array('name' => 'page',  'require' => true, 'type' => 'int', 'min' => 1, 'default' => 1, 'desc' => '第几页'),
                'perpage' => array('name' => 'perpage', 'require' => true, 'type' => 'int', 'min' => 1, 'max' => 20, 'default' => 10, 'desc' => '分页数量'),
            ),
            'searchScore' => array(
                'keyword' => ['name' => 'keyword', 'require' => true, 'min' => 1, 'max' => 255, 'type' => 'string'],

                'page' => array('name' => 'page',  'require' => true, 'type' => 'int', 'min' => 1, 'default' => 1, 'desc' => '第几页'),
                'perpage' => array('name' => 'perpage', 'require' => true, 'type' => 'int', 'min' => 1, 'max' => 20, 'default' => 10, 'desc' => '分页数量'),
            ),

        );
    }
    /**
     * 添加曲谱
     * @todo 敏感词检查
     * @routine /score/add
     * @return array 刚返回的曲谱id
     */
    /**
     * @api {post} /score/add 添加曲谱
     * @apiDescription 添加曲谱.
     * @apiVersion 2.0.0
     * @apiName addScore
     * @apiPermission user
     * @apiGroup Score
     *
     * @apiParam {String} title  曲谱标题.
     * @apiParam {String} addition  曲谱标题附加信息.  
     * @apiParam {String} text  曲谱正文.
     * @apiParam {Integer} [temp_image_id=0]  临时图片Id, 默认为0表示无配图.
     * @apiParam {Array} [alias='[]']  别名列表(json纯文本数组), 默认为空数组[].
     * @apiParam {String} [anime='单曲']  出处作品, 不填则默认为'单曲'.
     * @apiParam {Enum} [key='C']  调性, 默认为'C', 取值范围:
     * 
            ['C', 'C#', 'D', 'D#', 'E', 'F', 'F#', 'G', 'G#', 'A', 'A#', 'B']
     * @apiParam {Description} [description]  曲谱介绍.  
     * @apiParamExample Request-Example:
            'title': 'Test With Detail',
            'text': '1 2 3 4 5 6 7',
            'temp_image_id': img_id,
            'alias': json.dumps(['Alias 1', 'Alias 2']),
            'anime': 'Anime Name',
            'key': 'C#',
            'type': 0,
            'description': 'Hello, world',
            'addition': 'Some thing to append on title'
     *
     * @apiSuccess {Type} field Field description.
     *
     * @apiSuccessExample 成功响应:
            {
                "ret": 200,
                "data": {
                    "id": 150,
                    "image_url": "http:\/\/...\/uploads\/images\/2019\/05\/22\/2b6e9bea199a5cdc8c9f93d59aaabed2.png"
                },
                "msg": ""
            }
     */
    public function addScore()
    {
        $domain = new Domain();
        $uploadDomain = new \App\Domain\Upload();
        /** ============== 格式检查 ============== */
        $this->title = trim($this->title);
        $this->anime = trim($this->anime);
        $this->alias = json_encode(array_filter($this->alias));
        $this->addition = trim($this->addition);

        /** ============== 正式检查 ============== */
        // 检查临时图片id是否存在并属于当前用户(同时也检查了图片的有效性)
        $userId = \App\Domain\Auth::$currentUser['id'];
        if ($this->temp_image_id > 0 && !$uploadDomain->checkTempImageIdOwnerMatch($this->temp_image_id, $userId)) {
            throw new BadRequestException("图片长期未用被清理, 或者填入了错误的图片id.");
        }

        /** ============== 正式业务 ============== */
        $imageUrl = "";
        $imagePath = "";
        //如果提交了图片id, 就把图片以正式文件转存
        if ($this->temp_image_id > 0) {
            $this->image_id = $uploadDomain->saveImage($this->temp_image_id,  0, $imageUrl, $imagePath);
        } else {
            $this->image_id = 0;
        }
        if ($this->image_id == null) {
            $this->image_id = 0;
        }
        $id =  $domain->addScore(
            $this->title,
            $this->text,
            $this->alias,
            $this->anime,
            $this->key,
            $this->type,
            $this->description,
            $this->addition,
            $this->image_id,
            $imagePath
        );
        $return =  [
            'id' => intval($id)
        ];
        if ($this->image_id > 0) {
            $return['image_url'] = $imageUrl;
        }
        return $return;
    }
    /**
     * 更新曲谱
     * @routine /score/update
     * @return void
     */
    /**
     * @api {post} /score/add 更新曲谱
     * @apiDescription 更新曲谱.
     * @apiVersion 2.0.0
     * @apiName updateScore
     * @apiPermission user
     * @apiGroup Score
     * @apiParam {Integer} id  曲谱id.
     * @apiParam {String} title  曲谱标题.
     * @apiParam {String} addition  曲谱标题附加信息.  
     * @apiParam {String} text  曲谱正文.
     * @apiParam {Integer} [temp_image_id]  临时图片Id, 默认为0表示无配图.
     * @apiParam {Array} [alias]  别名列表(json纯文本数组), 默认为空数组[].
     * @apiParam {String} [anime]  出处作品, 不填则默认为'单曲'.
     * @apiParam {Enum} [key]  调性, 默认为'C', 取值范围:
            'range' => ['C', 'C#', 'D', 'D#', 'E', 'F', 'F#', 'G', 'G#', 'A', 'A#', 'B']
     * @apiParam {Description} [description]  曲谱介绍.  
     * @apiParamExample Request-Example:
            'title': 'Test With Detail',
            'text': '1 2 3 4 5 6 7',
            'temp_image_id': img_id,
            'alias': json.dumps(['Alias 1', 'Alias 2']),
            'anime': 'Anime Name',
            'key': 'C#',
            'type': 0,
            'description': 'Hello, world',
            'addition': 'Some thing to append on title'
     *
     * @apiSuccess {Type} field Field description.
     *
     * @apiSuccessExample 成功响应:
            {
                "ret": 200,
                "data": {
                    "id": 150,
                    "image_url": "http:\/\/...\/uploads\/images\/2019\/05\/22\/01b736f5049218567daab1f2411253ba.png"
                },
                "msg": ""
            }
     */
    public function updateScore()
    {
        $domain = new Domain();
        $uploadDomain = new \App\Domain\Upload();
        /** ============== 格式检查 ============== */
        $this->title = trim($this->title);
        $this->anime = trim($this->anime);
        $this->alias = json_encode(array_filter($this->alias));
        $this->addition = trim($this->addition);

        /** ============== 正式检查 ============== */
        $userId = \App\Domain\Auth::$currentUser['id'];

        // 检查要更新的曲谱是否属于当前用户(同时也检查了该曲谱是否存在)
        if (!$domain->checkIdOwnerMatch($this->id, $userId)) {
            throw new BadRequestException("曲谱不存在, 或者你没有权限修改此曲谱.");
        }
        // 检查临时图片id是否存在并属于当前用户(同时也检查了图片的有效性)
        if ($this->temp_image_id > 0 && !$uploadDomain->checkTempImageIdOwnerMatch($this->temp_image_id, $userId)) {
            throw new BadRequestException("图片长期未用被清理, 或者你没有权限引用此图片.");
        }

        /** ============== 正式业务 ============== */

        // 两个冗余字段，减少查表次数
        $imageUrl = "";
        $imagePath = "";
        // 如果提交了新的图片id, 但是之前已经关联了图片, 就删除图片解除关联
        $imageOnServer = $uploadDomain->getImageByScoreId($this->id);
        if ($imageOnServer != null && $this->temp_image_id > 0) {
            //delete old
            $uploadDomain->removeFile($imageOnServer['id']);
            $this->image_id = $uploadDomain->saveImage($this->temp_image_id,  0, $imageUrl, $imagePath);
        } elseif ($imageOnServer != null && $this->temp_image_id == 0) {
            $this->image_id = $imageOnServer['id'];
        } elseif ($imageOnServer == null && $this->temp_image_id > 0) {
            $this->image_id = $uploadDomain->saveImage($this->temp_image_id,  0, $imageUrl, $imagePath);
        }
        // 第三四种情况无需考虑
        if ($this->image_id == null) {
            $this->image_id = 0; //image_id 为0 就表示该内容不配图
        }
        $domain->updateScore(
            $this->id,
            $this->title,
            $this->text,
            $this->alias,
            $this->anime,
            $this->key,
            $this->type,
            $this->description,
            $this->addition,
            $this->image_id,
            $imagePath
        );
        $return =  [
            'id' => intval($this->id)
        ];
        if ($this->image_id > 0) {
            $return['image_url'] = $imageUrl;
        }
        return $return;
    }
    /**
     * @api {post} /score/remove 删除曲谱
     * @apiDescription 删除曲谱.
     * @apiVersion 2.0.0
     * @apiName removeScore
     * @apiPermission user
     * @apiGroup Score
     *
     * @apiParam {Integer} id  曲谱id.

     *
     * @apiSuccessExample 成功响应:
            {
                "ret": 200,
                "data": {},
                "msg": ""
            }
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
     * @api {post} /score 获取曲谱
     * @apiDescription 删除曲谱.
     * @apiVersion 2.0.0
     * @apiName getScore
     * @apiPermission none
     * @apiGroup Score
     *
     * @apiParam {Integer} id  曲谱id.

     *
     * @apiSuccessExample 成功响应:
            {
                "ret": 200,
                "data": {
                    "id": "150",
                    "title": "Updated Test With Detail",
                    "text": "Updated 1 2 3 4 5 6 7",
                    "username": "pluvet",
                    "alias": ["Updated", "Alias 2"],
                    "anime": "Updated Anime Name",
                    "key": "D",
                    "description": "Updated Hello, world",
                    "addition": "Updated Some thing to append on title",
                    "image_url": "http:\/\/...\/uploads\/images\/2019\/05\/22\/01b736f5049218567daab1f2411253ba.png"
                },
                "msg": ""
            }
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
    /**
     * @api {post} /score/list 获取曲谱列表
     * @apiDescription 获取曲谱列表, 按发布时间倒序排序.
     * @apiVersion 2.0.0
     * @apiName getScoreList
     * @apiPermission none
     * @apiGroup Score
     *
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
    public function getScoreList()
    {

        $domain = new Domain();
        return $domain->packUpScoreList($this->page, $this->perpage);
    }
    /**
     * @api {post} /score/list 搜索曲谱
     * @apiDescription 使用关键字搜索曲谱.
     * @apiVersion 2.0.0
     * @apiName searchScore
     * @apiPermission none
     * @apiGroup Score
     *
     * @apiParam {String} keyword  关键字.
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
    public function searchScore()
    {
        $domain = new Domain();
        return $domain->searchScoreByTitle($this->keyword, $this->page, $this->perpage);
    }
}
