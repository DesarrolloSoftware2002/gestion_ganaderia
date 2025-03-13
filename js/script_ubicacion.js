function generarReporte() {
    const identificador = document.getElementById('identificador').value;
    const startDate = document.getElementById('start-date').value;
    const endDate = document.getElementById('end-date').value;

    fetch(`../config/obtener_Ubicacion.php?identificador=${identificador}&start-date=${startDate}&end-date=${endDate}`)
        .then(response => response.json())
        .then(data => {
            const tbody = document.querySelector('#results-table tbody');
            tbody.innerHTML = ''; // Limpiar tabla antes de agregar nuevos datos
            data.forEach(row => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${row.id_vaca}</td>
                    <td>${row.identificador}</td>
                    <td>${row.lugar}</td>
                    <td>${row.movimiento}</td>
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
    const nombreArchivo = identificador ? `reporte_Ubicacion_${identificador}_${fechaActual}.csv` : `reporte_Ubicacion_general_${fechaActual}.csv`;
    a.setAttribute('href', url);
    a.setAttribute('download', nombreArchivo);
    a.click();
}

// Función para abrir el modal de estadística
function abrirModalEstadistica() {
    document.getElementById('modalEstadistica').style.display = 'flex';
}

// Función para cerrar el modal de estadística
function cerrarModalEstadistica() {
    document.getElementById('modalEstadistica').style.display = 'none';
    document.getElementById('estadistica-resultados').innerHTML = ''; // Limpiar la tabla al cerrar el modal
}
//funcion consultar

function consultarEstadistica() {
    const identificador = document.getElementById('identificador-estadistica').value;
    const fecha = document.getElementById('fecha-estadistica').value;

    fetch(`../config/obtener_Ubicacion.php?identificador=${identificador}&start-date=${fecha}&end-date=${fecha}`)
        .then(response => response.json())
        .then(data => {
            if (!data || data.length === 0) {
                document.getElementById('estadistica-resultados').innerHTML = '<p>No hay datos disponibles para la fecha seleccionada.</p>';
                return;
            }

            const tiemposPorLugar = {};
            let entradaActual = null;
            let lugarActual = null;

            data.forEach(record => {
                const lugar = record.lugar;
                const movimiento = record.movimiento;  // Cambié de 'tipo' a 'movimiento' para que coincida con tu estructura
                const fechaHora = new Date(record.fecha_hora);

                if (movimiento === 'ENTRADA') {
                    entradaActual = fechaHora;
                    lugarActual = lugar;
                } else if (movimiento === 'SALIDA' && entradaActual && lugarActual === lugar) {
                    const salida = fechaHora;
                    const duracion = (salida - entradaActual) / 1000 / 60; // Duración en minutos

                    if (!tiemposPorLugar[lugar]) {
                        tiemposPorLugar[lugar] = 0;
                    }
                    tiemposPorLugar[lugar] += duracion;

                    entradaActual = null; // Reiniciar la entrada actual
                }
            });

            let resultadosHTML = '<h3>Estadísticas de Tiempo por Lugar</h3>';
            for (const [lugar, tiempoMinutos] of Object.entries(tiemposPorLugar)) {
                const horas = Math.floor(tiempoMinutos / 60);
                const minutos = Math.round(tiempoMinutos % 60);
                resultadosHTML += `<p>${lugar}: ${horas}h ${minutos}m</p>`;
            }

            document.getElementById('estadistica-resultados').innerHTML = resultadosHTML;
        })
        .catch(error => {
            console.error('Error al obtener datos:', error);
            document.getElementById('estadistica-resultados').innerHTML = '<p>Error al obtener datos.</p>';
        });
}
