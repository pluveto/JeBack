# JeScoreLibrary Api 中间件

本系统提供曲谱分享系统系列接口。

请求流程：

* 接口需要验证？
    * 是：
        1. 向登录接口登录，成功后返回 `token`
        2. 对 `token` 和参数排序，用摘要算法计算出 `signature`
        3. 带上 `signature` 进行请求
    * 否：
        1. 直接访问

为了更加安全，可考虑将来使用 OAuth2 的隐式验证。

* 验证
    * 发送验证码               /auth/captch/phone
    * 发送验证邮件             /auth/captch/email
    * 手机注册                 /auth/register/phone
    * 邮箱注册                 /auth/register/email
    * 手机登录                 /auth/login/phone
    * 邮箱登录                 /auth/login/email
    * 刷新登录                 /auth/refresh
    * 修改密码                 /auth/change_password
    * 退出登录                 /auth/logout
    * 获取登录状态             /auth/status

对于资源，前几个为基本操作。后几个是从基本操作中抽离的操作。

* 用户
    * 创建一个用户             /user/add
    * 编辑一个用户             /user/update
    * 删除一个用户             /user/remove
    * 获取指定用户             /user/:id
    * 列出一些用户             /user/list
    * 搜索一些用户             /user/search

    * 获取关注者               /user/followed
    * 获取粉丝                 /user/follower
    * 关注、取消关注用户       /user/follow
    
* 曲谱
    * 创建一个曲谱             /score/add
    * 编辑一个曲谱             /score/update
    * 删除一个曲谱             /score/remove
    * 获取指定曲谱             /score/:id
    * 列出一些曲谱             /score/list
    * 搜索一些曲谱             /score/search

* 谱册
    * 创建一个谱册             /collection/add
    * 列出一些谱册             /collection/list
    * 搜索一些谱册             /collection/search
    * 获取一个谱册             /collection/:id
    * 编辑一个谱册             /collection/update
    * 删除一个谱册             /collection/remove

* 标签
    * 创建一个标签             /tag/add
    * 列出一些标签             /tag/list
    * 搜索一些标签             /tag/search
    * 获取一个标签             /tag/:id
    * 编辑一个标签             /tag/update
    * 删除一个标签             /tag/remove

    * 创建标签与谱册的关联     /tag/attach/add
    * 移除标签与谱册的关联     /tag/attach/remove

* 曲谱的评论
    * 创建一个评论             /score_comment/add    
    * 获取一个评论             /score_comment/:id
    * 编辑一个评论             /score_comment/update
    * 删除一个评论             /score_comment/remove

* 谱册的评论
    * 创建一个评论             /collection_comment/add    
    * 获取一个评论             /collection_comment/:id
    * 编辑一个评论             /collection_comment/update
    * 删除一个评论             /collection_comment/remove

* 曲谱的收藏
    * 创建一个收藏             /score_favorite/add    
    * 列出一些收藏             /score_favorite/list
    * 获取一个收藏             /score_favorite/:id
    * 编辑一个收藏             /score_favorite/update
    * 删除一个收藏             /score_favorite/remove

* 谱册的收藏
    * 创建一个收藏             /collection_favorite/add    
    * 列出一些收藏             /collection_favorite/list
    * 获取一个收藏             /collection_favorite/:id
    * 编辑一个收藏             /collection_favorite/update
    * 删除一个收藏             /collection_favorite/remove

* 关注列表
    * 创建一个关注             /follow/add
    * 列出一些关注             /follow/list    
    * 删除一个收藏             /follow/remove

```