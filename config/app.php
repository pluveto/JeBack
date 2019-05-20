<?php
/**
 * 请在下面放置任何您需要的应用配置
 * Pluveto注: 密钥配置, 请放到 je.php, 该文件属于 .gitignore
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
        'auth.registerByEmail',

        'favorite.listFavoriteScore',
        'favorite.listFavoriteCollection',
    ),
    /**
     * Auth 模块
     * 使用方式可参考: 
     *  http://www.thinkphp.cn/topic/59345.html 
     *  https://www.waytomilky.com/archives/340.html
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

            // 1.auth
            [['POST', 'GET'], '/auth/nonce', 'auth.getNonce'],
            ['POST', '/auth', 'auth.index'],
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

            // 2. user
            ['POST', '/user/add', ''],
            ['POST', '/user/update', ''],
            ['POST', '/user/remove', ''],
            ['POST', '/user', ''],
            ['POST', '/user/list', ''],
            ['POST', '/user/search', ''],

            // 3. score
            ['POST', '/score/add', 'score.addScore'],
            ['POST', '/score/update', 'score.updateScore'],
            ['POST', '/score/remove', 'score.removeScore'],
            ['POST', '/score', 'score.getScore'],
            ['POST', '/score/list', 'score.getScoreList'],
            ['POST', '/score/search', 'score.searchScore'],

            ['POST', '/score/performance', ''],
            ['POST', '/score/music', ''],

            // 4. collection
            ['POST', '/collection/add', ''],
            ['POST', '/collection/list', ''],
            ['POST', '/collection/search', ''],
            ['POST', '/collection', ''],
            ['POST', '/collection/update', ''],
            ['POST', '/collection/remove', ''],

            // 5. tag
            ['POST', '/tag/add', ''],
            ['POST', '/tag/list', ''],
            ['POST', '/tag/search', ''],
            ['POST', '/tag', ''],
            ['POST', '/tag/update', ''],
            ['POST', '/tag/remove', ''],

            ['POST', '/tag/attach/add', ''],
            ['POST', '/tag/attach/remove', ''],

            // 6. commment
            ['POST', '/comment/reply', 'comment.addCommentOnComment'],
            ['POST', '/comment/remove', 'comment.removeComment'],

            // 7. comment of ...
            ['POST', '/comment/score/add', 'comment.addCommentOnScore'],
            ['POST', '/comment/score', 'comment.getCommentsOnScore'],


            ['POST', '/comment/collection/add', 'comment.addCommentOnCollection'],
            ['POST', '/comment/collection', 'comment.getCommentOnCollection'],

            // 8. favorite of score
            ['POST', '/favorite/score/add', 'favorite.addFavoriteScore'],
            [['POST', 'GET'], '/favorite/score/list', 'favorite.listFavoriteScore'],
            ['POST', '/favorite/score/remove', 'favorite.removeFavoriteScore'],

            // 9. favorite of collection
            ['POST', '/favorite/colletion/add', 'favorite.addFavoriteCollection'],
            [['POST', 'GET'], '/favorite/colletion/list', 'favorite.listFavoriteCollection'],
            ['POST', '/favorite/colletion/remove', 'favorite.removeFavoriteCollection'],

            // 10. follow
            ['POST', '/follow/list/follwing', 'follow.getFollowingByUser'],
            ['POST', '/follow/list/follower', 'follow.getFollowerByUser'],
            ['POST', '/follow/add', 'follow.addFollow'],
            ['POST', '/follow/remove', 'follow.removeFollow'],
            ['POST', '/follow/following', 'follow.getFollowing'],
            ['POST', '/follow/follower', 'follow.getFollower'],

            // 11. upload
            ['POST', '/upload/image', 'upload.uploadImage'],


        ),
        'base_url' => '/project/public'
    ),
    'url' => array(
        'base_url' => '/project/public'
    )
);
