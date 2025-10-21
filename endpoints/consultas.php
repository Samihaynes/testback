<?php
// Archivo: endpoints/consultas.php

// (Headers ... b7al b7al)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { /* ... (b7al b7al) ... */ }
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

include_once '../config/Database.php';
$database = new Database();
$db = $database->getConnection();
$method = $_SERVER['REQUEST_METHOD'];

// (Nqraw l-ID men l-URL)
$id_consulta = isset($_GET['id_consulta']) ? $_GET['id_consulta'] : null;

switch ($method) {
    // ===================================
    // CASO 1: GET (MBEDDEL / UPDATED)
    // ===================================
    case 'GET':
        // HNA T-TBDIL: N-checkiw wach 3andna ID
        if ($id_consulta) {
            // 1. ILA 3ANDNA ID: Njbdo mochkil wa7ed
            $query = "SELECT c.*, u.nombre_usuario, v.marca, v.modelo, v.motor
                      FROM Consultas c
                      JOIN Usuarios u ON c.id_usuario = u.id_usuario
                      JOIN Vehiculos v ON c.id_vehiculo = v.id_vehiculo
                      WHERE c.id_consulta = :id_consulta";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':id_consulta', $id_consulta);
            $stmt->execute();
            $consulta = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch wa7ed

            if ($consulta) {
                http_response_code(200);
                echo json_encode($consulta);
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "Consulta no encontrada."));
            }

        } else {
            // 2. ILA MA 3ANDNACH ID: Njbdo kolchi (kifma kan f-l-awwal)
            $query = "SELECT c.id_consulta, c.titulo, c.estado,
                             u.nombre_usuario,
                             v.marca, v.modelo, v.motor
                      FROM Consultas c
                      JOIN Usuarios u ON c.id_usuario = u.id_usuario
                      JOIN Vehiculos v ON c.id_vehiculo = v.id_vehiculo
                      ORDER BY c.fecha_publicacion DESC";
            
            $stmt = $db->prepare($query);
            $stmt->execute();
            $consultas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($consultas) > 0) {
                http_response_code(200);
                echo json_encode($consultas);
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "No se encontraron consultas."));
            }
        }
        break;

    // ===================================
    // CASO 2: POST (Kaybqa b7al b7al)
    // ===================================
    case 'POST':
        // (L-Code dyal POST kaybqa kifma howa, ma kaytbeddelch)
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->titulo) && !empty($data->id_usuario) && !empty($data->vehiculo->vin)) {
             try {
                // 1. Nqllbo wach l-VIN déja kayn f-DB
                $query_vin = "SELECT id_vehiculo FROM Vehiculos WHERE vin = :vin LIMIT 1";
                $stmt_vin = $db->prepare($query_vin);
                $stmt_vin->bindParam(':vin', $data->vehiculo->vin);
                $stmt_vin->execute();
                if ($stmt_vin->rowCount() > 0) {
                    $row_vin = $stmt_vin->fetch(PDO::FETCH_ASSOC);
                    $id_vehiculo = $row_vin['id_vehiculo'];
                } else {
                    // 3. Ma lqinach l-VIN: N-creeiw tomobil jdida
                    $query_new_vin = "INSERT INTO Vehiculos (vin, marca, modelo, ano, motor) VALUES (:vin, :marca, :modelo, :ano, :motor)";
                    $stmt_new_vin = $db->prepare($query_new_vin);
                    // (Bind params...)
                    $stmt_new_vin->bindParam(':vin', $data->vehiculo->vin);
                    $stmt_new_vin->bindParam(':marca', $data->vehiculo->marca);
                    $stmt_new_vin->bindParam(':modelo', $data->vehiculo->modelo);
                    $stmt_new_vin->bindParam(':ano', $data->vehiculo->ano);
                    $stmt_new_vin->bindParam(':motor', $data->vehiculo->motor);
                    $stmt_new_vin->execute();
                    $id_vehiculo = $db->lastInsertId();
                }
                // --- DABA N-CREEIW L-CONSULTA (L-MOCHKIL) ---
                if ($id_vehiculo > 0) {
                    $query_consulta = "INSERT INTO Consultas (id_usuario, id_vehiculo, titulo, descripcion, categoria) VALUES (:id_usuario, :id_vehiculo, :titulo, :descripcion, :categoria)";
                    $stmt_consulta = $db->prepare($query_consulta);
                    // (Bind params...)
                    $stmt_consulta->bindParam(':id_usuario', $data->id_usuario);
                    $stmt_consulta->bindParam(':id_vehiculo', $id_vehiculo);
                    $stmt_consulta->bindParam(':titulo', $data->titulo);
                    $stmt_consulta->bindParam(':descripcion', $data->descripcion);
                    $stmt_consulta->bindParam(':categoria', $data->categoria);
                    if ($stmt_consulta->execute()) {
                        http_response_code(201); // Created
                        echo json_encode(array("message" => "Consulta creada exitosamente."));
                    } else {
                        http_response_code(503);
                        echo json_encode(array("message" => "No se pudo crear la consulta."));
                    }
                }
            } catch (Exception $e) { /* ... (b7al b7al) ... */ }
        } else { /* ... (b7al b7al) ... */ }
        break;
    
    // (Default kaybqa b7al b7al)
    default:
        http_response_code(405); 
        echo json_encode(array("message" => "Método no permitido."));
        break;
}
?>