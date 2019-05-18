<?php
/**
 * DI依赖注入配置文件
 * 
 * @license     http://www.phalapi.net/license GPL 协议
 * @link        http://www.phalapi.net/
 * @author      dogstar <chanzonghuang@gmail.com> 2017-07-13
 */

use PhalApi\Loader;
use PhalApi\Config\FileConfig;
use PhalApi\Logger;
use PhalApi\Logger\FileLogger;
use PhalApi\Database\NotORMDatabase;

/** ---------------- 基本注册 必要服务组件 ---------------- **/

$di = \PhalApi\DI();

// 配置
$di->config = new FileConfig(API_ROOT . '/config');

// 调试模式，$_GET['__debug__']可自行改名
$di->debug = !empty($_GET['__debug__']) ? true : $di->config->get('sys.debug');

// 日记纪录
$di->logger = new FileLogger(API_ROOT . '/runtime', Logger::LOG_LEVEL_DEBUG | Logger::LOG_LEVEL_INFO | Logger::LOG_LEVEL_ERROR);

// 数据操作 - 基于NotORM（注：支持多种数据库，默认使用 MySQL）
$di->notorm = new NotORMDatabase($di->config->get('dbs'), $di->debug);

// JSON中文输出
$di->response = new \PhalApi\Response\JsonResponse(JSON_UNESCAPED_UNICODE);

/** ---------------- 定制注册 可选服务组件 ---------------- **/
// 路由模块在 index.php 中已经预先注入。路由的配置见 config/app.php


// auth 验证模块, 与自定义过滤器协作才会生效
$di->authLite = new \Phalapi\Auth\Lite();

// 自定义过滤器, 进行权限过滤
$di->filter = new \App\Common\SignFilter();

// 重写地址，详见 `App\Common\Request` 的定义。
$di->request = new App\Common\Request();

// 系统邮件帮助类
// $di->sysEmailHelper = new App\Helper\SysEmail\Lite();



// session 工具
$di->session = new \PhalApi\Session\Lite();

// 签名验证服务
// $di->filter = new \PhalApi\Filter\SimpleMD5Filter();

// 缓存 - Memcache/Memcached
// $di->cache = function () {
//     return new \PhalApi\Cache\MemcacheCache(\PhalApi\DI()->config->get('sys.mc'));
// };

// 支持JsonP的返回
// if (!empty($_GET['callback'])) {
//     $di->response = new \PhalApi\Response\JsonpResponse($_GET['callback']);
// }

// 生成二维码扩展，参考示例：?s=App.Examples_QrCode.Png
// $di->qrcode = function() {
//     return new \PhalApi\QrCode\Lite();
// };
