<?php
namespace App\Api;

use PhalApi\Api;

/**
 * 用户验证、会话操作类
 * 
 * @author ZhangZijing <i@pluvet.com> 2019-5-15
 */
class Auth extends Api
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
        $base_url = \PhalApi\DI()->urlHelper->baseUrl();
        return array(
            'title' => 'Hello, Moe Auth!',
            'version' => '1.0.0',
            'time' => $_SERVER['REQUEST_TIME'],
            'urls' => array(
                'site_index_url' => $base_url . "/site/index",
            )
        );
    }
}
