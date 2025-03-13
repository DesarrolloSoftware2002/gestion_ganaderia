<?php
include 'db_conn.php'; // Conexión a la base de datos
header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id'], $data['identificador'], $data['sexo'], $data['raza'], 
    $data['color'], $data['nacimiento'], $data['partos'])) {
    $id = $data['id'];
    $identificador = $data['identificador'];
    $sexo = $data['sexo'];
    $raza = $data['raza'];
    $color = $data['color'];
    $nacimiento = $data['nacimiento'];
    $partos = $data['partos'];

    $sql = "UPDATE registro_vaca SET identificador = '$identificador', 
    sexo = '$sexo', raza = '$raza', color = '$color', nacimiento = '$nacimiento', 
    partos = '$partos' WHERE id = $id";

    if (pg_query($conexion, $sql)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => pg_last_error($conexion)]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
}

// Cerrar la conexión
pg_close($conexion);
?>
