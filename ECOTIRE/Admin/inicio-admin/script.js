// ===== GRÁFICO TABELA DE VENDAS (dinâmico) =====
(async function initSalesChart() {
  const chartEl = document.querySelector("#sales-chart");
  if (!chartEl) return;

  try {
    const resp = await fetch('api_graficos.php');
    const payload = await resp.json();

    const meses = payload.meses || [];
    const faturamento = payload.faturamento_mensal || [];
    const acessos = payload.acessos || [];
    const abandono = payload.abandono || [];

    const options = {
      colors: ['rgb(43, 109, 77)', '#36A2EB', 'rgb(255, 0, 55)'],
      series: [
        { name: 'Faturamento mensal', data: faturamento },
        { name: 'Acessos ao site', data: acessos },
        { name: 'Abandono de carrinho', data: abandono }
      ],
      chart: {
        height: 350,
        type: 'line',
        zoom: { enabled: false }
      },
      stroke: {
        width: [3, 3, 3],
        curve: 'straight'
      },
      legend: {
        tooltipHoverFormatter: function(val, opts) {
          return val + ' - <strong>' + opts.w.globals.series[opts.seriesIndex][opts.dataPointIndex] + '</strong>';
        }
      },
      xaxis: {
        categories: meses
      },
      grid: {
        borderColor: 'rgb(184, 190, 195)'
      }
    };

    new ApexCharts(chartEl, options).render();
  } catch (e) {
    console.error('Falha ao carregar dados dos gráficos:', e);
  }
})();

// ===== GRÁFICO DE PIZZA GÊNERO (dinâmico) =====
(async function initGenderChart() {
  const chartEl = document.querySelector("#gender-chart");
  if (!chartEl) return;

  try {
    const resp = await fetch('api_graficos.php');
    const payload = await resp.json();

    const generoLabels = payload?.genero?.labels || ['Feminino','Masculino','Outros','Prefiro não dizer'];
    const generoSeries = payload?.genero?.series || [0,0,0,0];

    const options = {
      series: generoSeries,
      chart: {
        width: 380,
        type: 'pie'
      },
      plotOptions: {
        pie: { expandOnClick: false }
      },
      states: {
        hover: { filter: { type: 'none' } },
        active: { filter: { type: 'darken', value: 0.1 } }
      },
      labels: generoLabels,
      colors: ['#FF6384', '#36A2EB', '#FFCE56', 'rgb(175, 175, 175)'],
      responsive: [
        {
          breakpoint: 480,
          options: {
            chart: { width: 200 },
            legend: { position: 'bottom' }
          }
        }
      ]
    };

    new ApexCharts(chartEl, options).render();
  } catch (e) {
    console.error('Falha ao carregar dados do gênero:', e);
  }
})();


