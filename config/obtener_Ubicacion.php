<?php
require 'db_conn.php';

$identificador = isset($_GET['identificador']) ? $_GET['identificador'] : '';
$startDate = isset($_GET['start-date']) ? $_GET['start-date'] : '';
$endDate = isset($_GET['end-date']) ? $_GET['end-date'] : '';

if ($startDate != '') {
    $startDate = date('Y-m-d 00:00:00', strtotime($startDate));
}
if ($endDate != '') {
    $endDate = date('Y-m-d 23:59:59', strtotime($endDate));
}

$query = "SELECT * FROM ubicacion WHERE 1=1";

if ($identificador != '') {
    $query .= " AND identificador = '$identificador'";
}
if ($startDate != '') {
    $query .= " AND fecha_hora >= '$startDate'";
}
if ($endDate != '') {
    $query .= " AND fecha_hora <= '$endDate'";
}

$query .= " ORDER BY fecha_hora ASC";
$result = pg_query($conexion, $query);

if ($result) {
    $registros = pg_fetch_all($result);
    echo json_encode($registros);
} else {
    echo json_encode([]);
}
?>
