<?php
require "../config/db_conn.php";
require "../config/validar_sesion.php";

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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="../css/salud.css">
</head>

<body>
    <div id="menu">
    <span><a href="/SGAGV/views/pagPrincipal.php">SGDGV</a></span>
        <div class="dropdown">
            <a href="#" class="goto">Ir a</a>
            <div class="dropdown-content">
                <a href="pagPrincipal.php">Página Principal</a>
                <a href="registrarVaca.php">Registrar animal</a>
                <a href="registrosProduccionLeche.php">Registros de Producción Lechera</a>
                <a href="registrosGPS.php">Registros GPS</a>
                <a href="registrosUbicacion.php">Registros de Ubicación</a>
            </div>
        </div>
        <a href="../config/logout.php" onclick="cerrarSesion()">Cerrar Sesión</a>
    </div>

    <div id="nav-form">
        <h2>Registros de Salud</h2>
        <div class="form-row">
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
                <label for="filter-date">Seleccionar día específico:</label>
                <input type="date" id="filter-date">
            </div>
        </div>
        <div class="button-group">
            <button onclick="generarReporte()">
                <i class="fas fa-chart-line"></i> Generar Datos
            </button>
            <button onclick="descargarReporte()">
                <i class="fas fa-download"></i> Descargar
            </button>
            <button onclick="mostrarGraficas()">
                <i class="fas fa-chart-bar"></i> Mostrar Gráficas
            </button>
        </div>
    </div>

    <div id="results-table-container">
        <table id="results-table">
            <thead>
                <tr>
                    <th>ID Vaca</th>
                    <th>Identificador</th>
                    <th>Temperatura</th>
                    <th>Frecuencia Cardíaca</th>
                    <th>Fecha y Hora</th>
                </tr>
            </thead>
            <tbody>
                <!-- Aquí se agregarán las filas con los datos -->
            </tbody>
        </table>
    </div>

    <div id="chart-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div id="chart-container">
                <canvas id="chart-temperature"></canvas>
                <canvas id="chart-heart-rate"></canvas>
                <button id="download-chart-btn">Descargar Gráficas</button>
            </div>
        </div>
    </div>

    <script>
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
    </script>


<!--Boton Scroll-->
    <button onclick="scrollToTop()" id="scroll-to-top" title="Ir arriba"
        style="border: none; display: none; left: 20px; margin-top: -100px;">
    <img src="../images/flecha-hacia-arriba.png" alt="" style="width: 40px; height: 40;"></button>



    <script src="../js/script_salud.js"></script>
</body>

</html>
