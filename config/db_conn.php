<?php
$host = 'localhost';
$db = 'gestion_ganado';
$user = 'postgres';
$password = '1234';

$conexion = pg_connect("host=$host dbname=$db user=$user password=$password");

if (!$conexion) {
    die("Error en la conexión a la base de datos: " . pg_last_error());
}
?>
