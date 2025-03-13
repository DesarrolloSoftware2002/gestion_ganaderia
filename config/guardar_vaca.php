<?php
require "../config/db_conn.php"; // Archivo de conexión a la base de datos

header('Content-Type: application/json'); // Asegura que la respuesta sea JSON

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $sexo = $_POST['sexo'];
    $raza = $_POST['raza'];
    $color = $_POST['color'];
    $fechaNacimiento = $_POST['fecha_nacimiento'];
    $numeroPartos = isset($_POST['numero_partos']) ? $_POST['numero_partos'] : 0;

    // Validar el campo 'sexo'
    if ($sexo !== 'hembra' && $sexo !== 'macho') {
        echo json_encode([
            'success' => false,
            'message' => 'Error: Sexo no válido. Debe ser Hembra o Macho.',
        ]);
        exit; // Detener la ejecución del script
    }

    try {
        // Consulta SQL parametrizada con pg_query_params
        $sql = "INSERT INTO registro_vaca (identificador, sexo, raza, color, nacimiento, partos, fecha_ingreso)
                VALUES ($1, $2, $3, $4, $5, $6, CURRENT_TIMESTAMP)";
        
        $result = pg_query_params($conexion, $sql, [
            $id,
            $sexo,
            $raza,
            $color,
            $fechaNacimiento,
            $numeroPartos
        ]);

        if ($result) {
            // Respuesta en caso de éxito
            echo json_encode([
                'success' => true,
                'message' => 'Registro guardado exitosamente.',
            ]);
        } else {
            // Captura errores de ejecución
            echo json_encode([
                'success' => false,
                'message' => 'Error al guardar el registro: ' . pg_last_error($conexion),
            ]);
        }
    } catch (Exception $e) {
        // Captura errores generales
        echo json_encode([
            'success' => false,
            'message' => 'Error inesperado: ' . $e->getMessage(),
        ]);
    }
} else {
    // Respuesta si no es un método POST
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido.',
    ]);
}
?>
