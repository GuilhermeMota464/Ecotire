const meses = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
const data = new Date();

const indice_mes_atual = data.getMonth() + 1;

// Cortamos o array meses do índice 0 até o mês atual
const meses_ate_agora = meses.slice(0, indice_mes_atual);

$fatu_jan = 1;
$fatu_fev = 2;
$fatu_mar = 3;
$fatu_abr = 7;
$fatu_mai = 6;
$fatu_jun = 3;
$fatu_jul = 5;
$fatu_ago = 4;
$fatu_set = 2;
$fatu_out = 6;
$fatu_nov = 1;
$fatu_dez = 9;  

const faturamento_mensal = [$fatu_jan, $fatu_fev, $fatu_mar, $fatu_abr, $fatu_mai, $fatu_jun, $fatu_jul, $fatu_ago, $fatu_set, $fatu_out, $fatu_nov, $fatu_dez];
const faturamento_mensal_ate_agora = faturamento_mensal.slice(0, indice_mes_atual);

const acessos = [35, 41, 62, 42, 13, 18, 29, 37, 36, 51, 32, 35];
const acessos_ate_agora = acessos.slice(0, indice_mes_atual);

const abandono = [20, 35, 44, 49, 40, 38, 42, 57, 67, 56, 45, 47];
const abandono_ate_agora = abandono.slice(0, indice_mes_atual);

var options = {
  // 1. Defina as cores aqui (na ordem das séries)
  colors: ['rgb(43, 109, 77)', '#36A2EB', 'rgb(255, 0, 55)'], 
  
  series: [{
    name: "Faturamento mensal",
    // 2. Apenas a array de números, sem o objeto "datasets" do Chart.js
    data: faturamento_mensal_ate_agora 
  },
  {
    name: "Acessos ao site",
    data: acessos_ate_agora
  },
  {
    name: 'Abandono de carrinho',
    data: abandono_ate_agora
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
    categories: meses_ate_agora,
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
