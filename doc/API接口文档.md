# API接口文档

## 数据备份

### 获取备份列表

* 方法：GET

* 路径：/back/list

* 请求参数：无

* 请求示例：

  ```
  {{host}}/backup/list
  ```

* 响应参数：有

* 响应示例：

  ```json
  {
      "status": "success",
      "info": {
          "msg": "数据获取成功",
          "count": 1
      },
      "data": [
          {
              "name": "world-1553801467.zip",
              "time": "2019-03-28 19:31:09",
              "size": 0,
              "class": "standard"
          }
      ]
  }
  ```

  ### 添加实时备份

* 方法：GET

* 路径：/back/add

* 请求参数：无

* 请求示例：

  ```
  {{host}}/backup/add
  ```

* 响应参数：有

* 响应示例：

  ```json
  {
      "status": "success",
      "ETag": "af2a54d47f25a38bdcdc9b05c3013919",
      "URL": "https://backup-1253362441.cos.ap-chengdu.myqcloud.com/Minecraft/world/world-1553801467.zip",
      "file_name": "world-1553801467.zip"
  }
  ```

