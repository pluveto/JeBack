define({ "api": [
  {
    "type": "post",
    "url": "/auth/captch/email",
    "title": "发送邮件验证码",
    "description": "<p>不检查邮箱存在性. 已注册的用户也可以接收验证码. 适用于注册/异常登录验证/找回密码/更改密码/注销.</p>",
    "version": "2.0.0",
    "permission": [
      {
        "name": "none"
      }
    ],
    "name": "getCaptchByEmail",
    "group": "Auth",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "email",
            "description": "<p>邮箱地址.</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "成功响应:",
          "content": "{\n    \"ret\": 200,\n    \"data\": {},\n    \"msg\": \"\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "src/app/Api/Auth.php",
    "groupTitle": "Auth"
  },
  {
    "type": "post",
    "url": "/auth/nonce",
    "title": "获取 nonce",
    "name": "getNonce",
    "group": "Auth",
    "version": "2.0.0",
    "permission": [
      {
        "name": "none"
      }
    ],
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "nonce",
            "description": "<p>获得的随机串.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功响应:",
          "content": "{\n    \"ret\": 200,\n    \"data\": {\n        \"nonce\": \"ffc0f836ee89e15eec0441a4ba94e92ee0ff1560\"\n    },\n    \"msg\": \"\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "src/app/Api/Auth.php",
    "groupTitle": "Auth"
  },
  {
    "type": "post",
    "url": "/auth/login/email",
    "title": "通过邮箱登录",
    "version": "2.0.0",
    "permission": [
      {
        "name": "none"
      }
    ],
    "name": "loginByEmail",
    "group": "Auth",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "email",
            "description": "<p>邮箱地址.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "nonce",
            "description": "<p>随机串.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "sign",
            "description": "<p>登录签名, 算法为 sign = sha1(nonce + email + sha1('moeje' + password)).</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>有效期为30天(注销自动过期)的token.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功响应:",
          "content": "{\n    \"ret\": 200,\n    \"data\": {\n        \"token\": \"9cf536625a810f538dfae11d321a0c987db63bd4\"\n    },\n    \"msg\": \"\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "src/app/Api/Auth.php",
    "groupTitle": "Auth"
  },
  {
    "type": "post",
    "url": "/auth/logout",
    "title": "用户退出登录",
    "description": "<p>退出登录, 并清除登录凭据(token).</p>",
    "version": "2.0.0",
    "name": "logout",
    "permission": [
      {
        "name": "user"
      }
    ],
    "group": "Auth",
    "success": {
      "examples": [
        {
          "title": "成功响应:",
          "content": "{\n    \"ret\": 200,\n    \"data\": {},\n    \"msg\": \"\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "src/app/Api/Auth.php",
    "groupTitle": "Auth"
  },
  {
    "type": "post",
    "url": "/auth/register/email",
    "title": "用户邮箱注册",
    "description": "<p>注册时, 请求体将不可避免地用明文传参.所以建议开启SSL.</p>",
    "version": "2.0.0",
    "permission": [
      {
        "name": "none"
      }
    ],
    "name": "registerByEmail",
    "group": "Auth",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "username",
            "description": "<p>用户名.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "email",
            "description": "<p>用户邮箱.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "captch",
            "description": "<p>验证码.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "password",
            "description": "<p>密码.</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "成功响应:",
          "content": "{\n    \"ret\": 200,\n    \"data\": {},\n    \"msg\": \"\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "src/app/Api/Auth.php",
    "groupTitle": "Auth"
  },
  {
    "type": "post",
    "url": "/collection/add",
    "title": "创建谱册",
    "description": "<p>创建谱册.</p>",
    "version": "2.0.0",
    "name": "addCollection",
    "permission": [
      {
        "name": "user"
      }
    ],
    "group": "Collection",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": true,
            "field": "temp_image_id",
            "description": "<p>临时配图id.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Integer",
            "optional": false,
            "field": "id",
            "description": "<p>创建的谱册的id.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功响应:",
          "content": "{\n    \"ret\": 200,\n    \"data\": {\n        \"id\": 32\n    },\n    \"msg\": \"\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "src/app/Api/Collection.php",
    "groupTitle": "Collection"
  },
  {
    "type": "post",
    "url": "/comment/collection/add",
    "title": "评论谱册",
    "description": "<p>评论谱册.</p>",
    "version": "2.0.0",
    "name": "addCommentOnCollection",
    "permission": [
      {
        "name": "user"
      }
    ],
    "group": "Comment",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "collection_id",
            "description": "<p>要评论的谱册的id.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "text",
            "description": "<p>回复内容.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Integer",
            "optional": false,
            "field": "id",
            "description": "<p>发表的评论的id.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功响应:",
          "content": "{\n    \"ret\": 200,\n    \"data\": {\n        \"id\": 32\n    },\n    \"msg\": \"\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "src/app/Api/Comment.php",
    "groupTitle": "Comment"
  },
  {
    "type": "post",
    "url": "/comment/replay",
    "title": "回复评论",
    "description": "<p>回复评论.</p>",
    "version": "2.0.0",
    "name": "addCommentOnComment",
    "permission": [
      {
        "name": "user"
      }
    ],
    "group": "Comment",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "comment_id",
            "description": "<p>要回复的评论的id.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "text",
            "description": "<p>回复内容.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Integer",
            "optional": false,
            "field": "id",
            "description": "<p>发表的评论的id.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功响应:",
          "content": "{\n    \"ret\": 200,\n    \"data\": {\n        \"id\": 32\n    },\n    \"msg\": \"\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "src/app/Api/Comment.php",
    "groupTitle": "Comment"
  },
  {
    "type": "post",
    "url": "/comment/score/add",
    "title": "评论曲谱",
    "description": "<p>评论曲谱.</p>",
    "version": "2.0.0",
    "name": "addCommentOnScore",
    "permission": [
      {
        "name": "user"
      }
    ],
    "group": "Comment",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "score_id",
            "description": "<p>要评论的曲谱的id.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "text",
            "description": "<p>回复内容.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Integer",
            "optional": false,
            "field": "id",
            "description": "<p>发表的评论的id.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功响应:",
          "content": "{\n    \"ret\": 200,\n    \"data\": {\n        \"id\": 32\n    },\n    \"msg\": \"\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "src/app/Api/Comment.php",
    "groupTitle": "Comment"
  },
  {
    "type": "post",
    "url": "/comment/remove",
    "title": "删除评论",
    "description": "<p>删除评论(目前不许删除有子评论的评论).</p>",
    "version": "2.0.0",
    "name": "name",
    "permission": [
      {
        "name": "user"
      }
    ],
    "group": "Comment",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "comment_id",
            "description": "<p>所要删除的评论的id.</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "成功响应:",
          "content": "{\n    \"ret\": 200,\n    \"data\": {},\n    \"msg\": \"\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "src/app/Api/Comment.php",
    "groupTitle": "Comment"
  },
  {
    "type": "post",
    "url": "/comment/score/list",
    "title": "获取对曲谱的评论",
    "description": "<p>获取对曲谱的评论.</p>",
    "version": "2.0.0",
    "name": "name",
    "permission": [
      {
        "name": "user"
      }
    ],
    "group": "Comment",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "score_id",
            "description": "<p>曲谱id.</p>"
          },
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": true,
            "field": "page",
            "description": "<p>页码.</p>"
          },
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": true,
            "field": "perpage",
            "description": "<p>每页数量.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": ".",
            "description": ""
          }
        ]
      },
      "examples": [
        {
          "title": "成功响应:",
          "content": "{}",
          "type": "json"
        }
      ]
    },
    "filename": "src/app/Api/Comment.php",
    "groupTitle": "Comment"
  }
] });
