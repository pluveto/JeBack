<?php
namespace App\Helper;

/**
 * URL 帮助类
 * @author ZhangZijing <i@pluvet.com>
 */
class Path
{
    /**
     * 获取API系统的的baseUrl
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
     * 获取临时图片文件夹绝对路径, 不以 / 结尾
     *
     * @return string 临时文件文件夹, 绝对路径
     */
    public static function getImageTempDir()
    {
        $dir =  realpath(API_ROOT) . "/public" . self::getImageTempRelativeDir();
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        return $dir;
    }
    /**
     * 获取临时图片文件夹相对路径, 不以 / 结尾
     *
     * @return string 临时文件文件夹, 相对路径
     */
    public static function getImageTempRelativeDir()
    {
        $dir = str_replace("{date}", date("Y/m/d"), \PhalApi\DI()->config->get('je.file_upload.image.temp_dir'));
        return $dir;
    }
    /**
     * 获取正式图片文件夹绝对路径, 不以 / 结尾
     *
     * @return string 临时文件文件夹, 绝对路径
     */
    public static function getImageDir()
    {
        $dir =  realpath(API_ROOT) . "/public" . self::getImageRelativeDir();
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        return $dir;
    }
    /**
     * 获取正式图片文件夹相对路径(包含{date}替换(若有)), 不以 / 结尾
     *
     * @return string 正式文件文件夹, 相对路径
     */
    public static function getImageRelativeDir()
    {
        $dir = str_replace("{date}", date("Y/m/d"), \PhalApi\DI()->config->get('je.file_upload.image.dir'));
        return $dir;
    }
    public static function getRelativePathToPublic($path)
    {
        $newPath =  substr($path, strlen(realpath(API_ROOT) . "/public"));
        return $newPath;
    }
    /**
     * 获取/public下的绝对路径 输入的path要以 / 开头
     *
     * @param string $path
     * @return string
     */
    public static function getAbsolutePathToPublic($path)
    {
        $newPath =  realpath(API_ROOT) . "/public" . $path;
        return $newPath;
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
