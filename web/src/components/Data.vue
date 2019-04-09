<template>
  <div id="data-page">
    <section class="wb-headline">
      <div class="inner">
        <h1>运行数据</h1>
        <p>私有世界数据统计</p>
      </div>
    </section>
    <section class="wb-content">
      <div class="inner">
        <el-card class="card">
          <div slot="header" class="clearfix">
            <span>内存用量</span>
          </div>
          <div class="chart">
            <v-chart :options="memory_graph" ref="echart"></v-chart>
          </div>
        </el-card>
        <el-card class="card">
          <div slot="header" class="clearfix">
            <span>2</span>
          </div>
          <p>步骤内容描述</p>
        </el-card>
        <el-card class="card">
          <div slot="header" class="clearfix">
            <span>3</span>
          </div>
          <p>步骤内容描述</p>
        </el-card>
        <el-card class="card">
          <div slot="header" class="clearfix">
            <span>备份列表</span>
          </div>
          <el-table :data="backup_list" border stripe>
            <el-table-column prop="name" label="名称"></el-table-column>
            <el-table-column prop="time" label="上传时间"></el-table-column>
            <el-table-column prop="size" label="文件大小"></el-table-column>
            <el-table-column prop="class" label="储存类型"></el-table-column>
            <el-table-column label="操作">
              <template slot-scope="scope">
                <el-button size="mini" @click="handleDownload(scope.$index, scope.row)">下载</el-button>
                <el-button size="mini" type="danger" @click="handleDelete(scope.$index, scope.row)">删除</el-button>
              </template>
            </el-table-column>
          </el-table>
        </el-card>
      </div>
    </section>
  </div>
</template>

<script>
  import echart_config from '../assets/echart_config'

  export default {
    name: "Data",
    data() {
      return {
        backup_list: [],
        memory_graph: echart_config['memory_graph']
      }
    },
    mounted() {
      this.getBackupList();
    },
    methods: {
      getBackupList: function () {
        let that = this;
        this.$http.get(this.$wb_host + '/backup/list').then(function (response) {
          if (response.data.status === 'success') {
            that.$notify.success({
              title: '成功',
              message: response.data.info.msg,
              type: 'warning',
              position: 'bottom-right'
            });
            that.backup_list = response.data.data;
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
      handleDownload: function () {
        
      },
      handleDelete: function () {
        
      }
    }
  }
</script>

<style scoped lang="scss">
  #data-page {
    .echarts {
      width: 1000px;
      height: 800px;
    }

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
      .card {
        margin: 20px 0;
      }
    }
  }
</style>
