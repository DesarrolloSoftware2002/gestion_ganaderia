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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="../css/leche.css">
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
                <a href="registrosGPS.php">Registros de GPS</a>
                <a href="registrosUbicacion.php">Registros de Ubicación</a>
            </div>
        </div>
        <a href="../config/logout.php" onclick="cerrarSesion()">Cerrar Sesión</a>
    </div>

    <div id="nav-form">
        <h2>Registros de Producción Lechera</h2>
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

        <div class="form-group button-group">
            <button onclick="generarReporte()">
                <i class="fas fa-chart-line"></i> Generar Reporte
            </button>
            <button onclick="descargarReporte()">
                <i class="fas fa-download"></i> Descargar
            </button>
            <button onclick="mostrarGrafica()">
                <i class="fas fa-chart-bar"></i> Mostrar Gráfica
            </button>
        </div>
    </div>

    <div id="results-table-container">
        <table id="results-table">
            <thead>
                <tr>
                    <th>ID Vaca</th>
                    <th>Identificador</th>
                    <th>Producción Lechera (litros)</th>
                    <th>Fecha/Hora</th>
                </tr>
            </thead>
            <tbody>
                <!-- Aquí se agregarán las filas con los datos -->
            </tbody>
        </table>
    </div>

    <!-- Modal para la gráfica -->
    <div id="chart-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span> 
            <div id="chart-container">
                <canvas id="chart"></canvas>
            </div>
            <button id="download-chart-btn">Descargar Gráfica</button>
        </div>
    </div>
   

    <script src="../js/script_ProduccionLeche.js"></script>

</body>

</html>
