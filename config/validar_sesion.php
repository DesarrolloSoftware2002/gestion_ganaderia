<?php
session_start();
if (!isset($_SESSION['nombre_usuario'])) {
    header("Location: ../views/login.php");
    exit;
}

require 'db_conn.php';

$usuario = $_SESSION['nombre_usuario'];
$query = "SELECT nombre, apellido FROM usuario WHERE usuario = '$usuario'";
$result = pg_query($conexion, $query);

if (!$result) {
    echo "Error en la consulta: " . pg_last_error();
    exit;
}

$user_data = pg_fetch_assoc($result);
$nombre_completo = $user_data['nombre'] . ' ' . $user_data['apellido'];


?>