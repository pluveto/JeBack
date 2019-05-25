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
