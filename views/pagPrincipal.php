<?php
require "../config/validar_sesion.php";
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Gestión Datos Ganadería</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="shortcut icon" href="/images/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/principal.css">
</head>

<body>
    <div id="menu">
        <span><a href="pagPrincipal.php">SGDGV</a></span>
        <a href="../config/logout.php">Cerrar Sesión</a>
    </div>
    
     <span id="bienvenida">Bienvenido, <?php echo $nombre_completo; ?></span>


    <div id="contenido">
        <div class="seccion" id="registro" onclick="location.href='registrarVaca.php'">
            <h2 class="nombre-seccion">Registrar Nuevo Ganado Vacuno</h2>
        </div>
        <div class="seccion" id="ubicacion" onclick="location.href='registrosUbicacion.php'">
            <h2 class="nombre-seccion">Registros de Ubicación</h2>
        </div>
        <div class="seccion" id="consulta" onclick="location.href='registrosGPS.php'">
            <h2 class="nombre-seccion">Registros de GPS</h2>
        </div>
        <div class="seccion" id="salud" onclick="location.href='registrosSalud.php'">
            <h2 class="nombre-seccion">Registros de Salud</h2>
        </div>
        <div class="seccion" id="produccion" onclick="location.href='registrosProduccionLeche.php'">
            <h2 class="nombre-seccion">Producción de Leche</h2>
        </div>
    </div>
</body>



</html>