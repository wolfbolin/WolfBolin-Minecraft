export default {
  net_pie_graph: {
    title : {
      text: '网络使用量',
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
          {value: 88, name: '闲置',itemStyle: {color: '#808080'}}
        ]
      }
    ]
  },
  disk_pie_graph: {
    title : {
      text: '磁盘使用量',
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
          {value: 88, name: '闲置',itemStyle: {color: '#808080'}}
        ]
      }
    ]
  }
}
