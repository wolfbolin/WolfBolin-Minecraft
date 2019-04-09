<template>
  <div id="app">
    <!--祖传导航栏-->
    <div class="wb-navbar">
      <el-row class="inner">
        <el-col :span="6" class="logo">
          <img src="./assets/image/logo.png"/>
        </el-col>
        <el-col :span="18" hidden-sm-and-up>
          <el-menu
            :default-active="activeIndex"
            mode="horizontal"
            class="menu"
            @select="handleSelect"
            background-color="#404040"
            text-color="#ffffff"
            active-text-color="#0080ff">
            <el-menu-item index="index" class="hidden-xs-only">{{ $t("i18n.index") }}</el-menu-item>
            <el-menu-item index="document" class="hidden-xs-only">{{ $t("i18n.about") }}</el-menu-item>
            <el-menu-item index="data" class="hidden-xs-only">{{ $t("i18n.login") }}</el-menu-item>
            <el-menu-item index="lang" class="hidden-xs-only">{{ $t("i18n.lang") }}</el-menu-item>
            <el-submenu index="box" class="hidden-sm-and-up">
              <template slot="title">菜单</template>
              <el-menu-item index="index" class="hidden-xs-only">{{ $t("i18n.index") }}</el-menu-item>
              <el-menu-item index="document" class="hidden-xs-only">{{ $t("i18n.about") }}</el-menu-item>
              <el-menu-item index="data" class="hidden-xs-only">{{ $t("i18n.login") }}</el-menu-item>
              <el-menu-item index="lang" class="hidden-xs-only">{{ $t("i18n.lang") }}</el-menu-item>
            </el-submenu>
          </el-menu>
        </el-col>
      </el-row>
    </div>
    <!--此处利用路由自动呈现不同的内容-->
    <div class="wb-content">
      <router-view/>
      <vue-canvas-nest :config="{color: '#0000FF', opacity: 0.9, zIndex: -2, count: 300}"></vue-canvas-nest>
    </div>
    <!--统一的页脚-->
    <footer class="wb-footer">
      <p class="copyright">CopyRight © 2018 WolfBolin. All Rights Reserved.</p>
      <a href="http://www.miitbeian.gov.cn" class="icp">湘ICP备17015330号</a>
    </footer>
  </div>
</template>

<script>
  import vueCanvasNest from 'vue-canvas-nest'

  export default {
    name: 'App',
    data() {
      return {
        activeIndex: 'index'
      }
    },
    mounted() {
      this.connectTest();
    },
    methods: {
      handleSelect: function (key) {
        if (key === 'index') {
          this.$router.push('/');
        } else if (key === 'document') {
          this.$router.push('/document');
        } else if (key === 'data') {
          this.$router.push('/data');
        } else if (key === 'lang') {
          this.$i18n.locale = this.$i18n.locale === 'zh-CN' ? 'en-US' : 'zh-CN';
        }
      },
      connectTest: function () {
        let that = this;
        this.$http.get(this.$wb_host).then(function (response) {
          if (response.data.status === 'success') {
            that.$notify.success({
              title: '成功',
              message: response.data.info,
              position: 'bottom-right'
            });
          } else {
            that.$notify({
              title: '警告',
              message: response.data.info,
              type: 'warning',
              position: 'bottom-right'
            });
          }
        }).catch(function (err) {
            that.$notify.error({
              title: '错误',
              message: '您似乎与服务器断开链接',
              position: 'bottom-right'
            });
          }
        );
      }
    },
    components: {
      vueCanvasNest
    }
  }
</script>

<style lang="scss">
  html, body {
    margin: 0;
    padding: 0;
  }

  #app {
    min-width: 720px;
    .wb-navbar {
      font-family: 'FZXY-CUT', sans-serif;
      font-weight: 200;
      background-color: #404040;
      position: fixed;
      z-index: 100;
      height: 60px;
      width: 100%;
      .inner {
        max-width: 1140px;
        margin: 0 auto;
      }
      .logo {
        padding-top: 16px;
        img {
          height: 40px;
        }
      }
      .menu {
        float: right;
      }
      .el-menu--horizontal {
        border-bottom: none;
      }
    }

    .wb-content {
      background: none;
      background-size: cover;
      position: relative;
    }

    .wb-footer {
      background-color: #404040;
      p, a {
        margin: 0;
        display: block;
        text-align: center;
      }
      .copyright {
        color: #888888;
        padding: 10px;
      }
      .icp {
        color: #666666;
        padding-bottom: 5px;
      }
    }
  }

</style>
