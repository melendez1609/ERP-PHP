export async function initSalesChart() {
    const areaElem = document.getElementById('areaChart');
    if (!areaElem) return;

    let areaChart, barChart, radarChart, donutChart;

    const erpPalette = {
        navy: 'rgba(19, 40, 115, 0.9)',
        navyLight: 'rgba(19, 40, 115, 0.2)',
        skyBlue: 'rgba(2, 132, 199, 0.85)',
        green: 'rgba(40, 167, 69, 0.85)',
        yellow: 'rgba(236, 185, 18, 0.85)',
        red: 'rgba(216, 65, 48, 0.85)'
    };

    try {
        const response = await fetch('/reports/sales-data');
        const apiData = await response.json();

        const filterSelect = document.getElementById('filterSelect');
        const currentFilter = filterSelect ? filterSelect.value : 'month';
        const filterData = apiData[currentFilter];

        const ctxArea = areaElem.getContext('2d');
        areaChart = new Chart(ctxArea, {
            type: 'line',
            data: {
                labels: filterData.labels,
                datasets: [{
                    label: 'Ventas ($)',
                    data: filterData.totals,
                    borderColor: erpPalette.navy,
                    backgroundColor: erpPalette.navyLight,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: erpPalette.navy
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } } }
        });

        const barElem = document.getElementById('barChart');
        if (barElem) {
            const ctxBar = barElem.getContext('2d');
            barChart = new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: filterData.labels,
                    datasets: [{
                        label: 'Cant. Transacciones',
                        data: filterData.counts,
                        backgroundColor: erpPalette.navy,
                        borderRadius: 4
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } }
            });
        }

        const radarElem = document.getElementById('radarChart');
        if (radarElem) {
            const ctxRadar = radarElem.getContext('2d');
            radarChart = new Chart(ctxRadar, {
                type: 'radar',
                data: {
                    labels: apiData.radar.labels,
                    datasets: [{
                        label: 'Venta Promedio ($)',
                        data: apiData.radar.data,
                        borderColor: erpPalette.navy,
                        backgroundColor: erpPalette.navyLight
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, scales: { r: { beginAtZero: true, ticks: { display: false } } } }
            });
        }

        const donutElem = document.getElementById('donutChart');
        if (donutElem) {
            const ctxDonut = donutElem.getContext('2d');
            donutChart = new Chart(ctxDonut, {
                type: 'doughnut',
                data: {
                    labels: apiData.donut.labels,
                    datasets: [{
                        data: apiData.donut.data,
                        backgroundColor: [
                            erpPalette.navy,
                            erpPalette.green,
                            erpPalette.yellow,
                            erpPalette.red,
                            erpPalette.skyBlue
                        ]
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false }
            });
        }

        if (filterSelect) {
            filterSelect.addEventListener('change', function(e) {
                const sel = e.target.value;
                if (apiData[sel]) {
                    if (areaChart) {
                        areaChart.data.labels = apiData[sel].labels;
                        areaChart.data.datasets[0].data = apiData[sel].totals;
                        areaChart.update();
                    }

                    if (barChart) {
                        barChart.data.labels = apiData[sel].labels;
                        barChart.data.datasets[0].data = apiData[sel].counts;
                        barChart.update();
                    }
                }
            });
        }

    } catch (error) {
        console.error('Error al cargar datos analíticos:', error);
    }
}