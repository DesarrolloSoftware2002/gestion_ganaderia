function generarReporte() {
    const identificador = document.getElementById('identificador').value;
    const startDate = document.getElementById('start-date').value;
    const endDate = document.getElementById('end-date').value;

    fetch(`../config/obtener_gps.php?identificador=${identificador}&start-date=${startDate}&end-date=${endDate}`)
        .then(response => response.json())
        .then(data => {
            const tbody = document.querySelector('#results-table tbody');
            tbody.innerHTML = ''; // Limpiar tabla antes de agregar nuevos datos
            data.forEach(row => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${row.id_vaca}</td>
                    <td>${row.identificador}</td>
                    <td>${row.gps}</td>
                    <td>${row.fecha_hora}</td>
                `;
                tbody.appendChild(tr);
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

    const csvContent = [
        headers,
        ...rows.map(row => {
            const cells = Array.from(row.querySelectorAll('td')).map(cell => cell.textContent.trim());
            return cells.map((cell, index) => {
                if (index === 2) { // Columna GPS
                    return `"${cell}"`;
                }
                return cell;
            }).join(',');
        })
    ].join('\n');

    const BOM = '\uFEFF';
    const blob = new Blob([BOM + csvContent], { type: 'text/csv;charset=utf-8;' });

    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    const fechaActual = new Date().toISOString().slice(0, 10);
    const nombreArchivo = identificador ? `reporte_GPS_${identificador}_${fechaActual}.csv` : `reporte_GPS_general_${fechaActual}.csv`;
    a.setAttribute('href', url);
    a.setAttribute('download', nombreArchivo);
    a.click();
}
