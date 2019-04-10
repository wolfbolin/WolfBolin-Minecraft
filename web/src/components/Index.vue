<template>
  <div id="index-page">
    <section class="wb-cover">
      <div class="inner" ref="cover">
        <el-carousel :height="cover_height">
          <el-carousel-item v-for="item in cover_list" :key="item">
            <img :src="'../../static/image/' + item"/>
          </el-carousel-item>
        </el-carousel>
      </div>
    </section>
    <section class="wb-section">
      <div class="inner">
        <div class="title">
          <p>您好</p>
          <h1>欢迎来到我的世界</h1>
        </div>
        <el-row :gutter="40">
          <el-col class="item" :xs="24" :sm="12">
            <h2>这是什么？</h2>
            <p>这是一个私有的“我的世界”服务器，哦这是一个私有的“我的世界”服务器，哦这是一个私有的“我的世界”服务器，哦
              这是一个私有的“我的世界”服务器，哦这是一个私有的“我的世界”服务器，哦这是一个私有的“我的世界”服务器，哦
              这是一个私有的“我的世界”服务器，哦这是一个私有的“我的世界”服务器，哦这是一个私有的“我的世界”服务器，哦
              这是一个私有的“我的世界”服务器，哦这是一个私有的“我的世界”服务器，哦
            </p>
          </el-col>
          <el-col class="item" :xs="24" :sm="12">
            <h2>如何加入？</h2>
            <p>你就申请就行了你就申请就行了你就申请就行了你就申请就行了你就申请就行了你就申请就行了你就申请就行了
              你就申请就行了你就申请就行了你就申请就行了你就申请就行了你就申请就行了你就申请就行了你就申请就行了
              你就申请就行了你就申请就行了你就申请就行了你就申请就行了你就申请就行了你就申请就行了你就申请就行了
              你就申请就行了你就申请就行了你就申请就行了你就申请就行了你就申请就行了你就申请就行了你就申请就行了
            </p>
          </el-col>
        </el-row>
      </div>
    </section>
    <section class="wb-section">
      <div class="inner">
        <div class="title">
          <p>时间线</p>
          <h1>服务已连续运行</h1>
        </div>

        <div class="wb-timeline">
          <el-card v-for="(num, index) in time_list" :key="index" class="wb-num">{{ num }}</el-card>
          <el-card class="wb-num">小时</el-card>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
  export default {
    name: "Index",
    data() {
      return {
        timer: null,
        cover_height: '0px',
        cover_list: ['pic1.png', 'pic2.png', 'pic3.png', 'pic4.png', 'pic5.png',
          'pic6.png', 'pic7.png', 'pic8.png', 'pic9.png'],
        start_time: 1546272000,
        time_list: [0, 0, 0, 1, 2]
      }
    },
    mounted() {
      this.setCarouselHeight();
      this.getStartTime();
      clearInterval(this.timer);
      this.setTimer();
    },
    beforeDestroy() {
      clearInterval(this.timer);
    },
    methods: {
      setCarouselHeight: function () {
        // let clientWidth = document.documentElement.clientWidth;
        let coverWidth = this.$refs.cover.clientWidth;
        let coverHeight = coverWidth * 0.5625;
        this.cover_height = `${coverHeight}px`;
      },
      getStartTime: function () {
        let that = this;
        this.$http.get(this.$wb_host + '/info/time').then(function (response) {
          if (response.data.status === 'success') {
            that.start_time = response.data.start_time;
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
      },
      setTimer: function () {
        this.timer = setInterval(() => {
          let dt = Math.round(new Date().getTime() / 1000) - this.start_time;
          dt = parseInt(dt / 3600);
          this.time_list = [];
          while (dt > 0) {
            this.time_list.unshift(dt % 10);
            dt = parseInt(dt / 10);
          }
        }, 1000)
      }
    }
  }
</script>

<style scoped lang="scss">
  #index-page {
    .inner {
      max-width: 1140px;
      margin: 0 auto;
    }

    .wb-cover {
      padding-top: 100px;
      img {
        width: 100%;
        height: 100%;
      }
    }

    .wb-section {
      padding: 20px 0;
      .title {
        text-align: center;
        padding: 1em;
        p {
          margin: 0;
          padding: 0.5em;
          color: #404040;
          font-weight: 100;
          font-size: 28px;
        }
        h1 {
          margin: 0;
          padding: 0.5em;
          color: #66b1ff;
          font-weight: 400;
          font-size: 38px;
        }
      }

      .item {
        h2 {
          color: #404040;
        }
        p {
          color: #404040;
          line-height: 26px;
        }
        a {
          color: #66b1ff;
          text-decoration: none;
        }
      }

      .wb-timeline {
        text-align: center;
        margin: 60px 0;
        .wb-num {
          display: inline-block;
          margin: 10px;
          padding: 0 10px;
          font-size: 4em;
          font-weight: 600;
        }
      }
    }
  }
</style>
