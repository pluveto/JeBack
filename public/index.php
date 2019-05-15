<?php
/**
 * 统一访问入口
 */

require_once dirname(__FILE__) . '/init.php';

//显式初始化，并调用分发
\PhalApi\DI()->fastRoute = new PhalApi\FastRoute\Lite();
\PhalApi\DI()->fastRoute->dispatch();

$pai = new \PhalApi\PhalApi();
$pai->response()->output();
