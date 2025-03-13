
let chartTemperature;
let chartHeartRate;

function generarReporte() {
    const identificador = document.getElementById('identificador').value;
    const filterDate = document.getElementById('filter-date').value;

    const queryString = `?identificador=${identificador}&start-date=${filterDate}&end-date=${filterDate}`;

    fetch(`../config/obtener_salud.php${queryString}`)
        .then(response => response.json())
        .then(data => {
            const tbody = document.querySelector('#results-table tbody');
            tbody.innerHTML = ''; // Limpiar tabla antes de agregar nuevos datos
            data.forEach(row => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${row.id_vaca}</td>
                    <td>${row.identificador}</td>
                    <td>${row.temperatura}</td>
                    <td>${row.frecuencia_cardiaca}</td>
                    <td>${row.fecha_hora}</td>
                `;
                tbody.appendChild(tr);
            });
        })
        .catch(error => console.error('Error fetching data:', error));
}

function mostrarGraficas() {
    const identificador = document.getElementById('identificador').value;
    const filterDate = document.getElementById('filter-date').value;

    const queryString = `?identificador=${identificador}&start-date=${filterDate}&end-date=${filterDate}`;

    if (!identificador) {
        Swal.fire({
            title: "Error",
            text: "Por favor, seleccione un identificador.",
            icon: "warning"
        });
        return;
    }

    fetch(`../config/obtener_salud.php${queryString}`)
        .then(response => response.json())
        .then(data => {
            if (data.length === 0) {
                Swal.fire({
                    title: "Error",
                    text: "No se encontraron datos para mostrar.",
                    icon: "warning"
                });
                return;
            }
            renderCharts(data, identificador, filterDate);
            const modal = document.getElementById('chart-modal');
            modal.style.display = 'block';

            const closeModal = document.getElementsByClassName('close')[0];
            closeModal.onclick = function () {
                modal.style.display = 'none';
            };

            window.onclick = function (event) {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            };

            document.getElementById('download-chart-btn').onclick = function () {
                downloadCharts();
            };
        })
        .catch(error => console.error('Error fetching data:', error));
}

function renderCharts(data, identificador, filterDate) {
    const labels = data.map(row => row.fecha_hora);
    const temperatureData = data.map(row => row.temperatura);
    const heartRateData = data.map(row => row.frecuencia_cardiaca);

    const ctxTemperature = document.getElementById('chart-temperature').getContext('2d');
    const ctxHeartRate = document.getElementById('chart-heart-rate').getContext('2d');

    if (chartTemperature) chartTemperature.destroy();
    if (chartHeartRate) chartHeartRate.destroy();

    chartTemperature = new Chart(ctxTemperature, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: `Temperatura - Identificador: ${identificador} (${filterDate})`,
                data: temperatureData,
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    chartHeartRate = new Chart(ctxHeartRate, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: `Frecuencia Cardiaca - Identificador: ${identificador} (${filterDate})`,
                data: heartRateData,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

function downloadCharts() {
    const linkTemperature = document.createElement('a');
    linkTemperature.href = chartTemperature.toBase64Image();
    linkTemperature.download = 'grafica_temperatura.jpg';
    linkTemperature.click();

    const linkHeartRate = document.createElement('a');
    linkHeartRate.href = chartHeartRate.toBase64Image();
    const fechaActual = new Date().toISOString().slice(0, 10);
    linkHeartRate.download = `grafica_frecuencia_cardiaca_${fechaActual}.jpg`; 
    linkHeartRate.click();
}


function descargarReporte() {
    const identificador = document.getElementById('identificador').value;
    const table = document.getElementById('results-table');
    const rows = Array.from(table.querySelectorAll('tbody tr'));
    const headerRow = table.querySelector('thead tr');

    // Validar si hay datos en la tabla
    if (rows.length === 0 || (rows.length === 1 && rows[0].textContent.includes('No hay datos disponibles'))) {
        Swal.fire({
            icon: 'warning',
            title: 'Error de Descarga',
            text: 'No hay datos en la tabla para descargar.',
        });
        return;
    }

    // Obtener encabezado
    const headers = Array.from(headerRow.querySelectorAll('th')).map(th => th.textContent.trim()).join(',');

    // Generar contenido CSV
    const csvContent = [
        headers, // Agregar encabezado
        ...rows.map(row => {
            const cells = Array.from(row.querySelectorAll('td')).map(cell => cell.textContent.trim());
            return cells.join(',');
        })
    ].join('\n');

    // Convertir a Blob con UTF-8 y BOM para soportar caracteres especiales
    const BOM = '\uFEFF'; // Marca de orden de bytes (Byte Order Mark)
    const blob = new Blob([BOM + csvContent], { type: 'text/csv;charset=utf-8;' });

    // Crear enlace de descarga
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    const fechaActual = new Date().toISOString().slice(0, 10);
    const nombreArchivo = identificador ? `reporte_Salud_${identificador}_${fechaActual}.csv` : `reporte_Salud_general_${fechaActual}.csv`;
    a.setAttribute('href', url);
    a.setAttribute('download', nombreArchivo);
    a.click();
}
