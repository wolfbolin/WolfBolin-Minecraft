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
          <div slot="header" class="wb-clear_fix">
            <span>服务概况</span>
          </div>
          <el-row :gutter="40" class="wb-state">
            <el-col :xs="12" :sm="8">
              <p class="wb-state-title">服务状态：</p>{{service_state}}({{service_notice}})
            </el-col>
            <el-col :xs="12" :sm="8">
              <p class="wb-state-title">重启次数：</p>{{restart_count}}
            </el-col>
            <el-col :xs="12" :sm="8">
              <p class="wb-state-title">服务端口：</p>{{server_port}}
            </el-col>
            <el-col :xs="12" :sm="8">
              <p class="wb-state-title">启动时间：</p>{{start_time}}
            </el-col>
            <el-col :xs="12" :sm="8">
              <p class="wb-state-title">关闭时间：</p>{{stop_time}}
            </el-col>
            <el-col :xs="12" :sm="8">
              <p class="wb-state-title">更新时间：</p>{{update_time}}
            </el-col>
          </el-row>
          <el-row :gutter="40" class="wb-pie-graph">
            <el-col :xs="24" :sm="12">
              <v-chart :options="cpu_pie_graph" class="cpu_pie_graph"></v-chart>
            </el-col>
            <el-col :xs="24" :sm="12">
              <v-chart :options="ram_pie_graph" class="ram_pie_graph"></v-chart>
            </el-col>
            <el-col :xs="24" :sm="12">
              <v-chart :options="net_pie_graph" class="net_pie_graph"></v-chart>
            </el-col>
            <el-col :xs="24" :sm="12">
              <v-chart :options="disk_pie_graph" class="disk_pie_graph"></v-chart>
            </el-col>
          </el-row>

        </el-card>
        <el-card class="card">
          <div slot="header" class="wb-clear_fix">
            <span>CPU用量</span>
          </div>
          <div ref="wb-cpu_line_graph">
            <v-chart :options="cpu_line_graph" class="wb-cpu_line_graph"></v-chart>
          </div>
        </el-card>
        <el-card class="card">
          <div slot="header" class="wb-clear_fix">
            <span>内存用量</span>
          </div>
          <div ref="wb-ram_line_graph">
            <v-chart :options="ram_line_graph" class="wb-ram_line_graph"></v-chart>
          </div>
        </el-card>
        <el-card class="card">
          <div slot="header" class="wb-clear_fix">
            <span>备份列表</span>
            <el-button class="wb-backup" type="primary" size="small" @click="authPassword">输入密钥</el-button>
            <el-button class="wb-backup" type="success" size="small" @click="addBackup">新增存档</el-button>
          </div>
          <el-table :data="backup_list" border stripe>
            <el-table-column prop="name" label="名称"></el-table-column>
            <el-table-column prop="time" label="上传时间"></el-table-column>
            <el-table-column prop="size" label="文件大小"></el-table-column>
            <el-table-column prop="class" label="储存类型"></el-table-column>
            <el-table-column label="操作">
              <template slot-scope="scope">
                <el-button size="mini" @click="getDownload(scope.$index, scope.row)">下载</el-button>
                <el-button size="mini" type="danger" @click="postDelete(scope.$index, scope.row)">删除</el-button>
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
  import backup_mod from '../assets/backup_mod'

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
        restart_count: 0,
        server_port: "25665",
        service_notice: "running",
        service_state: "Running",
        start_time: 1553738639,
        stop_time: 1553738621,
        update_time: "2019-04-11 01:29:02",
        auth_code: ''
      }
    },
    mounted() {
      this.getCPUData();
      this.getRAMData();
      this.getOverview();
      this.getBackupList();
    },
    methods: {
      getOverview: other_mod['get_overview'],
      getCPUData: cpu_mod['get_data'],
      getRAMData: ram_mod['get_data'],
      getBackupList: backup_mod['get_data'],
      authPassword: backup_mod['auth_password'],
      getDownload: backup_mod['get_download'],
      postDelete: backup_mod['post_delete'],
      addBackup: backup_mod['add_backup'],
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
        text-align: center;
        .wb-clear_fix {
          text-align: left;
          font-weight: 800;
          .wb-backup {
            float: right;
            margin-left: 10px;
          }
        }
        .wb-state {
          width: 80%;
          display: inline-block;
          text-align: left;
          .wb-state-title {
            display: inline-block;
            font-weight: 800;
          }
        }
        .wb-pie-graph {
          margin: 30px 0;
        }
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
