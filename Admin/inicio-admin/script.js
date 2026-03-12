// Gráfico de linhas para vendas mensais
var options = {
  series: [{
    name: "Faturamento mensal",
    data: [45, 52, 38, 24, 33, 26, 21, 20, 6, 8, 15, 10]
  },
  {
    name: "Acessos ao site",
    data: [35, 41, 62, 42, 13, 18, 29, 37, 36, 51, 32, 35]
  },
  {
    name: 'Abandono de carrinho',
    data: [200, 57, 74, 99, 75, 38, 62, 47, 82, 56, 45, 47 ]
  }
  ],
  chart: {
    height: 350,
    type: 'line',
    zoom: {
      enabled: false,
      borderColor: 'rgba(255, 0, 0, 1)', 
      tension: 0.1
    },
  },
  dataLabels: {
    enabled: false
  },
  stroke: {
    width: [5, 7, 5],
    curve: 'straight',
    dashArray: [0, 8, 5]
  },
        legend: {
          tooltipHoverFormatter: function(val, opts) {
            return val + ' - <strong>' + opts.w.globals.series[opts.seriesIndex][opts.dataPointIndex] + '</strong>'
          }
        },
        markers: {
          size: 0,
          hover: {
            sizeOffset: 6
          }
        },
        xaxis: {
          categories: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun',
            'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'
          ],
        },
        grid: {
          borderColor: 'rgb(184, 190, 195)',
        }
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();   

        

// Gráfico de pizza para gênero dos clientes
 var options = {
          series: [44, 55, 13, 43],
          chart: {
          width: 380,
          type: 'pie',
        },
        labels: ['Feminino', 'Masculino', 'Outros', 'Prefiro não dizer'],
        colors: ['#FF6384', '#36A2EB', '#FFCE56', '#4bc04bff'],
        responsive: [{
          breakpoint: 480,
          options: {
            chart: {
              width: 200
            },
            legend: {
              position: 'bottom'
            }
          }
        }]
        };

        var chart = new ApexCharts(document.querySelector("#gender-chart"), options);
        chart.render();
