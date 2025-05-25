<?php
require "../config/validar_sesion.php";
?>
<!DOCTYPE html>

<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registros de Vacas</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="shortcut icon" href="/images/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/animales.css">
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
                <a href="registrosUbicacion.php">Registros de Ubicación</a>
            </div>
        </div>
        <a href="../config/logout.php" onclick="cerrarSesion()">Cerrar Sesión</a>
    </div>
    <div id="button-container">
        <button onclick="history.back()">
            <i class="fas fa-arrow-left"></i> Atrás
        </button>
        <button onclick="descargarReporte()">
            <i class="fas fa-download"></i> Descargar Reporte
        </button>
    </div>
    <div id="results-table-container">
        <table id="results-table">
            <thead>
                <tr>
                    <th>ID Vaca</th>
                    <th>Identificador</th>
                    <th>Sexo</th>
                    <th>Raza</th>
                    <th>Color</th>
                    <th>Fecha de Nacimiento</th>
                    <th>Edad</th>
                    <th>Número de Partos</th>
                    <th>Fecha de Ingreso</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
    </div>
    <script src="../js/script_modificarEliminar.js"></script>
</body>

</html>