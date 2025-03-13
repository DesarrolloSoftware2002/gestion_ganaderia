function calcularEdad() {
    var fechaNacimiento = document.getElementById('fecha_nacimiento').value;
    var hoy = new Date();
    var nacimiento = new Date(fechaNacimiento);
    var edadAnios = hoy.getFullYear() - nacimiento.getFullYear();
    var edadMeses = hoy.getMonth() - nacimiento.getMonth();

    if (edadMeses < 0) {
        edadAnios--;
        edadMeses += 12;
    }

    if (hoy.getDate() < nacimiento.getDate()) {
        edadMeses--;
        if (edadMeses < 0) {
            edadAnios--;
            edadMeses += 12;
        }
    }

    var edadTexto = 'Edad: ' + edadAnios + ' años y ' + edadMeses + ' meses';
    document.getElementById('edad').innerText = edadTexto;
}

function togglePartos() {
    var sexo = document.getElementById('sexo').value;
    var numeroPartos = document.getElementById('numero_partos');
    if (sexo === 'hembra') {
        numeroPartos.disabled = false;
        numeroPartos.placeholder = '';
    } else {
        numeroPartos.disabled = true;
        numeroPartos.placeholder = '0';
    }
}

function verRegistros() {
    window.location.href = "../views/registrosAnimales.php";
}

document.getElementById('registro-form').addEventListener('submit', function (event) {
    event.preventDefault(); // Evitar el comportamiento predeterminado del formulario

    const formData = new FormData(this);

    fetch('../config/guardar_vaca.php', {
        method: 'POST',
        body: formData
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json(); // Intentar parsear la respuesta como JSON
        })
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: data.message,
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message,
                });
            }
        })
        .catch(error => {
            // Manejar errores de red o de formato JSON
            Swal.fire({
                icon: 'error',
                title: 'Error inesperado',
                text: 'Hubo un problema con el servidor. Por favor, verifica los datos e inténtalo de nuevo.',
            });
            console.error('Error:', error);
        });
});
