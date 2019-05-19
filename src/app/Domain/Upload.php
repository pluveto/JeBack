<?php
namespace App\Domain;

use App\Model\Upload as Model;
use App\Model\Score as ScoreModel;

/**
 * 文件上传 Domain 类
 * 
 * @author ZhangZijing <i@pluvet.com> 2019-5-17
 */
class Upload
{

    public function getImageByScoreId(int $scoreId)
    {
        $scoreModel = new ScoreModel();
        $imageId =  $scoreModel->get($scoreId, 'image_id')['image_id'];
        $model = new Model();
        return $model->get($imageId);
    }
    /**
     * 把一个临时图片正式保存, (临时文件被移动, 之后其数据库item也被删除)
     *
     * @param int $tempId
     * @return int 插入后的正式id
     */
    public function saveImage($tempId, int $type)
    {
        $tempImage = $this->getTempImage($tempId);
        $model = new Model();
        $oldPath = \App\Helper\Path::getAbsolutePathToPublic($tempImage['path']);

        $newPath = \App\Helper\Path::getImageDir()   . '/' . basename($tempImage['path']);
        rename($oldPath, $newPath);
        $model->removeTempImage($tempId);
        $id =  intval($model->insert([
            'path' => \App\Helper\Path::getRelativePathToPublic($newPath),
            'user_id' => \App\Domain\Auth::$currentUser['id'],
            'type' => $type, // 0 曲谱 , 1 谱册
            'created_at' => time(),
        ]));
        return $id;
    }
    /**
     * 获取临时图片
     *
     * @param int $tempId 临时图片 Id
     * @return void
     */
    public function getTempImage($tempId)
    {
        $model = new Model();
        return $model->getTempImage($tempId);
    }
    /**
     * 检查图片Id是否存在并属于某用户
     *
     * @param int $tempImageId
     * @param int $userId
     * @return bool
     */
    public function checkTempImageIdOwnerMatch(int $tempImageId, int $userId)
    {
        $model = new Model();
        $tempImage = $model->getTempImage($tempImageId);
        return $tempImage && ($tempImage['user_id'] == $userId);
    }

    public function removeFile(int $id)
    {
        $model = new Model();
        $file = $model->get($id);
        if (unlink(\App\Helper\Path::getAbsolutePathToPublic($file['path']))) {
            $model->delete($id);
            return true;
        }
        return false;
    }
    public function removeTempFile(int $id)
    {
        $model = new Model();
        $file = $model->get($id);
        if (unlink(\App\Helper\Path::getAbsolutePathToPublic($file['path']))) {
            $model->delete($id);
            return true;
        }
        return false;
    }
    /**
     * 检测图像文件是否真的是一个图像文件(而不是恶意代码)
     *
     * @param string $filepath
     * @return bool 检测通过返回 true
     */
    public function checkImageMime(string $filepath)
    {
        return (in_array(mime_content_type($filepath), array('image/jpeg', 'image/png', 'image/gif', 'image/webp')));
    }
    /**
     * 保存临时图片文件, 如果路径不存在则试图创建
     * @todo: 编写定期清理临时文件的代码
     * @param array $file
     * @return mixed 成功时返回含有 ID 和 key 和 url 的数组, 失败时返回含有 ERRORMESSAGE 的数组
     */
    public function saveTempImageFile($file)
    {
        // 所有上传的文件会获得临时文件名(此为php自带临时文件)
        $tmpName = $file['tmp_name'];
        // 为文件计算一个安全的文件名
        $randomFilename = md5($file['name'] . time()) . strrchr($file['name'], '.');
        $imgPath = \App\Helper\Path::getImageTempDir() . '/' .  $randomFilename;
        if (!move_uploaded_file($tmpName, $imgPath)) {
            return array('error' => '无法移动文件');
        }

        $url = \App\Helper\Path::baseUrl() . \App\Helper\Path::getImageTempRelativeDir() . '/' . $randomFilename;
        // 将临时文件信息存到数据库
        $model = new Model();
        $fileInserted = $model->insertTempFile([
            'path' => \App\Helper\Path::getRelativePathToPublic($imgPath),
            'user_id' => \App\Domain\Auth::$currentUser['id'],
            'created_at' => time()
        ]);
        return [
            'id' => $fileInserted['id'],
            'image_url' => $url
        ];
    }

    public function getScoreImageUrl($scoreId)
    {
        $image = $this->getImageByScoreId($scoreId);
        if ($image == null) return null;
        $url = \App\Helper\Path::baseUrl() . $this->getImageByScoreId($scoreId)['path'];
        return $url;
    }
}
