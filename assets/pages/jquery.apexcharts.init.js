

var options = {
    chart: {
        height: 380,
        type: 'bar',
        toolbar: {
            show: false
        }
    },
    plotOptions: {
        bar: {
            horizontal: true,
        }
    },
    dataLabels: {
        enabled: false
    },
    series: [{
        data: [400, 430, 448, 470, 540, 580, 690, 1100, 1200, 1380]
    }],
    colors: ["#95a6bf"],
    xaxis: {
        categories: ['South Korea', 'Canada', 'United Kingdom', 'Netherlands', 'Italy', 'France', 'Japan', 'United States', 'China', 'Germany'],
    },
    states: {
        hover: {
            filter: 'none'
        }
    },
    grid: {
        borderColor: '#f1f3fa'
    }
}




// new ApexCharts(document.querySelector("#chart1"), options1).render();
// new ApexCharts(document.querySelector("#apex_bar1"), options).render();

$(".chart-today").each(function(i, e){
  console.log();
  var options1 = {
  chart: {
      type: 'line',
      width: 140,
      height: 30,
      sparkline: {
          enabled: true
      }
  },
  series: [{
      data: JSON.parse($(this).attr("data-series"))
  }],
  stroke: {
      width: 2,
      curve: 'smooth'
  },
  markers: {
      size: 0
  },
  colors: ["#028602"],
  tooltip: {
      fixed: {
          enabled: false
      },
      x: {
          show: false
      },
      y: {
          title: {
              formatter: function (seriesName) {
                  return ''
              }
          }
      },
      marker: {
          show: false
      }
    }
  }
  new ApexCharts(e, options1).render();
});