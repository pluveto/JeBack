<?php
namespace App\Helper\UrlHelper;

/**
 * URL 帮助类
 * @author ZhangZijing <i@pluvet.com>
 */
class Lite
{
    /**
     * 获取请求的baseUrl
     *
     * @return string base url
     */
    function baseUrl()
    {
        return sprintf(
            "%s://%s%s%s",
            isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
            $_SERVER['SERVER_NAME'],
            $_SERVER["SERVER_PORT"] == '80' ? '' : ':' . $_SERVER["SERVER_PORT"],
            \PhalApi\DI()->config->get('app.FastRoute.base_url')
        );
    }
}
