# JeScoreLibrary Api 中间件 (Bipubipu API 后端)

**如果您要参与开发, 请务必阅读 [指南](https://github.com/pluveto/JeBack/wiki)**





本系统提供曲谱分享系统系列接口。

请求流程：

* 接口需要验证？//已变, 待更新
    * 是：
        1. 向登录接口登录，成功后返回 `token`
        2. 对 `token` 和参数排序，用摘要算法计算出 `signature`
        3. 带上 `signature` 进行请求
    * 否：
        1. 直接访问

为了更加安全，可考虑将来使用 OAuth2 的隐式验证。

* 验证
    * 发送验证码                /auth/captch/phone
    * 发送验证邮件              /auth/captch/email
    * 手机注册                  /auth/register/phone
    * 邮箱注册                  /auth/register/email
    * 检查手机可用性            /auth/available/phone
    * 检查邮箱可用性            /auth/available/email
    * 检查用户名可用性          /auth/available/username
    * 手机登录                  /auth/login/phone
    * 邮箱登录                  /auth/login/email
    * 刷新登录                  /auth/refresh
    * 修改密码                  /auth/change_password
    * 退出登录                  /auth/logout
    * 获取登录状态              /auth/status

对于资源，前几个为基本操作。后几个是从基本操作中抽离的操作。

* 用户
    * 创建一个用户              /user/add
    * 编辑一个用户              /user/update
    * 删除一个用户              /user/remove
    * 获取指定用户              /user
    * 列出一些用户              /user/list
    * 搜索一些用户              /user/search

    * 获取关注者                /user/followed
    * 获取粉丝                  /user/follower
    * 关注、取消关注用户        /user/follow
    
* 曲谱
    * 创建一个曲谱              /score/add
    * 编辑一个曲谱              /score/update
    * 删除一个曲谱              /score/remove
    * 获取指定曲谱              /score
    * 列出一些曲谱              /score/list
    * 搜索一些曲谱              /score/search

    * 获取曲谱关联演奏          /score/performance
    * 获取曲谱关联音乐          /score/music
    * 获取收藏此曲谱的用户      使用用户 api
    * 获取收录该曲谱的谱册      使用谱册 api


* 谱册
    * 创建一个谱册              /collection/add
    * 列出一些谱册              /collection/list
    * 搜索一些谱册              /collection/search
    * 获取一个谱册              /collection
    * 编辑一个谱册              /collection/update
    * 删除一个谱册              /collection/remove

* 标签
    * 创建一个标签              /tag/add
    * 列出一些标签              /tag/list
    * 搜索一些标签              /tag/search
    * 获取一个标签              /tag
    * 编辑一个标签              /tag/update
    * 删除一个标签              /tag/remove

    * 创建标签与谱册的关联      /tag/attach/add
    * 移除标签与谱册的关联      /tag/attach/remove

* 曲谱的评论
    * 创建一个评论              /score_comment/add    
    * 获取一个评论              /score_comment
    * 编辑一个评论              /score_comment/update
    * 删除一个评论              /score_comment/remove

* 谱册的评论
    * 创建一个评论              /collection_comment/add    
    * 获取一个评论              /collection_comment
    * 编辑一个评论              /collection_comment/update
    * 删除一个评论              /collection_comment/remove

* 曲谱的收藏
    * 创建一个收藏              /score_favorite/add    
    * 列出一些收藏              /score_favorite/list
    * 获取一个收藏              /score_favorite
    * 编辑一个收藏              /score_favorite/update
    * 删除一个收藏              /score_favorite/remove

* 谱册的收藏
    * 创建一个收藏              /collection_favorite/add    
    * 列出一些收藏              /collection_favorite/list
    * 获取一个收藏              /collection_favorite
    * 编辑一个收藏              /collection_favorite/update
    * 删除一个收藏              /collection_favorite/remove

* 关注列表
    * 创建一个关注              /follow/add
    * 列出一些关注              /follow/list    
    * 删除一个收藏              /follow/remove

```