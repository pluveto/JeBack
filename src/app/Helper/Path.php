<?php
namespace App\Helper;

/**
 * URL 帮助类
 * @author ZhangZijing <i@pluvet.com>
 */
class Path
{
    /**
     * 获取请求的baseUrl
     *
     * @return string base url
     */
    public static function baseUrl()
    {
        return sprintf(
            "%s://%s%s%s",
            isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
            $_SERVER['SERVER_NAME'],
            $_SERVER["SERVER_PORT"] == '80' ? '' : ':' . $_SERVER["SERVER_PORT"],
            \PhalApi\DI()->config->get('app.url.base_url')
        );
    }

    /**
     * 获取临时图片文件夹
     *
     * @return string 临时文件文件夹, 绝对路径
     */
    public static function getImageTempDir()
    {
        $dir =  realpath(API_ROOT) . "/public" . str_replace("{date}", date("Y/m/d"), \PhalApi\DI()->config->get('je.file_upload.image.temp_dir'));
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        if (!Path::endsWith($dir, '/')) $dir = $dir . '/';
        return $dir;
    }
    /**
     * 获取临时图片文件夹相对路径
     *
     * @return string 临时文件文件夹, 绝对路径
     */
    public static function getImageTempDirRel()
    {
        $dir = str_replace("{date}", date("Y.m.d"), \PhalApi\DI()->config->get('je.file_upload.image.temp_dir'));
        if (!Path::endsWith($dir, '/')) $dir = $dir . '/';
        return $dir;
    }

    private static function endsWith($haystack, $needle)
    {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }

        return (substr($haystack, -$length) === $needle);
    }
}
