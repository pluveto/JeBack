<?php

namespace App\Common;

/**
 * 该类用于自定义返回格式接口。
 *  - 重写后，请求地址变成形如 `/index.php?r=Namespace/Class/Action` 
 *  - 参考：http://docs.phalapi.net/#/v2.0/how-to-request
 * 
 * 提示：通过 Apache 或 Nginx 的进一步重写，可变成形如 `/Namespace/Class/Action`
 * 
 * @author ZhangZijing <i@pluvet.com> 2019-5-14
 */
class Request extends \PhalApi\Request
{

    public function getService()
    {
        // 优先返回自定义格式的接口服务名称
        $service = $this->get('r');
        if (!empty($service)) {
            $namespace = count(explode('/', $service)) == 2 ? 'App.' : '';
            return $namespace . str_replace('/', '.', $service);
        }

        return parent::getService();
    }
}
