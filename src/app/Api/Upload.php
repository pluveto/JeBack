<?php
namespace App\Api;

use PhalApi\Api;
use PhalApi\Exception\BadRequestException;

use \App\Domain\Upload as Domain;

/**
 * 
 * 文件(图像)上传接口类
 * 注意, 上传文件为 multipart/form-data 表单类型
 * 
 * @author ZhangZijing <i@pluvet.com> 2019-5-15
 */
class Upload extends Api
{

    public function getRules()
    {
        return [
            'uploadImage' => [
                'file' => array(
                    'name' => 'file',        // 客户端上传的文件字段
                    'type' => 'file',
                    'require' => true,
                    'max' => 2097152,        // 最大允许上传2M = 2 * 1024 * 1024, 
                    'range' => array('image/jpeg', 'image/png', 'image/gif', 'image/webp'),  // 允许的文件格式
                    'ext' => 'jpeg,jpg,png,gif,webp', // 允许的文件扩展名 
                    'desc' => '待上传的图片文件',
                ),
            ]
        ];
    }

    /**
     * 上传文件(此接口暂不开放)
     * 
     * @routine /upload/file
     *
     * @return void
     */
    public function uploadFile()
    { }
    /**
     * 上传临时图片(有效期 24 小时)
     * 
     * @desc 用户上传曲谱之前, 需要上传临时图片. 该接口返回给用户一个nonce和文件id.
     * 
     * 
     *  
     * 服务器数据存nonce, fileId , filepath 和 timestamp.
     * 正式提交时, 带上nonce和文件id, 检验一致, 会将文件移动到正式文件夹
     * 如果超过24小时没有正式提交, 就删除临时文件
     *
     * @return int id 临时图片 id
     * @return string nonce 校验随机串
     */
    public function uploadImage()
    {
        $domain = new Domain();
        // mime 检测
        if (!$domain->checkImageMime($this->file['tmp_name'])) {
            throw new BadRequestException("文件非法, 未通过安全性检测.");
        }
        // 保存
        $result = $domain->saveTempImageFile($this->file);
        if (array_key_exists('error', $result)) {
            throw new BadRequestException("上传失败: " . $result['error']);
        }
        return [
            'id' => $result['id'],
            'url' => $result['url']
        ];
    }
}
