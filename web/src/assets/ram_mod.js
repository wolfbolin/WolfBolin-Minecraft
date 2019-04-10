export default {
  get_data: function () {
    let that = this;
    this.$http.get(this.$wb_host + '/info/ram', {
      params: {
        time: '7days'
      }
    }).then(function (response) {
      if (response.data.status === 'success') {
        that.ram_line_graph.xAxis[0].data = [];
        that.ram_line_graph.series[0].data = [];
        that.ram_line_graph.series[1].data = [];
        for (let index in response.data.data) {
          if (response.data.data.hasOwnProperty(index)) {
            that.ram_line_graph.xAxis[0].data.push(response.data.data[index]['time']);
            that.ram_line_graph.series[0].data.push(parseFloat(response.data.data[index]['num']));
            that.ram_line_graph.series[1].data.push(parseFloat(response.data.data[index]['per']));
          }
        }
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
  ram_line_graph: {
    tooltip: {
      trigger: 'axis',
      axisPointer: {
        type: 'cross',
        crossStyle: {
          color: '#999'
        }
      }
    },
    color: ['#1C86EE', '#FF3030'],
    xAxis: [
      {
        type: 'category',
        axisPointer: {
          type: 'shadow'
        },
        data: []
      }
    ],
    yAxis: [
      {
        type: 'value',
        name: '内存/GB',
        position: 'left',
        axisLine: {
          lineStyle: {
            color: "#1C86EE"
          }
        },
        axisLabel: {
          formatter: '{value} GB'
        }
      },
      {
        type: 'value',
        name: '百分比/%',
        position: 'right',
        axisLine: {
          lineStyle: {
            color: "#FF3030"
          }
        },
        axisLabel: {
          formatter: '{value} %'
        }
      }
    ],
    series: [
      {
        name: '使用量',
        type: 'bar',
        yAxisIndex: 0,
        data: []
      },
      {
        name: '百分比',
        type: 'line',
        yAxisIndex: 1,
        data: []
      }
    ]
  },
  ram_pie_graph: {
    title: {
      text: 'RAM占用量',
      left: 'center',
      top: 'bottom'
    },
    tooltip: {
      trigger: 'item',
      formatter: "{b}: {c}MB ({d}%)"
    },
    series: [
      {
        name: 'RAM占用量',
        type: 'pie',
        radius: ['50%', '70%'],
        avoidLabelOverlap: false,
        label: {
          normal: {
            formatter: '{d}%'
          }
        },
        data: [
          {value: 12, name: '占用', itemStyle: {color: '#0080FF'}},
          {value: 88, name: '空闲', itemStyle: {color: '#808080'}}
        ]
      }
    ]
  }
}
