<?php
require 'db_conn.php';

$query = "SELECT COUNT(*) AS total FROM registro_vaca";
$result = pg_query($conexion, $query);

if ($result) {
    $row = pg_fetch_assoc($result);
    echo json_encode(['cantidad' => $row['total']]);
} else {
    echo json_encode(['cantidad' => 0]);
}
?>