export default {
  get_data: function () {
    let that = this;
    this.$http.get(this.$wb_host + '/info/cpu', {
      params: {
        time: '7days'
      }
    }).then(function (response) {
      if (response.data.status === 'success') {
        that.cpu_line_graph.xAxis.data = [];
        that.cpu_line_graph.series[0].data = [];
        for (let index in response.data.data) {
          if (response.data.data.hasOwnProperty(index)) {
            that.cpu_line_graph.xAxis.data.push(response.data.data[index]['time']);
            that.cpu_line_graph.series[0].data.push(parseFloat(response.data.data[index]['num']));
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
  cpu_line_graph: {
    tooltip: {
      trigger: 'axis',
      formatter: "使用量: {c}%",
      axisPointer: {
        type: 'cross',
        crossStyle: {
          color: '#999'
        }
      }
    },
    xAxis: {
      type: 'category',
      boundaryGap: false,
      data: []
    },
    yAxis: {
      type: 'value',
      name: '百分比/%',
      axisLabel: {
        formatter: '{value} %'
      }
    },
    series: [{
      data: [],
      type: 'line',
      areaStyle: {}
    }]
  },
  cpu_pie_graph: {
    title : {
      text: 'CPU使用量',
      left:'center',
      top: 'bottom'
    },
    tooltip: {
      trigger: 'item',
      formatter: "{b}: {d}%"
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
          {value: 12, name: '占用',itemStyle: {color: '#0080FF'}},
          {value: 88, name: '空闲',itemStyle: {color: '#808080'}}
        ]
      }
    ]
  }
}
