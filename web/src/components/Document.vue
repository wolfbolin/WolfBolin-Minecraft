<template>
  <div id="document-page">
    <section class="wb-headline">
      <div class="inner">
        <h1>使用文档</h1>
        <p>私有世界接入方案</p>
      </div>
    </section>
    <section class="wb-content">
      <div class="inner">
        <el-card class="doc-card">
          <div slot="header" class="clearfix">
            <span>第一步：申请白名单</span>
          </div>
          <p>步骤内容描述</p>
        </el-card>
        <el-card class="doc-card">
          <div slot="header" class="clearfix">
            <span>第二步：下载配套软件</span>
          </div>
          <p>步骤内容描述</p>
        </el-card>
        <el-card class="doc-card">
          <div slot="header" class="clearfix">
            <span>第三步：安装Java与插件</span>
          </div>
          <p>步骤内容描述</p>
        </el-card>
        <el-card class="doc-card">
          <div slot="header" class="clearfix">
            <span>第四步：连接到服务器</span>
          </div>
          <p>步骤内容描述</p>
        </el-card>
      </div>
    </section>
  </div>
</template>

<script>
  export default {
    name: "Document"
  }
</script>

<style scoped lang="scss">
  #document-page {
    .inner {
      max-width: 1140px;
      margin: 0 auto;
    }

    .wb-headline {
      padding: 80px 0 20px 0;
      h1 {
        font-size: 36px;
      }
      p {
        color: #404040;
      }
    }

    .wb-content {
      .doc-card {
        margin: 20px 0;
      }
    }
  }
</style>
