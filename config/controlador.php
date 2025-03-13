<?php
require 'db_conn.php';
session_start();

$usuario = pg_escape_string($conexion, $_POST['user']);
$contraseña = pg_escape_string($conexion, $_POST['pass']);

$query = "SELECT * FROM usuario WHERE usuario = '$usuario' AND contraseña = '$contraseña'";
$consulta = pg_query($conexion, $query);

if (!$consulta) {
    echo "Error en la consulta: " . pg_last_error();
    exit;
}

$cantidad = pg_num_rows($consulta);
if($cantidad > 0){
    $_SESSION['nombre_usuario'] = $usuario;
    header("location: ../views/pagPrincipal.php");
} else {
    header("location: ../views/login.php?error=1");
    exit;
}
?>
