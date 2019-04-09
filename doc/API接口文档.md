# API接口文档

## 数据备份

### 获取备份列表

* 方法：GET

* 路径：`/backup/list`

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
          "count": 2
      },
      "data": [
          {
              "name": "world-1553801467.zip",
              "time": "2019-03-28 19:31:09",
              "size": 0,
              "class": "standard"
          },
          {
              "name": "world-1553801421.zip",
              "time": "2019-03-28 19:33:09",
              "size": 0,
              "class": "standard"
          }
      ]
  }
  ```

### 添加备份


* 方法：POST

* 路径：`/backup/world`

* 请求参数：无

* 请求示例：

  ```
  {{host}}/backup/world
  ```

* 响应参数：有

* 响应示例：

  ```json
  {
      "status": "success",
      "ETag": "af2a54d47f25a38bdcdc9b05c3013919",
      "URL": "https://cos.mc.wolfbolin.com/Minecraft/world/world-1553801467.zip",
      "file_name": "world-1553801467.zip"
  }
  ```

### 下载备份

* 方法：GET

* 路径：`/backup/world-[0-9]{10}`

* 请求参数：无

* 请求示例：

  ```
  {{host}}/backup/world-1553957502
  ```

* 响应参数：有

* 响应示例：

  ```json
  {
      "status": "success",
      "ttl": "1 minutes",
      "url": "https://cos.mc.wolfbolin.com/Minecraft/world/world-1553801467.zip"
  }
  ```

### 删除备份

* 方法：DELETE

* 路径：`/backup/world-[0-9]{10}`

* 请求参数：无

* 请求示例：

  ```
  {{host}}/backup/world-1553957502
  ```

* 响应参数：有

* 响应示例：

  ```json
  {
      "status": "success",
      "operate": "NWNhMGRlMThfNGQ5ZTU4NjRfNmE5Zl8zZWE0NDI="
  }
  ```
