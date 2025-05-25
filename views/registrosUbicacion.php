<?php
require "../config/validar_sesion.php";
require_once '../config/db_conn.php';

$query = "SELECT identificador FROM registro_vaca";
$result = pg_query($conexion, $query);
$identificadores = [];
while ($row = pg_fetch_assoc($result)) {
    $identificadores[] = $row['identificador'];
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Gestión Datos Ganadería</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="shortcut icon" href="/images/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/ubicacion.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div id="menu">
    <span><a href="pagPrincipal.php">SGDGV</a></span>
        <div class="dropdown">
            <a href="#" class="goto">Ir a</a>
            <div class="dropdown-content">
                <a href="pagPrincipal.php">Página Principal</a>
                <a href="registrarVaca.php">Registrar animal</a>
                <a href="registrosSalud.php">Registros de salud</a>
                <a href="registrosProduccionLeche.php">Registros de Producción Lechera</a>
                <a href="registrosGPS.php">Registros de GPS</a>
            </div>
        </div>
        <a href="../config/logout.php" onclick="cerrarSesion()">Cerrar Sesión</a>
    </div>

    <div id="nav-form">
        <h2>Registros de Ubicación</h2>
        <div class="form-group">
            <label for="identificador">Identificador de Animal:</label>
            <select id="identificador" name="identificador">
                <option value="">Seleccione un identificador</option>
                <?php foreach ($identificadores as $identificador) : ?>
                    <option value="<?php echo $identificador; ?>"><?php echo $identificador; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="start-date">Fecha Inicio:</label>
            <input type="date" id="start-date" name="start-date">
        </div>
        <div class="form-group">
            <label for="end-date">Fecha Fin:</label>
            <input type="date" id="end-date" name="end-date">
        </div>
        <div class="button-group">
            <button type="button" onclick="generarReporte()">
                Generar Reporte
            </button>
            <button type="button" onclick="descargarReporte()">
                Descargar
            </button>
            <button type="button" onclick="abrirModalEstadistica()">
                Estadística</button>
        </div>
    </div>

    <div id="results-table-container">
        <table id="results-table">
            <thead>
                <tr>
                    <th>ID Vaca</th>
                    <th>Identificador</th>
                    <th>Lugar</th>
                    <th>Movimiento</th>
                    <th>Fecha/Hora</th>
                </tr>
            </thead>
            <tbody>
                <!-- Aquí se agregarán las filas con los datos -->
            </tbody>
        </table>
    </div>
    <div id="modalEstadistica" style="display:none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); justify-content: center; align-items: center;">
        <div style="background-color: white; padding: 20px; border-radius: 8px; width: 400px;">
            <h2>Consulta Estadística</h2>
            <h3>Consulta por el identificador de la vaca,
                que tiempo ha estado en algun lugar
            </h3>
            <div class="form-group">
                <label for="identificador-estadistica">Identificador de Animal:</label>
                <select id="identificador-estadistica" name="identificador-estadistica">
                    <option value="">Seleccione un identificador</option>
                    <?php foreach ($identificadores as $identificador) : ?>
                        <option value="<?php echo $identificador; ?>"><?php echo $identificador; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="fecha-estadistica">Fecha:</label>
                <input type="date" id="fecha-estadistica" name="fecha-estadistica">
            </div>
            <button type="button" onclick="consultarEstadistica()">Consultar</button>
            <button type="button" onclick="cerrarModalEstadistica()">Cerrar</button>

            <!-- Contenedor para la tabla de resultados dentro del modal -->
            <div id="estadistica-resultados" style="margin-top: 20px;"></div>
        </div>
    </div>

    <script src="../js/script_ubicacion.js"></script>
    <!-- <script>
        // Función para desplazar hacia arriba
        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        // Mostrar el botón cuando el usuario hace scroll hacia abajo 20px desde la parte superior
        window.onscroll = function() {
            toggleScrollButton()
        };

        function toggleScrollButton() {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                document.getElementById("scroll-to-top").style.display = "block";
            } else {
                document.getElementById("scroll-to-top").style.display = "none";
            }
        }
    </script> -->
</body>

</html>