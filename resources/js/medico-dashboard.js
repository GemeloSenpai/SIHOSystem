document.addEventListener('DOMContentLoaded', () => {
  if (typeof window.Chart === 'undefined') {
    console.warn('Chart.js no cargó. Revisa /js/chart.umd.js');
    return;
  }

  const el = document.getElementById('chart-semanal');
  if (!el) return;

  const labels = JSON.parse(el.dataset.labels || '[]');
  let data     = JSON.parse(el.dataset.data   || '[]');

  console.log('Labels:', labels);
  console.log('Data:', data);

  // Si todos son 0, metemos datos de prueba para confirmar que el lienzo se dibuja
  const allZero = Array.isArray(data) && data.length && data.every(n => Number(n) === 0);
  if (allZero) {
    console.warn('Serie semanal vacía (semana actual sin registros). Pintando datos de prueba...');
    data = [3, 5, 2, 0, 4, 1, 6];
  }

  const ctx = el.getContext('2d');
  new Chart(ctx, {
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
