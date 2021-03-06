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
    "title": "退出登录",
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
    "title": "邮箱注册",
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
  },
  {
    "type": "post",
    "url": "/favorite/collection/add",
    "title": "收藏谱册",
    "description": "<p>收藏谱册.</p>",
    "version": "2.0.0",
    "name": "addFavoriteCollection",
    "permission": [
      {
        "name": "user"
      }
    ],
    "group": "Favorite",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "collection_id",
            "description": "<p>谱册id.</p>"
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
            "description": "<p>该收藏项的id.</p>"
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
    "filename": "src/app/Api/Favorite.php",
    "groupTitle": "Favorite"
  },
  {
    "type": "post",
    "url": "/favorite/score/add",
    "title": "收藏曲谱",
    "description": "<p>收藏曲谱.</p>",
    "version": "2.0.0",
    "name": "addFavoriteScore",
    "permission": [
      {
        "name": "user"
      }
    ],
    "group": "Favorite",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "score_id",
            "description": "<p>曲谱id.</p>"
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
            "description": "<p>该收藏项的id.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功响应:",
          "content": "{\n    \"ret\": 200,\n    \"data\": {\n        \"id\": 52\n    },\n    \"msg\": \"\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "src/app/Api/Favorite.php",
    "groupTitle": "Favorite"
  },
  {
    "type": "post",
    "url": "/favorite/collection/list",
    "title": "获取用户收藏的谱册",
    "description": "<p>列出用户收藏的谱册.</p>",
    "version": "2.0.0",
    "name": "listFavoriteCollection",
    "permission": [
      {
        "name": "none"
      }
    ],
    "group": "Favorite",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "user_id",
            "description": "<p>用户id.</p>"
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
            "type": "Array",
            "optional": false,
            "field": "items",
            "description": "<p>列表数据项.</p>"
          },
          {
            "group": "Success 200",
            "type": "Integer",
            "optional": false,
            "field": "total",
            "description": "<p>列表数据项.</p>"
          },
          {
            "group": "Success 200",
            "type": "Integer",
            "optional": false,
            "field": "page",
            "description": "<p>页码.</p>"
          },
          {
            "group": "Success 200",
            "type": "Integer",
            "optional": false,
            "field": "perpage",
            "description": "<p>每页数量.</p>"
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
    "filename": "src/app/Api/Favorite.php",
    "groupTitle": "Favorite"
  },
  {
    "type": "post",
    "url": "/favorite/score/list",
    "title": "获取用户收藏的曲谱",
    "description": "<p>列出用户收藏的曲谱.</p>",
    "version": "2.0.0",
    "name": "listFavoriteScore",
    "permission": [
      {
        "name": "none"
      }
    ],
    "group": "Favorite",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "user_id",
            "description": "<p>用户id.</p>"
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
            "type": "Array",
            "optional": false,
            "field": "items",
            "description": "<p>列表数据项.</p>"
          },
          {
            "group": "Success 200",
            "type": "Integer",
            "optional": false,
            "field": "total",
            "description": "<p>列表数据项.</p>"
          },
          {
            "group": "Success 200",
            "type": "Integer",
            "optional": false,
            "field": "page",
            "description": "<p>页码.</p>"
          },
          {
            "group": "Success 200",
            "type": "Integer",
            "optional": false,
            "field": "perpage",
            "description": "<p>每页数量.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功响应:",
          "content": "{\n    \"ret\": 200,\n    \"data\": {\n        \"items\": [{\n            \"id\": \"149\",\n            \"title\": \"Test\",\n            \"created_at\": \"1558500661\",\n            \"updated_at\": \"1558500661\",\n            \"anime\": \"单曲\",\n            \"key\": \"C\",\n            \"user_id\": \"1\",\n            \"username\": \"pluvet\"\n        }, {\n            \"id\": \"150\",\n            \"title\": \"Updated Test With Detail\",\n            \"created_at\": \"1558500661\",\n            \"updated_at\": \"1558500662\",\n            \"anime\": \"Updated Anime Name\",\n            \"key\": \"D\",\n            \"addition\": \"Updated Some thing to append on title\",\n            \"user_id\": \"1\",\n            \"username\": \"pluvet\"\n        }, {\n            \"id\": \"147\",\n            \"title\": \"Test\",\n            \"created_at\": \"1558500602\",\n            \"updated_at\": \"1558500602\",\n            \"anime\": \"单曲\",\n            \"key\": \"C\",\n            \"user_id\": \"1\",\n            \"username\": \"pluvet\"\n        }, {\n            \"id\": \"146\",\n            \"title\": \"Test\",\n            \"created_at\": \"1558500560\",\n            \"updated_at\": \"1558500560\",\n            \"anime\": \"单曲\",\n            \"key\": \"C\",\n            \"user_id\": \"1\",\n            \"username\": \"pluvet\"\n        }, {\n            \"id\": \"145\",\n            \"title\": \"Test\",\n            \"created_at\": \"1558500551\",\n            \"updated_at\": \"1558500551\",\n            \"anime\": \"单曲\",\n            \"key\": \"C\",\n            \"user_id\": \"1\",\n            \"username\": \"pluvet\"\n        }, {\n            \"id\": \"144\",\n            \"title\": \"Test\",\n            \"created_at\": \"1558500478\",\n            \"updated_at\": \"1558500478\",\n            \"anime\": \"单曲\",\n            \"key\": \"C\",\n            \"user_id\": \"1\",\n            \"username\": \"pluvet\"\n        }, {\n            \"id\": \"143\",\n            \"title\": \"Test\",\n            \"created_at\": \"1558500430\",\n            \"updated_at\": \"1558500430\",\n            \"anime\": \"单曲\",\n            \"key\": \"C\",\n            \"user_id\": \"1\",\n            \"username\": \"pluvet\"\n        }, {\n            \"id\": \"141\",\n            \"title\": \"Test\",\n            \"created_at\": \"1558500313\",\n            \"updated_at\": \"1558500313\",\n            \"anime\": \"单曲\",\n            \"key\": \"C\",\n            \"user_id\": \"1\",\n            \"username\": \"pluvet\"\n        }],\n        \"total\": 8,\n        \"page\": 1,\n        \"perpage\": 10\n    },\n    \"msg\": \"\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "src/app/Api/Favorite.php",
    "groupTitle": "Favorite"
  },
  {
    "type": "post",
    "url": "/favorite/collection/remove",
    "title": "移除对谱册的收藏",
    "description": "<p>移除对谱册的收藏.</p>",
    "version": "2.0.0",
    "name": "removeFavoriteCollection",
    "permission": [
      {
        "name": "user"
      }
    ],
    "group": "Favorite",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "collection_id",
            "description": "<p>曲谱id.</p>"
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
    "filename": "src/app/Api/Favorite.php",
    "groupTitle": "Favorite"
  },
  {
    "type": "post",
    "url": "/favorite/score/remove",
    "title": "移除对曲谱的收藏",
    "description": "<p>移除对曲谱的收藏.</p>",
    "version": "2.0.0",
    "name": "removeFavoriteScore",
    "permission": [
      {
        "name": "user"
      }
    ],
    "group": "Favorite",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "score_id",
            "description": "<p>曲谱id.</p>"
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
    "filename": "src/app/Api/Favorite.php",
    "groupTitle": "Favorite"
  },
  {
    "type": "post",
    "url": "/score/add",
    "title": "添加曲谱",
    "description": "<p>添加曲谱.</p>",
    "version": "2.0.0",
    "name": "addScore",
    "permission": [
      {
        "name": "user"
      }
    ],
    "group": "Score",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "title",
            "description": "<p>曲谱标题.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "addition",
            "description": "<p>曲谱标题附加信息.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "text",
            "description": "<p>曲谱正文.</p>"
          },
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": true,
            "field": "temp_image_id",
            "defaultValue": "0",
            "description": "<p>临时图片Id, 默认为0表示无配图.</p>"
          },
          {
            "group": "Parameter",
            "type": "Array",
            "optional": true,
            "field": "alias",
            "defaultValue": "[]",
            "description": "<p>别名列表(json纯文本数组), 默认为空数组[].</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "anime",
            "defaultValue": "单曲",
            "description": "<p>出处作品, 不填则默认为'单曲'.</p>"
          },
          {
            "group": "Parameter",
            "type": "Enum",
            "optional": true,
            "field": "key",
            "defaultValue": "C",
            "description": "<p>调性, 默认为'C', 取值范围:</p> <pre><code>        ['C', 'C#', 'D', 'D#', 'E', 'F', 'F#', 'G', 'G#', 'A', 'A#', 'B']</code></pre>"
          },
          {
            "group": "Parameter",
            "type": "Description",
            "optional": true,
            "field": "description",
            "description": "<p>曲谱介绍.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request-Example:",
          "content": "'title': 'Test With Detail',\n'text': '1 2 3 4 5 6 7',\n'temp_image_id': img_id,\n'alias': json.dumps(['Alias 1', 'Alias 2']),\n'anime': 'Anime Name',\n'key': 'C#',\n'type': 0,\n'description': 'Hello, world',\n'addition': 'Some thing to append on title'",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Type",
            "optional": false,
            "field": "field",
            "description": "<p>Field description.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功响应:",
          "content": "{\n    \"ret\": 200,\n    \"data\": {\n        \"id\": 150,\n        \"image_url\": \"http:\\/\\/...\\/uploads\\/images\\/2019\\/05\\/22\\/2b6e9bea199a5cdc8c9f93d59aaabed2.png\"\n    },\n    \"msg\": \"\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "src/app/Api/Score.php",
    "groupTitle": "Score"
  },
  {
    "type": "post",
    "url": "/score",
    "title": "获取曲谱",
    "description": "<p>删除曲谱.</p>",
    "version": "2.0.0",
    "name": "getScore",
    "permission": [
      {
        "name": "none"
      }
    ],
    "group": "Score",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "id",
            "description": "<p>曲谱id.</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "成功响应:",
          "content": "{\n    \"ret\": 200,\n    \"data\": {\n        \"id\": \"150\",\n        \"title\": \"Updated Test With Detail\",\n        \"text\": \"Updated 1 2 3 4 5 6 7\",\n        \"username\": \"pluvet\",\n        \"alias\": [\"Updated\", \"Alias 2\"],\n        \"anime\": \"Updated Anime Name\",\n        \"key\": \"D\",\n        \"description\": \"Updated Hello, world\",\n        \"addition\": \"Updated Some thing to append on title\",\n        \"image_url\": \"http:\\/\\/...\\/uploads\\/images\\/2019\\/05\\/22\\/01b736f5049218567daab1f2411253ba.png\"\n    },\n    \"msg\": \"\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "src/app/Api/Score.php",
    "groupTitle": "Score"
  },
  {
    "type": "post",
    "url": "/score/list",
    "title": "获取曲谱列表",
    "description": "<p>获取曲谱列表, 按发布时间倒序排序.</p>",
    "version": "2.0.0",
    "name": "getScoreList",
    "permission": [
      {
        "name": "none"
      }
    ],
    "group": "Score",
    "parameter": {
      "fields": {
        "Parameter": [
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
            "type": "Array",
            "optional": false,
            "field": "items",
            "description": "<p>列表数据项.</p>"
          },
          {
            "group": "Success 200",
            "type": "Integer",
            "optional": false,
            "field": "total",
            "description": "<p>列表数据项.</p>"
          },
          {
            "group": "Success 200",
            "type": "Integer",
            "optional": false,
            "field": "page",
            "description": "<p>页码.</p>"
          },
          {
            "group": "Success 200",
            "type": "Integer",
            "optional": false,
            "field": "perpage",
            "description": "<p>每页数量.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功响应:",
          "content": "{\n    \"ret\": 200,\n    \"data\": {\n        \"items\": [{\n            \"id\": \"149\",\n            \"title\": \"Test\",\n            \"created_at\": \"1558500661\",\n            \"updated_at\": \"1558500661\",\n            \"anime\": \"单曲\",\n            \"key\": \"C\",\n            \"user_id\": \"1\",\n            \"username\": \"pluvet\"\n        }, {\n            \"id\": \"150\",\n            \"title\": \"Updated Test With Detail\",\n            \"created_at\": \"1558500661\",\n            \"updated_at\": \"1558500662\",\n            \"anime\": \"Updated Anime Name\",\n            \"key\": \"D\",\n            \"addition\": \"Updated Some thing to append on title\",\n            \"user_id\": \"1\",\n            \"username\": \"pluvet\"\n        }, {\n            \"id\": \"147\",\n            \"title\": \"Test\",\n            \"created_at\": \"1558500602\",\n            \"updated_at\": \"1558500602\",\n            \"anime\": \"单曲\",\n            \"key\": \"C\",\n            \"user_id\": \"1\",\n            \"username\": \"pluvet\"\n        }, {\n            \"id\": \"146\",\n            \"title\": \"Test\",\n            \"created_at\": \"1558500560\",\n            \"updated_at\": \"1558500560\",\n            \"anime\": \"单曲\",\n            \"key\": \"C\",\n            \"user_id\": \"1\",\n            \"username\": \"pluvet\"\n        }, {\n            \"id\": \"145\",\n            \"title\": \"Test\",\n            \"created_at\": \"1558500551\",\n            \"updated_at\": \"1558500551\",\n            \"anime\": \"单曲\",\n            \"key\": \"C\",\n            \"user_id\": \"1\",\n            \"username\": \"pluvet\"\n        }, {\n            \"id\": \"144\",\n            \"title\": \"Test\",\n            \"created_at\": \"1558500478\",\n            \"updated_at\": \"1558500478\",\n            \"anime\": \"单曲\",\n            \"key\": \"C\",\n            \"user_id\": \"1\",\n            \"username\": \"pluvet\"\n        }, {\n            \"id\": \"143\",\n            \"title\": \"Test\",\n            \"created_at\": \"1558500430\",\n            \"updated_at\": \"1558500430\",\n            \"anime\": \"单曲\",\n            \"key\": \"C\",\n            \"user_id\": \"1\",\n            \"username\": \"pluvet\"\n        }, {\n            \"id\": \"141\",\n            \"title\": \"Test\",\n            \"created_at\": \"1558500313\",\n            \"updated_at\": \"1558500313\",\n            \"anime\": \"单曲\",\n            \"key\": \"C\",\n            \"user_id\": \"1\",\n            \"username\": \"pluvet\"\n        }],\n        \"total\": 8,\n        \"page\": 1,\n        \"perpage\": 10\n    },\n    \"msg\": \"\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "src/app/Api/Score.php",
    "groupTitle": "Score"
  },
  {
    "type": "post",
    "url": "/score/remove",
    "title": "删除曲谱",
    "description": "<p>删除曲谱.</p>",
    "version": "2.0.0",
    "name": "removeScore",
    "permission": [
      {
        "name": "user"
      }
    ],
    "group": "Score",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "id",
            "description": "<p>曲谱id.</p>"
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
    "filename": "src/app/Api/Score.php",
    "groupTitle": "Score"
  },
  {
    "type": "post",
    "url": "/score/list",
    "title": "搜索曲谱",
    "description": "<p>使用关键字搜索曲谱.</p>",
    "version": "2.0.0",
    "name": "searchScore",
    "permission": [
      {
        "name": "none"
      }
    ],
    "group": "Score",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "keyword",
            "description": "<p>关键字.</p>"
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
            "type": "Array",
            "optional": false,
            "field": "items",
            "description": "<p>列表数据项.</p>"
          },
          {
            "group": "Success 200",
            "type": "Integer",
            "optional": false,
            "field": "total",
            "description": "<p>列表数据项.</p>"
          },
          {
            "group": "Success 200",
            "type": "Integer",
            "optional": false,
            "field": "page",
            "description": "<p>页码.</p>"
          },
          {
            "group": "Success 200",
            "type": "Integer",
            "optional": false,
            "field": "perpage",
            "description": "<p>每页数量.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功响应:",
          "content": "{\n    \"ret\": 200,\n    \"data\": {\n        \"items\": [{\n            \"id\": \"149\",\n            \"title\": \"Test\",\n            \"created_at\": \"1558500661\",\n            \"updated_at\": \"1558500661\",\n            \"anime\": \"单曲\",\n            \"key\": \"C\",\n            \"user_id\": \"1\",\n            \"username\": \"pluvet\"\n        }, {\n            \"id\": \"150\",\n            \"title\": \"Updated Test With Detail\",\n            \"created_at\": \"1558500661\",\n            \"updated_at\": \"1558500662\",\n            \"anime\": \"Updated Anime Name\",\n            \"key\": \"D\",\n            \"addition\": \"Updated Some thing to append on title\",\n            \"user_id\": \"1\",\n            \"username\": \"pluvet\"\n        }, {\n            \"id\": \"147\",\n            \"title\": \"Test\",\n            \"created_at\": \"1558500602\",\n            \"updated_at\": \"1558500602\",\n            \"anime\": \"单曲\",\n            \"key\": \"C\",\n            \"user_id\": \"1\",\n            \"username\": \"pluvet\"\n        }, {\n            \"id\": \"146\",\n            \"title\": \"Test\",\n            \"created_at\": \"1558500560\",\n            \"updated_at\": \"1558500560\",\n            \"anime\": \"单曲\",\n            \"key\": \"C\",\n            \"user_id\": \"1\",\n            \"username\": \"pluvet\"\n        }, {\n            \"id\": \"145\",\n            \"title\": \"Test\",\n            \"created_at\": \"1558500551\",\n            \"updated_at\": \"1558500551\",\n            \"anime\": \"单曲\",\n            \"key\": \"C\",\n            \"user_id\": \"1\",\n            \"username\": \"pluvet\"\n        }, {\n            \"id\": \"144\",\n            \"title\": \"Test\",\n            \"created_at\": \"1558500478\",\n            \"updated_at\": \"1558500478\",\n            \"anime\": \"单曲\",\n            \"key\": \"C\",\n            \"user_id\": \"1\",\n            \"username\": \"pluvet\"\n        }, {\n            \"id\": \"143\",\n            \"title\": \"Test\",\n            \"created_at\": \"1558500430\",\n            \"updated_at\": \"1558500430\",\n            \"anime\": \"单曲\",\n            \"key\": \"C\",\n            \"user_id\": \"1\",\n            \"username\": \"pluvet\"\n        }, {\n            \"id\": \"141\",\n            \"title\": \"Test\",\n            \"created_at\": \"1558500313\",\n            \"updated_at\": \"1558500313\",\n            \"anime\": \"单曲\",\n            \"key\": \"C\",\n            \"user_id\": \"1\",\n            \"username\": \"pluvet\"\n        }],\n        \"total\": 8,\n        \"page\": 1,\n        \"perpage\": 10\n    },\n    \"msg\": \"\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "src/app/Api/Score.php",
    "groupTitle": "Score"
  },
  {
    "type": "post",
    "url": "/score/add",
    "title": "更新曲谱",
    "description": "<p>更新曲谱.</p>",
    "version": "2.0.0",
    "name": "updateScore",
    "permission": [
      {
        "name": "user"
      }
    ],
    "group": "Score",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "id",
            "description": "<p>曲谱id.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "title",
            "description": "<p>曲谱标题.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "addition",
            "description": "<p>曲谱标题附加信息.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "text",
            "description": "<p>曲谱正文.</p>"
          },
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": true,
            "field": "temp_image_id",
            "description": "<p>临时图片Id, 默认为0表示无配图.</p>"
          },
          {
            "group": "Parameter",
            "type": "Array",
            "optional": true,
            "field": "alias",
            "description": "<p>别名列表(json纯文本数组), 默认为空数组[].</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "anime",
            "description": "<p>出处作品, 不填则默认为'单曲'.</p>"
          },
          {
            "group": "Parameter",
            "type": "Enum",
            "optional": true,
            "field": "key",
            "description": "<p>调性, 默认为'C', 取值范围: 'range' =&gt; ['C', 'C#', 'D', 'D#', 'E', 'F', 'F#', 'G', 'G#', 'A', 'A#', 'B']</p>"
          },
          {
            "group": "Parameter",
            "type": "Description",
            "optional": true,
            "field": "description",
            "description": "<p>曲谱介绍.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request-Example:",
          "content": "'title': 'Test With Detail',\n'text': '1 2 3 4 5 6 7',\n'temp_image_id': img_id,\n'alias': json.dumps(['Alias 1', 'Alias 2']),\n'anime': 'Anime Name',\n'key': 'C#',\n'type': 0,\n'description': 'Hello, world',\n'addition': 'Some thing to append on title'",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Type",
            "optional": false,
            "field": "field",
            "description": "<p>Field description.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功响应:",
          "content": "{\n    \"ret\": 200,\n    \"data\": {\n        \"id\": 150,\n        \"image_url\": \"http:\\/\\/...\\/uploads\\/images\\/2019\\/05\\/22\\/01b736f5049218567daab1f2411253ba.png\"\n    },\n    \"msg\": \"\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "src/app/Api/Score.php",
    "groupTitle": "Score"
  }
] });
