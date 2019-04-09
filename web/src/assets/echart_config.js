export default {
  memory_graph: {
    color: ['#1C86EE', '#FF3030'],

    tooltip: {
      trigger: 'axis',
      axisPointer: {
        type: 'cross'
      }
    },
    grid: {
      right: '20%'
    },
    legend: {
      data: ['蒸发量', '平均温度']
    },
    xAxis: [
      {
        type: 'category',
        axisTick: {
          alignWithLabel: true
        },
        data: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月']
      }
    ],
    yAxis: [
      {
        type: 'value',
        name: '内存/GB',
        min: 0,
        max: 250,
        position: 'left',
        axisLine: {
          lineStyle: {
            color: "#1C86EE"
          }
        },
        axisLabel: {
          formatter: '{value} MB'
        }
      },
      {
        type: 'value',
        name: '百分比/%',
        min: 0,
        max: 100,
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
        data: [2.0, 4.9, 7.0, 23.2, 25.6, 76.7, 135.6, 162.2, 32.6, 20.0, 6.4, 3.3]
      },
      {
        name: '百分比',
        type: 'line',
        yAxisIndex: 1,
        data: [2.0, 2.2, 3.3, 4.5, 6.3, 10.2, 20.3, 23.4, 23.0, 16.5, 12.0, 6.2]
      }
    ]
  }
}
