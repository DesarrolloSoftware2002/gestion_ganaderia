<?php
require 'db_conn.php';

$query = "SELECT id, identificador, sexo, raza, color, nacimiento, partos, fecha_ingreso 
        FROM registro_vaca ORDER BY id";
$result = pg_query($conexion, $query);

if ($result) {
    $registros = pg_fetch_all($result);
    echo json_encode($registros);
} else {
    echo json_encode([]);
}
