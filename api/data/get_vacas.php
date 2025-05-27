<?php
require_once '../../config/db_conn.php';

$query = "SELECT * FROM registro_vaca";
$result = pg_query($conexion, $query);

$datos = [];
while ($row = pg_fetch_assoc($result)) {
    $datos[] = $row;
}

header('Content-Type: application/json');
echo json_encode($datos);
?>
