<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>G-ERP | Reporte de Ventas</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    @include('partials.header')
    <div class="volume-control">
        <img class="audio-control volume-icon" src="{{ asset('icons/audio.png') }}" alt="audio">
    </div>

    <main class="erp-reports-container" style="padding: 20px;">
        <section class="reports-section-top" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <div class="reports-section-top-tittle">
                <h3 style="margin: 0;">Reporte de Ventas</h3>
            </div>
            <div>
                <select id="filterSelect" class="form-control" style="padding: 8px; border-radius: 4px; border: 1px solid #ccc;">
                    <option value="year">Por Año</option>
                    <option value="month" selected>Por Mes</option>
                    <option value="day">Por Día (Últimos 7 días)</option>
                </select>
            </div>
        </section>

        <section class="reports-section-table" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
            <div style="position: relative; height: 400px; width: 100%;">
                <canvas id="salesChart"></canvas>
            </div>
        </section>
    </main>

    @include('partials.footer')
    <script type="module" src="{{ asset('js/main.js') }}"></script>

    <script>
        let salesChart;

        async function initSalesChart() {
            try {
                const response = await fetch("{{ route('sales.reports.data') }}");
                const chartData = await response.json();

                const ctx = document.getElementById('salesChart').getContext('2d');
                
                salesChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: chartData.month.labels,
                        datasets: [{
                            label: 'Ventas Totales ($)',
                            data: chartData.month.data,
                            backgroundColor: 'rgba(37, 99, 235, 0.6)',
                            borderColor: 'rgba(37, 99, 235, 1)',
                            borderWidth: 1,
                            borderRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: { beginAtZero: true }
                        }
                    }
                });

                document.getElementById('filterSelect').addEventListener('change', function(e) {
                    const selected = e.target.value;
                    
                    if (chartData[selected]) {
                        salesChart.data.labels = chartData[selected].labels;
                        salesChart.data.datasets[0].data = chartData[selected].data;
                        salesChart.update();
                    }
                });

            } catch (error) {
                console.error('Error al obtener los datos reales de ventas:', error);
            }
        }

        document.addEventListener('DOMContentLoaded', initSalesChart);
    </script>
</body>
</html>