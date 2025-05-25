<?php
require_once '../config/db_conn.php'; // archivo que contiene la conexión a la BD

$data = json_decode(file_get_contents("php://input"), true);

// Validación básica
if (!isset($data['id_vaca'], $data['identificador'], $data['lugar'], $data['movimiento'], $data['fecha_hora'])) {
    http_response_code(400);
    echo json_encode(["error" => "Datos incompletos."]);
    exit;
}

// Verificar si la vaca existe
$query = "SELECT id FROM registro_vaca WHERE id = $1 AND identificador = $2";
$result = pg_query_params($conexion, $query, [$data['id_vaca'], $data['identificador']]);

if (pg_num_rows($result) === 0) {
    http_response_code(404);
    echo json_encode(["error" => "Vaca no encontrada."]);
    exit;
}

// Insertar datos en ubicación
$insert = "INSERT INTO ubicacion (id_vaca, identificador, lugar, movimiento, fecha_hora) 
           VALUES ($1, $2, $3, $4, $5)";
$params = [
    $data['id_vaca'],
    $data['identificador'],
    $data['lugar'],
    $data['movimiento'],
    $data['fecha_hora']
];
$result = pg_query_params($conexion, $insert, $params);

if ($result) {
    echo json_encode(["success" => true]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Error al insertar en ubicación."]);
}
?>
