<?php
require 'db_conn.php';

$identificador = isset($_GET['identificador']) ? $_GET['identificador'] : '';
$startDate = isset($_GET['start-date']) ? $_GET['start-date'] : '';
$endDate = isset($_GET['end-date']) ? $_GET['end-date'] : '';

// Construir la consulta
$query = "SELECT * FROM registro_salud WHERE 1=1";

if ($identificador != '') {
    $query .= " AND identificador = '$identificador'";
}

// Si se ha especificado una fecha de inicio, filtrar por esa fecha
if ($startDate != '') {
    $query .= " AND fecha_hora::date = '$startDate'";
}

$query .= " ORDER BY fecha_hora ASC";

// Ejecutar la consulta
$result = pg_query($conexion, $query);

if ($result) {
    $registros = pg_fetch_all($result);
    echo json_encode($registros);
} else {
    echo json_encode([]);
}
?>
