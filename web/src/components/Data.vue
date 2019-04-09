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
            <span>服务概况</span>
          </div>
          <el-row :gutter="40">
            <el-col class="item" :xs="24" :sm="6">
              <v-chart :options="cpu_pie_graph" class="cpu_pie_graph"></v-chart>
            </el-col>
            <el-col class="item" :xs="24" :sm="6">
              <v-chart :options="ram_pie_graph" class="ram_pie_graph"></v-chart>
            </el-col>
            <el-col class="item" :xs="24" :sm="6">
              <v-chart :options="net_pie_graph" class="net_pie_graph"></v-chart>
            </el-col>
            <el-col class="item" :xs="24" :sm="6">
              <v-chart :options="disk_pie_graph" class="disk_pie_graph"></v-chart>
            </el-col>
          </el-row>

        </el-card>
        <el-card class="card">
          <div slot="header" class="clearfix">
            <span>CPU用量</span>
          </div>
          <div ref="wb-cpu_line_graph">
            <v-chart :options="cpu_line_graph" class="wb-cpu_line_graph"></v-chart>
          </div>
        </el-card>
        <el-card class="card">
          <div slot="header" class="clearfix">
            <span>内存用量</span>
          </div>
          <div ref="wb-ram_line_graph">
            <v-chart :options="ram_line_graph" class="wb-ram_line_graph"></v-chart>
          </div>
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
  import ram_mod from '../assets/ram_mod'
  import cpu_mod from '../assets/cpu_mod'
  import other_mod from '../assets/other_mod'

  export default {
    name: "Data",
    data() {
      return {
        backup_list: [],
        cpu_pie_graph: cpu_mod['cpu_pie_graph'],
        ram_pie_graph: ram_mod['ram_pie_graph'],
        net_pie_graph: other_mod['net_pie_graph'],
        disk_pie_graph: other_mod['disk_pie_graph'],
        cpu_line_graph: cpu_mod['cpu_line_graph'],
        ram_line_graph: ram_mod['ram_line_graph'],
      }
    },
    mounted() {
      this.getBackupList();
      this.getCPUData();
      this.getRAMData();
    },
    methods: {
      getCPUData: cpu_mod['get_data'],
      getRAMData: ram_mod['get_data'],
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
      width: 100%;
      height: 100%;
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
        .wb-cpu_line_graph {
          width: 100%;
          height: 400px;
        }
        .wb-ram_line_graph {
          width: 100%;
          height: 400px;
        }
        .cpu_pie_graph, .ram_pie_graph, .net_pie_graph, .disk_pie_graph {
          width: 100%;
          height: 250px;
        }
      }
    }
  }
</style>
