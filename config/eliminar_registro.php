<?php
include 'db_conn.php'; // Conexión a la base de datos

$id = $_GET['id'];

$sql = "DELETE FROM registro_vaca WHERE id = $id";

if (pg_query($conexion, $sql)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>
