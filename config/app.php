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
        //'sign' => ['name' => 'sign', 'require' => true],
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
     * 使用方式可参考: http://www.thinkphp.cn/topic/59345.html https://www.waytomilky.com/archives/340.html
     */
    'auth' => array(
        'auth_on' => true, // 认证开关
        'auth_user' => 'user', // 用户信息表,
        'auth_group' => 'auth_group', // 组数据表名
        'auth_group_access' => 'relationship_auth_group', // 用户-组关系表
        'auth_rule' => 'auth_rule', // 权限规则表
        'auth_not_check_user' => [] //跳过权限检测的用户
    ),
    /**
     * 路由表
     */
    'FastRoute' => array(
        /**
         * 格式：[$method, $routePattern, $handler]
         *
         * @param string/array $method 允许的HTTP请求方式，可以为：GET/POST/HEAD/DELETE 等
         * @param string $routePattern 路由的正则表达式
         * @param string $handler 对应PhalApi中接口服务名称，即：?service=$handler
         */
        'routes' => array(
            ['POST', '/', 'site.index'],

            // auth
            ['POST', '/auth', 'auth.index'],
            ['POST', '/auth/nonce', 'auth.getNonce'],
            ['POST', '/auth/captch/phone', ''],
            ['POST', '/auth/captch/email', 'auth.getCaptchByEmail'],
            ['POST', '/auth/register/phone', ''],
            ['POST', '/auth/register/email', 'auth.registerByEmail'],
            ['POST', '/auth/login/phone', ''],
            ['POST', '/auth/login/email', 'auth.loginByEmail'],
            ['POST', '/auth/refresh', ''],
            ['POST', '/auth/change_password', ''],
            ['POST', '/auth/logout', 'auth.logout'],
            ['POST', '/auth/status', ''],

            // user
            ['POST', '/user/add', ''],
            ['POST', '/user/update', ''],
            ['POST', '/user/remove', ''],
            ['POST', '/user', ''],
            ['POST', '/user/list', ''],
            ['POST', '/user/search', ''],
            ['POST', '/user/followed', ''],
            ['POST', '/user/follower', ''],
            ['POST', '/user/follow', ''],

            // score
            ['POST', '/score/add', 'score.addScore'],
            ['POST', '/score/update', 'score.updateScore'],
            ['POST', '/score/remove', 'score.removeScore'],
            ['POST', '/score', 'score.getScore'],
            ['POST', '/score/list', 'score.getScores'],
            ['POST', '/score/search', 'score.searchScores'],

            ['POST', '/score/performance', ''],
            ['POST', '/score/music', ''],

            // collection
            ['POST', '/collection/add', ''],
            ['POST', '/collection/list', ''],
            ['POST', '/collection/search', ''],
            ['POST', '/collection', ''],
            ['POST', '/collection/update', ''],
            ['POST', '/collection/remove', ''],

            // tag
            ['POST', '/tag/add', ''],
            ['POST', '/tag/list', ''],
            ['POST', '/tag/search', ''],
            ['POST', '/tag', ''],
            ['POST', '/tag/update', ''],
            ['POST', '/tag/remove', ''],

            ['POST', '/tag/attach/add', ''],
            ['POST', '/tag/attach/remove', ''],

            // commment of score
            ['POST', '/score_comment/add    ', ''],
            ['POST', '/score_comment', ''],
            ['POST', '/score_comment/update', ''],
            ['POST', '/score_comment/remove', ''],

            // comment of collection
            ['POST', '/collection_comment/add    ', ''],
            ['POST', '/collection_comment', ''],
            ['POST', '/collection_comment/update', ''],
            ['POST', '/collection_comment/remove', ''],

            // favorite of score
            ['POST', '/score_favorite/add    ', ''],
            ['POST', '/score_favorite/list', ''],
            ['POST', '/score_favorite', ''],
            ['POST', '/score_favorite/update', ''],
            ['POST', '/score_favorite/remove', ''],

            //favorite of collection
            ['POST', '/collection_favorite/add', ''],
            ['POST', '/collection_favorite/list', ''],
            ['POST', '/collection_favorite', ''],
            ['POST', '/collection_favorite/update', ''],
            ['POST', '/collection_favorite/remove', ''],

            // follow
            ['POST', '/follow/add', ''],
            ['POST', '/follow/list', ''],
            ['POST', '/follow/remove', ''],


        ),
        'base_url' => '/project/public'
    ),
    'url' => array(
        'base_url' => '/project/public'
    )
);
