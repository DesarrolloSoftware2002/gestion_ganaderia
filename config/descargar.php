<?php
// Incluir el archivo de conexión a la base de datos
include 'db_conn.php';

// Consulta para obtener los datos de las vacas
$sql = "SELECT id, identificador, sexo, raza, color, nacimiento, partos, fecha_ingreso FROM registro_vaca";
$resultado = pg_query($conexion, $sql);

if (!$resultado) {
    die("Error al obtener datos: " . pg_last_error($conexion));
}

// Crear el contenido del archivo CSV
$contenidoCSV = "ID Vaca,Identificador,Sexo,Raza,Color,Fecha de Nacimiento,Número de Partos,Fecha de Ingreso\n";

while ($fila = pg_fetch_assoc($resultado)) {
    // Limpiar los datos para evitar problemas con las comas en el contenido
    $id = $fila['id'];
    $identificador = limpiarCSV($fila['identificador']);
    $sexo = limpiarCSV($fila['sexo']);
    $raza = limpiarCSV($fila['raza']);
    $color = limpiarCSV($fila['color']);
    $nacimiento = $fila['nacimiento'];
    $partos = $fila['partos'];
    $fecha_ingreso = $fila['fecha_ingreso'];

    // Formatear la fecha de nacimiento si es necesario
    // Por ejemplo: $nacimiento = date('Y-m-d', strtotime($nacimiento));

    // Concatenar los datos en una línea CSV
    $lineaCSV = "$id,$identificador,$sexo,$raza,$color,$nacimiento,$partos,$fecha_ingreso\n";
    $contenidoCSV .= $lineaCSV;
}

// Cerrar la conexión a la base de datos
pg_close($conexion);

// Función para limpiar los datos para el CSV (eliminar comas y otros caracteres problemáticos)
function limpiarCSV($valor) {
    // Eliminar comas y otros caracteres problemáticos aquí si es necesario
    return str_replace(',', '', $valor);
}

$fecha = ('Y-m-d');
// Definir el nombre del archivo con la fecha actual
$nombreArchivo = 'reporte_vacas_' . $fecha . '.csv';

// Configurar las cabeceras HTTP para descargar el archivo CSV
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $nombreArchivo . '"');

// Imprimir el contenido del archivo CSV
echo $contenidoCSV;
?>
