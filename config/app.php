<?php
/**
 * 请在下面放置任何您需要的应用配置
 *
 * @license     http://www.phalapi.net/license GPL 协议
 * @link        http://www.phalapi.net/
 * @author dogstar <chanzonghuang@gmail.com> 2017-07-13
 */

return array(

    /**
     * 应用接口层的统一参数
     */
    'apiCommonRules' => array(
        //'sign' => array('name' => 'sign', 'require' => true),
    ),

    /**
     * 此处填写公共接口, 任何人都能自由访问
     * 
     * 对于用户组等, 我们使用 Auth 模块进行更精细的验证。
     * 
     * 接口服务白名单，格式：接口服务类名.接口服务方法名
     *
     * 示例：
     * - *.*         通配，全部接口服务，慎用！
     * - Site.*      Api_Default接口类的全部方法
     * - *.Index     全部接口类的Index方法
     * - Site.Index  指定某个接口服务，即Api_Default::Index()
     */
    'service_whitelist' => array(
        'site.index',
        'auth.getNonce',
        'auth.getCaptchByEmail',
        'auth.loginByEmail',
        'auth.registerByEmail'
    ),
    /**
     * Auth 模块
     */
    'auth' => array(
        'auth_on' => true, // 认证开关
        'auth_user' => 'user', // 用户信息表,
        'auth_group' => 'auth_group', // 组数据表名
        'auth_group_access' => 'auth_group_access', // 用户-组关系表
        'auth_rule' => 'auth_rule', // 权限规则表
        'auth_not_check_user' => array() //跳过权限检测的用户
    ),
    /**
     * 扩展类库 - 快速路由配置
     */
    'FastRoute' => array(
        /**
         * 格式：array($method, $routePattern, $handler)
         *
         * @param string/array $method 允许的HTTP请求方式，可以为：GET/POST/HEAD/DELETE 等
         * @param string $routePattern 路由的正则表达式
         * @param string $handler 对应PhalApi中接口服务名称，即：?service=$handler
         */
        'routes' => array(
            array('POST', '/', 'site.index'),
            // auth
            array('POST', '/auth', 'auth.index'),
            array('POST', '/auth/nonce', 'auth.getNonce'),
            array('POST', '/auth/captch/phone', ''),
            array('POST', '/auth/captch/email', 'auth.getCaptchByEmail'),
            array('POST', '/auth/register/phone', ''),
            array('POST', '/auth/register/email', 'auth.registerByEmail'),
            array('POST', '/auth/login/phone', ''),
            array('POST', '/auth/login/email', 'auth.loginByEmail'),
            array('POST', '/auth/refresh', ''),
            array('POST', '/auth/change_password', ''),
            array('POST', '/auth/logout', 'auth.logout'),
            array('POST', '/auth/status', ''),

            // user
            array('POST', '/user/add', ''),
            array('POST', '/user/update', ''),
            array('POST', '/user/remove', ''),
            array('POST', '/user', ''),
            array('POST', '/user/list', ''),
            array('POST', '/user/search', ''),
            array('POST', '/user/followed', ''),
            array('POST', '/user/follower', ''),
            array('POST', '/user/follow', ''),

            // score
            array('POST', '/score/add', ''),
            array('POST', '/score/update', ''),
            array('POST', '/score/remove', ''),
            array('POST', '/score', ''),
            array('POST', '/score/list', ''),
            array('POST', '/score/search', ''),

            array('POST', '/score/performance', ''),
            array('POST', '/score/music', ''),

            // collection
            array('POST', '/collection/add', ''),
            array('POST', '/collection/list', ''),
            array('POST', '/collection/search', ''),
            array('POST', '/collection', ''),
            array('POST', '/collection/update', ''),
            array('POST', '/collection/remove', ''),

            // tag
            array('POST', '/tag/add', ''),
            array('POST', '/tag/list', ''),
            array('POST', '/tag/search', ''),
            array('POST', '/tag', ''),
            array('POST', '/tag/update', ''),
            array('POST', '/tag/remove', ''),

            array('POST', '/tag/attach/add', ''),
            array('POST', '/tag/attach/remove', ''),

            // commment of score
            array('POST', '/score_comment/add    ', ''),
            array('POST', '/score_comment', ''),
            array('POST', '/score_comment/update', ''),
            array('POST', '/score_comment/remove', ''),

            // comment of collection
            array('POST', '/collection_comment/add    ', ''),
            array('POST', '/collection_comment', ''),
            array('POST', '/collection_comment/update', ''),
            array('POST', '/collection_comment/remove', ''),

            // favorite of score
            array('POST', '/score_favorite/add    ', ''),
            array('POST', '/score_favorite/list', ''),
            array('POST', '/score_favorite', ''),
            array('POST', '/score_favorite/update', ''),
            array('POST', '/score_favorite/remove', ''),

            //favorite of collection
            array('POST', '/collection_favorite/add', ''),
            array('POST', '/collection_favorite/list', ''),
            array('POST', '/collection_favorite', ''),
            array('POST', '/collection_favorite/update', ''),
            array('POST', '/collection_favorite/remove', ''),

            // follow
            array('POST', '/follow/add', ''),
            array('POST', '/follow/list', ''),
            array('POST', '/follow/remove', ''),


        ),
        'base_url' => '/project/public'
    ),
    'url' => array(
        'base_url' => '/project/public'
    )
);
