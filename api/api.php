<?php

class ApiGanado
{
    private $pdo;
    public function __construct()
    {
        $this->pdo = new PDO("pgsql:host=localhost;dbname=gestion_ganado", 'postgres', '1234');
    } 
    public function handleRequest($api)
    {
        // Establecer el tipo de contenido de respuesta JSON
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);

            if ($data) {
                // Procesar la solicitud POST y guardar los datos en la base de datos
                $this->saveDataToDatabase($api, $data);

                // Responder con un mensaje de éxito
                echo json_encode(['message' => 'Datos guardados!', 'status' => '200']);
            } else {
                echo json_encode(['message' => 'Los datos no fueron recibidos correctamente!', 'status' => '400']);
            }
        } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Procesar la solicitud GET y obtener datos de la base de datos
            $data = $this->getDataFromDatabase($api);

            // Responder con los datos obtenidos
            echo json_encode(['status' => '200', 'data' => $data]);
        } else {
            echo json_encode(['message' => 'Método no válido!', 'status' => '405']);
        }
    }

    private function getDataFromDatabase($api)
    {
        // Realizar la consulta a la base de datos y devolver los datos como un arreglo
        switch ($api) {
            case 'produccion_lechera':
                $stmt = $this->pdo->query('SELECT * FROM produccion_lechera ORDER BY id DESC');
                break;
            case 'registro_gps':
                $stmt = $this->pdo->query('SELECT * FROM registro_gps ORDER BY id DESC');
                break;
            case 'registro_salud':
                $stmt = $this->pdo->query('SELECT * FROM registro_salud ORDER BY id DESC');
                break;
            case 'ubicacion':
                $stmt = $this->pdo->query('SELECT * FROM ubicacion ORDER BY id DESC');
                break;
            default:
                throw new Exception('API no válida');
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function saveDataToDatabase($api, $data)
    {
        // Guardar los datos en la base de datos
        $query = '';
        $params = [];
        switch ($api) {
            case 'produccion_lechera':
                if (isset($data['id_vaca'], $data['identificador'], $data['fecha_hora'], $data['prod_leche'])) {
                    $query = '
                        INSERT INTO produccion_lechera (id_vaca, identificador, fecha_hora, prod_leche)
                        VALUES (:id_vaca, :identificador, :fecha_hora, :prod_leche)
                    ';
                    $params = [
                        ':id_vaca' => $data['id_vaca'],
                        ':identificador' => $data['identificador'],
                        ':fecha_hora' => $data['fecha_hora'],
                        ':prod_leche' => $data['prod_leche']
                    ];
                } else {
                    echo json_encode(['message' => 'Datos incompletos para produccion_lechera!', 'status' => '400']);
                    return;
                }
                break;
            case 'registro_gps':
                if (isset($data['id_vaca'], $data['identificador'], $data['gps'], $data['fecha_hora'])) {
                    $query = '
                        INSERT INTO registro_gps (id_vaca, identificador, gps, fecha_hora)
                        VALUES (:id_vaca, :identificador, :gps, :fecha_hora)
                    ';
                    $params = [
                        ':id_vaca' => $data['id_vaca'],
                        ':identificador' => $data['identificador'],
                        ':gps' => $data['gps'],
                        ':fecha_hora' => $data['fecha_hora']
                    ];
                } else {
                    echo json_encode(['message' => 'Datos incompletos para registro_gps!', 'status' => '400']);
                    return;
                }
                break;
            case 'registro_salud':
                if (isset($data['id_vaca'], $data['identificador'], $data['temperatura'], 
                    $data['frecuencia_cardiaca'], $data['fecha_hora'])) {
                    $query = '
                        INSERT INTO registro_salud (id_vaca, identificador, temperatura, frecuencia_cardiaca, 
                        fecha_hora)
                        VALUES (:id_vaca, :identificador, :temperatura, :frecuencia_cardiaca, :fecha_hora)
                    ';
                    $params = [
                        ':id_vaca' => $data['id_vaca'],
                        ':identificador' => $data['identificador'],
                        ':temperatura' => $data['temperatura'],
                        ':frecuencia_cardiaca' => $data['frecuencia_cardiaca'],
                        ':fecha_hora' => $data['fecha_hora']
                    ];
                } else {
                    echo json_encode(['message' => 'Datos incompletos para registro_salud!', 'status' => '400']);
                    return;
                }
                break;
            case 'ubicacion':
                if (isset($data['id_vaca'], $data['identificador'], $data['lugar'], $data['movimiento'], 
                $data['fecha_hora'])) {
                    $query = '
                        INSERT INTO ubicacion (id_vaca, identificador, lugar, movimiento, fecha_hora)
                        VALUES (:id_vaca, :identificador, :lugar, :movimiento, :fecha_hora)
                    ';
                    $params = [
                        ':id_vaca' => $data['id_vaca'],
                        ':identificador' => $data['identificador'],
                        ':lugar' => $data['lugar'],
                        ':movimiento' => $data['movimiento'],
                        ':fecha_hora' => $data['fecha_hora']
                    ];
                } else {
                    echo json_encode(['message' => 'Datos incompletos para ubicacion!', 'status' => '400']);
                    return;
                }
                break;
            default:
                echo json_encode(['message' => 'API no válida!', 'status' => '400']);
                return;
        }

        $stmt = $this->pdo->prepare($query);
        if ($stmt->execute($params)) {
            echo json_encode(['message' => 'Datos guardados correctamente!', 'status' => '200']);
        } else {
            echo json_encode(['message' => 'Error al guardar los datos!', 'status' => '500']);
        }
    }
}