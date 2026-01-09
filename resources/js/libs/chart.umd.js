import Chart from './libs/chart.umd.js';

document.addEventListener('DOMContentLoaded', () => {
  const el = document.getElementById('chart-semanal');
  if (!el || typeof Chart === 'undefined') return;

  const labels = JSON.parse(el.dataset.labels || '[]');
  const data   = JSON.parse(el.dataset.data   || '[]');

  new Chart(el, {
    type: 'bar',
    data: {
      labels,
      datasets: [{ label: 'Consultas', data }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: { y: { beginAtZero: true, ticks: { precision: 0 } } },
      plugins: { legend: { display: false } }
    }
  });
});
