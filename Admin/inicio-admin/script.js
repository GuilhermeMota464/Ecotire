var options = {
  // 1. Defina as cores aqui (na ordem das séries)
  colors: ['rgb(43, 109, 77)', '#36A2EB', 'rgb(255, 0, 55)'], 
  
  series: [{
    name: "Faturamento mensal",
    // 2. Apenas a array de números, sem o objeto "datasets" do Chart.js
    data: [45, 52, 38, 24, 33, 26, 21, 20, 6, 8, 15, 10] 
  },
  {
    name: "Acessos ao site",
    data: [35, 41, 62, 42, 13, 18, 29, 37, 36, 51, 32, 35]
  },
  {
    name: 'Abandono de carrinho',
    data: [20, 35, 44, 49, 40, 38, 42, 57, 67, 56, 45, 47]
  }],
  chart: {
    height: 350,
    type: 'line',
    zoom: {
      enabled: false,
    },
  },
  stroke: {
    width: [3, 3, 3], // Aqui você define a espessura da linha (borderWidth)
    curve: 'straight'   // Aqui você define a suavização (tension)
  },
  legend: {
    tooltipHoverFormatter: function(val, opts) {
      return val + ' - <strong>' + opts.w.globals.series[opts.seriesIndex][opts.dataPointIndex] + '</strong>'
    }
  },
  xaxis: {
    categories: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
  },
  grid: {
    borderColor: 'rgb(184, 190, 195)',
  }
};

var chart = new ApexCharts(document.querySelector("#sales-chart"), options);
chart.render();

        

// Gráfico de pizza para gênero dos clientes
var options = {
    series: [44, 55, 13, 43],
    chart: {
        width: 380,
        type: 'pie',
    },
    // 1. Impede que a fatia "pule" para fora ao clicar
    plotOptions: {
        pie: {
            expandOnClick: false 
        }
    },
    // 2. Controla o efeito visual de passar o mouse ou clicar
    states: {
        hover: {
            filter: {
                type: 'none'
            }
        },
        active: {
            filter: {
                type: 'darken', // Remove o escurecimento ao clicar
                value: 0.1
            }
        }
    },
        labels: ['Feminino', 'Masculino', 'Outros', 'Prefiro não dizer'],
        colors: ['#FF6384', '#36A2EB', '#FFCE56', 'rgb(175, 175, 175)'],
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
