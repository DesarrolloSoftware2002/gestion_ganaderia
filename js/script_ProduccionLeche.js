
let myChart;

function generarReporte() {
    const identificador = document.getElementById('identificador').value;
    const startDate = document.getElementById('start-date').value;
    const endDate = document.getElementById('end-date').value;

    fetch(`../config/obtener_produccion.php?identificador=${identificador}&start-date=${startDate}&end-date=${endDate}`)
        .then(response => response.json())
        .then(data => {
            const tableBody = document.querySelector('#results-table tbody');
            tableBody.innerHTML = '';

            data.forEach(row => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${row.id_vaca}</td>
                    <td>${row.identificador}</td>
                    <td>${row.prod_leche}</td>
                    <td>${row.fecha_hora}</td>
                `;
                tableBody.appendChild(tr);
            });
        });
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
    const headers = Array.from(headerRow.querySelectorAll('th')).map(th => th.textContent.trim()).join(',');
    // Generar contenido CSV
    const csvContent = [
        headers, // Agregar encabezado
        ...rows.map(row => {
            const cells = Array.from(row.querySelectorAll('td')).map(cell => cell.textContent.trim());
            return cells.join(',');
        })
    ].join('\n');

    const BOM = '\uFEFF'; // Marca de orden de bytes (Byte Order Mark)
    const blob = new Blob([BOM + csvContent], { type: 'text/csv;charset=utf-8;' });
    
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    const fechaActual = new Date().toISOString().slice(0, 10);
    const nombreArchivo = identificador ? `reporte_Produccion_${identificador}_${fechaActual}.csv` : `reporte_Produccion_general_${fechaActual}.csv`;
    a.setAttribute('href', url);
    a.setAttribute('download', nombreArchivo);
    a.click();
}

function mostrarGrafica() {
    const identificador = document.getElementById('identificador').value;
    const startDate = document.getElementById('start-date').value;
    const endDate = document.getElementById('end-date').value;

    if (!identificador || !startDate || !endDate) {
        Swal.fire({
            title: "Error",
            text: "Por favor, seleccione un identificador y un rango de fechas.",
            icon: "warning"
        });
        return;
    }

    fetch(`../config/obtener_produccion.php?identificador=${identificador}&start-date=${startDate}&end-date=${endDate}`)
        .then(response => response.json())
        .then(data => {
            const labels = data.map(row => row.fecha_hora);
            const productionData = data.map(row => row.prod_leche);

            const ctx = document.getElementById('chart').getContext('2d');

            // Eliminar el gráfico anterior si existe
            if (myChart) {
                myChart.destroy();
            }

            myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: `Producción Lechera (Litros) - Identificador: ${identificador}`,
                        data: productionData,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false, // Permite escalar el gráfico
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
            

            const modal = document.getElementById('chart-modal');
            modal.style.display = 'block';

            const closeModal = document.getElementsByClassName('close')[0];
            closeModal.onclick = function() {
                modal.style.display = 'none';
            };

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            };

            document.getElementById('download-chart-btn').onclick = function() {
                const link = document.createElement('a');
                link.href = myChart.toBase64Image();
                link.download = 'grafica_produccion.jpg';
                link.click();
            };
        })
        .catch(error => {
            console.error('Error al obtener los datos para la gráfica:', error);
        });
}
