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

    <main class="erp-sales-reports-container">
        <section class="sales-reports-section-top">
            <div class="sales-reports-section-top-tittle">
                <h3>Panel Analítico de Ventas</h3>
            </div>
            <div>
                <select id="filterSelect" class="table-action-select">
                    <option value="year">Por Año</option>
                    <option value="month" selected>Por Mes</option>
                    <option value="day">Por Día (Últimos 7 días)</option>
                </select>
            </div>
        </section>

        <div class="sales-reports-grid-wrapper">
            <div class="sales-reports-card">
                <div class="sales-reports-card-title">Monto Total de Ventas ($)</div>
                <div class="sales-reports-chart-box">
                    <canvas id="areaChart"></canvas>
                </div>
            </div>

            <div class="sales-reports-card">
                <div class="sales-reports-card-title">N° de Transacciones Realizadas</div>
                <div class="sales-reports-chart-box">
                    <canvas id="barChart"></canvas>
                </div>
            </div>

            <div class="sales-reports-card">
                <div class="sales-reports-card-title">Ventas por Día de Semana</div>
                <div class="sales-reports-chart-box">
                    <canvas id="radarChart"></canvas>
                </div>
            </div>

            <div class="sales-reports-card">
                <div class="sales-reports-card-title">Proporción por Estado</div>
                <div class="sales-reports-chart-box">
                    <canvas id="donutChart"></canvas>
                </div>
            </div>
        </div>
    </main>

    @include('partials.footer')
    <script type="module" src="{{ asset('js/main.js') }}"></script>
</body>
</html>