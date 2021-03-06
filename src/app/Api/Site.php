<?php
namespace App\Api;

use PhalApi\Api;

/**
 * 默认接口服务类
 * @author: dogstar <chanzonghuang@gmail.com> 2014-10-04
 */
class Site extends Api
{
    public function getRules()
    {
        return array(
            'index' => array(),
        );
    }

    /**
     * 默认接口服务
     * @desc 默认接口服务，当未指定接口服务时执行此接口服务
     * @return string title 标题
     * @return string content 内容
     * @return string version 版本，格式：X.X.X
     * @return int time 当前时间戳
     * @exception 400 非法请求，参数传递错误
     */
    public function index()
    {
        $base_url = \App\Helper\Path::baseUrl();
        return array(
            'title' => 'Hello, Moe JE!',
            'version' => '1.0.0',
            'time' => $_SERVER['REQUEST_TIME'],
            'urls' => array(
                'site_index_url' => $base_url . "/",
                'login_url' => $base_url . "/auth/login/email",
                'register_url' => $base_url . "/auth/register"

            )
        );
    }
}
