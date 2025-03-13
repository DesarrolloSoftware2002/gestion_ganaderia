<?php
require 'db_conn.php';

$identificador = isset($_GET['identificador']) ? $_GET['identificador'] : '';
$startDate = isset($_GET['start-date']) ? $_GET['start-date'] : '';
$endDate = isset($_GET['end-date']) ? $_GET['end-date'] : '';

$query = "SELECT * FROM produccion_lechera WHERE 1=1";

if ($identificador != '') {
    $query .= " AND identificador = '$identificador'";
}
if ($startDate != '') {
    $query .= " AND fecha_hora >= '$startDate'";
}
if ($endDate != '') {
    $query .= " AND fecha_hora <= DATE '$endDate' + INTERVAL '1 day' - INTERVAL '1 second'";
}

$query .= " ORDER BY fecha_hora ASC"; // Cambia a ASC para ordenar ascendentemente
$result = pg_query($conexion, $query);

$registros = [];
if ($result) {
    $registros = pg_fetch_all($result);
}

echo json_encode($registros);
?>
