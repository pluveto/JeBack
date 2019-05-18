<?php
namespace App\Domain;

use App\Model\Upload as Model;


/**
 * 文件上传 Domain 类
 * 
 * @author ZhangZijing <i@pluvet.com> 2019-5-17
 */
class Upload
{

    /**
     * 检测图像文件是否真的是一个图像文件(而不是恶意代码)
     *
     * @param string $filepath
     * @return bool 检测通过返回 true
     */
    public function checkImageMime($filepath)
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
        $imgPath = \App\Helper\Path::getImageTempDir() .  $randomFilename;
        if (!move_uploaded_file($tmpName, $imgPath)) {
            return array('error' => '无法移动文件');
        }

        $url = \App\Helper\Path::baseUrl() . \App\Helper\Path::getImageTempDirRel() . $randomFilename;
        // 将临时文件信息存到数据库
        $model = new Model();
        $fileInserted = $model->insertTempFile([
            'path' => $imgPath,
            'user_id' => \App\Domain\Auth::$currentUser['id'],
            'created_at' => time()
        ]);
        return [
            'id' => $fileInserted['id'],
            'url' => $url
        ];
    }
}
