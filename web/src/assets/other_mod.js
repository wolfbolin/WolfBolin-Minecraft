export default {
  get_overview: function () {
    let that = this;
    this.$http.get(this.$wb_host + '/info/overview').then(function (response) {
      if (response.data.status === 'success') {

        let cpu_item = response.data.data['cpu'];
        that.cpu_pie_graph.series[0].data[0].value = parseFloat(cpu_item);
        that.cpu_pie_graph.series[0].data[1].value = 100 - parseFloat(cpu_item);

        let ram_item = response.data.data['ram'];
        let ram_num = parseFloat(ram_item['num']) * 1000;
        let ram = Math.round(ram_num / (parseFloat(ram_item['per']) / 100));
        that.ram_pie_graph.series[0].data[0].value = ram_num;
        that.ram_pie_graph.series[0].data[1].value = ram - ram_num;

        let net_item = response.data.data['net'];
        that.net_pie_graph.series[0].data[0].value = parseFloat(net_item['in']);
        that.net_pie_graph.series[0].data[1].value = parseFloat(net_item['out']) * 1000;

        let disk_item = response.data.data['disk'];
        that.disk_pie_graph.series[0].data[0].value = parseFloat(disk_item['in']);
        that.disk_pie_graph.series[0].data[1].value = parseFloat(disk_item['out']);


      } else {
        that.$notify({
          title: '警告',
          message: response.data.info,
          type: 'warning',
          position: 'bottom-right'
        });
      }
    }).catch(function (err) {
        console.log(err);
        that.$notify.error({
          title: '错误',
          message: '您似乎与服务器断开链接',
          position: 'bottom-right'
        });
      }
    );
  },
  net_pie_graph: {
    title: {
      text: '网络使用量',
      left: 'center',
      top: 'bottom'
    },
    tooltip: {
      trigger: 'item',
      formatter: "{b}: {c}MB ({d}%)"
    },
    series: [
      {
        type: 'pie',
        radius: ['50%', '70%'],
        avoidLabelOverlap: false,
        label: {
          normal: {
            formatter: '{d}%'
          }
        },
        data: [
          {value: 12, name: '出网', itemStyle: {color: '#0080FF'}},
          {value: 88, name: '入网', itemStyle: {color: '#808080'}}
        ]
      }
    ]
  },
  disk_pie_graph: {
    title: {
      text: '磁盘使用量',
      left: 'center',
      top: 'bottom'
    },
    tooltip: {
      trigger: 'item',
      formatter: "{b}: {c}MB ({d}%)"
    },
    series: [
      {
        type: 'pie',
        radius: ['50%', '70%'],
        avoidLabelOverlap: false,
        label: {
          normal: {
            formatter: '{d}%'
          }
        },
        data: [
          {value: 12, name: '写入', itemStyle: {color: '#0080FF'}},
          {value: 88, name: '读取', itemStyle: {color: '#808080'}}
        ]
      }
    ]
  }
}
