
document.addEventListener('DOMContentLoaded', function () {
    fetch('../config/obtener_registros.php')
        .then(response => response.json())
        .then(data => {
            const tbody = document.querySelector('#results-table tbody');
            data.forEach(row => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                            <td>${row.id}</td>
                            <td><span class="editable">${row.identificador}</span><input type="text" class="edit-mode" value="${row.identificador}" style="display:none;"></td>
                            <td><span class="editable">${row.sexo}</span><select class="edit-mode" style="display:none;">
                                <option value="Hembra" ${row.sexo === 'Hembra' ? 'selected' : ''}>Hembra</option>
                                <option value="Macho" ${row.sexo === 'Macho' ? 'selected' : ''}>Macho</option>
                            </select></td>
                            <td><span class="editable">${row.raza}</span><input type="text" class="edit-mode" value="${row.raza}" style="display:none;"></td>
                            <td><span class="editable">${row.color}</span><input type="text" class="edit-mode" value="${row.color}" style="display:none;"></td>
                            <td><span class="editable">${row.nacimiento}</span><input type="date" class="edit-mode" value="${row.nacimiento}" style="display:none;"></td>
                            <td><span class="editable">${calcularEdad(new Date(row.nacimiento))}</span><input type="text" class="edit-mode" value="${calcularEdad(new Date(row.nacimiento))}" style="display:none;"></td>
                            <td><span class="editable">${row.partos}</span><input type="number" class="edit-mode" value="${row.partos}" style="display:none;"></td>
                            <td>${row.fecha_ingreso}</td>
                            <td class="action-buttons">
                                <button class="editar" onclick="editarFila(this)">Editar</button>
                                <button class="eliminar" onclick="confirmarEliminar(this, ${row.id})">Eliminar</button>
                                <button class="cancelar" onclick="cancelarEdicion(this)" style="display:none;">Cancelar</button>
                                <button class="guardar" onclick="guardarCambios(this, ${row.id})" style="display:none;">Guardar</button>
                            </td>
                        `;
                tbody.appendChild(tr);
            });
        });
});

function calcularEdad(fechaNacimiento) {
    const hoy = new Date();
    let edadAnios = hoy.getFullYear() - fechaNacimiento.getFullYear();
    let edadMeses = hoy.getMonth() - fechaNacimiento.getMonth();

    if (edadMeses < 0 || (edadMeses === 0 && hoy.getDate() < fechaNacimiento.getDate())) {
        edadAnios--;
        edadMeses += 12;
    }

    if (hoy.getDate() < fechaNacimiento.getDate()) {
        edadMeses--;
    }

    if (edadMeses < 0) {
        edadMeses += 12;
        edadAnios--;
    }

    return `${edadAnios} años y ${edadMeses} meses`;
}

function editarFila(button) {
    let tr = button.closest('tr');
    let editMode = tr.querySelectorAll('.edit-mode');
    let nonEditMode = tr.querySelectorAll('.editable');
    let actionButtons = tr.querySelector('.action-buttons');

    editMode.forEach(elem => elem.style.display = 'inline-block');
    nonEditMode.forEach(elem => elem.style.display = 'none');

    actionButtons.querySelector('.editar').style.display = 'none';
    actionButtons.querySelector('.eliminar').style.display = 'none';
    actionButtons.querySelector('.cancelar').style.display = 'inline-block';
    actionButtons.querySelector('.guardar').style.display = 'inline-block';
}

function cancelarEdicion(button) {
    let tr = button.closest('tr');
    let editMode = tr.querySelectorAll('.edit-mode');
    let nonEditMode = tr.querySelectorAll('.editable');

    editMode.forEach(elem => elem.style.display = 'none');
    nonEditMode.forEach(elem => elem.style.display = 'inline-block');

    tr.querySelector('button:nth-last-of-type(1)').style.display = 'none';
    tr.querySelector('button:nth-last-of-type(2)').style.display = 'none';
    tr.querySelector('button:nth-last-of-type(3)').style.display = 'inline-block';
    tr.querySelector('button:nth-last-of-type(4)').style.display = 'inline-block';
}

function guardarCambios(button, id) {
    let tr = button.closest('tr');
    let inputs = tr.querySelectorAll('.edit-mode');
    let data = {
        id: id,
        identificador: inputs[0].value,
        sexo: inputs[1].value,
        raza: inputs[2].value,
        color: inputs[3].value,
        nacimiento: inputs[4].value,
        edad: inputs[5].value,
        partos: inputs[6].value
    };

    fetch('../config/actualizar_registro.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(result => {
            if (result.success) {
                let nonEditMode = tr.querySelectorAll('.editable');
                nonEditMode[0].innerText = data.identificador;
                nonEditMode[1].innerText = data.sexo;
                nonEditMode[2].innerText = data.raza;
                nonEditMode[3].innerText = data.color;
                nonEditMode[4].innerText = data.nacimiento;
                nonEditMode[5].innerText = calcularEdad(new Date(data.nacimiento));
                nonEditMode[6].innerText = data.partos;

                cancelarEdicion(button);
                Swal.fire({
                    title: "Exito",
                    text: "Registro guardado correctamente",
                    icon: "success"
                  });
            } else {
                console.error("Error del servidor: ", result.error);
                Swal.fire({
                    title: "Error",
                    text: "Error al actualizar el registro: "+ result.error,
                    icon: "error"
                  });
            }
        })
        .catch(error => {
            console.error("Error al guardar los cambios: ", error);
            Swal.fire({
                title: "Error",
                text: "Error al guardar los cambios.",
                icon: "error"
              });
        });
}

function confirmarEliminar(button, id) {
    Swal.fire({
        title: "¿Está seguro?",
        text: "¡No podrá revertir esta acción!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`../config/eliminar_registro.php?id=${id}`, {
                method: 'DELETE'
            })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        let tr = button.closest('tr');
                        tr.remove();
                        Swal.fire({
                            title: "¡Éxito!",
                            text: "Registro eliminado correctamente.",
                            icon: "success"
                        });
                    } else {
                        Swal.fire({
                            title: "Error",
                            text: "Hubo un problema al eliminar el registro.",
                            icon: "error"
                        });
                    }
                })
                .catch(() => {
                    Swal.fire({
                        title: "Error",
                        text: "Hubo un problema al eliminar el registro.",
                        icon: "error"
                    });
                });
        }
    });
}


function descargarReporte() {
    fetch('../config/descargar.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.blob();
        })
        .then(blob => {
            const linkDescarga = document.createElement('a');
            const fechaActual = new Date().toISOString().slice(0, 10);
            linkDescarga.href = URL.createObjectURL(blob);
            linkDescarga.download = `reporte_vacas_${fechaActual}.csv`;
            document.body.appendChild(linkDescarga);
            linkDescarga.click();
            document.body.removeChild(linkDescarga);
        })
        .catch(error => {
            console.error('Error al descargar el reporte:', error);
            Swal.fire({
                title: "Error",
                text: "Error al descargar el reporte. Por favor, inténtalo de nuevo.",
                icon: "error"
            });
        });
}
