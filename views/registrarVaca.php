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
    <link rel="stylesheet" href="../css/regvaca.css">
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

    <div id="form-container">
        <form id="registro-form" action="../config/guardar_vaca.php" method="post">
            <label for="id">Identificador:</label>
            <input type="text" id="id" name="id" required>

            <label for="sexo">Sexo:</label>
            <select id="sexo" name="sexo" onchange="togglePartos()" required>
                <option>Seleccione</option>
                <option value="macho">Macho</option>
                <option value="hembra">Hembra</option>
            </select>

            <label for="raza">Raza:</label>
            <input type="text" id="raza" name="raza" required>

            <label for="color">Color:</label>
            <input type="text" id="color" name="color">

            <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" onchange="calcularEdad()" required>

            <label id="edad"></label>

            <label for="numero_partos">Número de Partos:</label>
            <input type="number" id="numero_partos" name="numero_partos" disabled>
        </form>
        <div id="botones">
            <button type="submit" form="registro-form">Registrar</button>
            <button type="button" onclick="verRegistros()">Ver Todos los Registros</button>
        </div>
    </div>

    <script src="../js/script_registroVaca.js"></script>

</body>

</html>
