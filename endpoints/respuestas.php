<?php
// Archivo: endpoints/respuestas.php

// 1. Headers del API (ESENCIALES)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS"); // GET, POST, PUT (para marcar solución)
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// 2. Manejar la solicitud OPTIONS
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// 3. Incluir la conexión a la Base de Datos
include_once '../config/Database.php';

$database = new Database();
$db = $database->getConnection();

// 4. Determinar el método de la solicitud
$method = $_SERVER['REQUEST_METHOD'];

// 5. Njbdo l-data li jat f-l-URL
// Ghadi n7tajo bzzaf l-ID dyal l-consulta
$id_consulta = isset($_GET['id_consulta']) ? $_GET['id_consulta'] : null;

switch ($method) {
    // ===================================
    // CASO 1: El cliente quiere LISTAR las respuestas (Método GET)
    // ===================================
    case 'GET':
        // Khass darori y3tina l-ID dyal l-mochkil
        if ($id_consulta) {
            // Njbdo l-ajwiba w l-ma3lomat dyal l-user li ktbhom
            $query = "SELECT 
                        r.id_respuesta, r.descripcion_respuesta, r.es_solucion, r.fecha_respuesta,
                        u.nombre_usuario, u.puntos_reputacion
                      FROM Respuestas r
                      JOIN Usuarios u ON r.id_usuario = u.id_usuario
                      WHERE r.id_consulta = :id_consulta
                      ORDER BY r.es_solucion DESC, r.fecha_respuesta ASC"; // L-7al l-mzyan howa l-fowwal

            $stmt = $db->prepare($query);
            $stmt->bindParam(':id_consulta', $id_consulta);
            $stmt->execute();

            $respuestas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($respuestas) > 0) {
                http_response_code(200); // OK
                echo json_encode($respuestas);
            } else {
                http_response_code(404); // Not Found
                echo json_encode(array("message" => "No se encontraron respuestas para esta consulta."));
            }
        } else {
            http_response_code(400); // Bad Request
            echo json_encode(array("message" => "Falta el id_consulta en la URL."));
        }
        break;

    // ===================================
    // CASO 2: El cliente quiere AÑADIR una respuesta (Método POST)
    // ===================================
    case 'POST':
        // Nqraw l-data li jat men React (JSON)
        $data = json_decode(file_get_contents("php://input"));

        // N-vérifiw l-data
        if (
            !empty($data->id_consulta) &&
            !empty($data->id_usuario) && // (Hada khasso yji men l-TOKEN)
            !empty($data->descripcion_respuesta)
        ) {
            $query = "INSERT INTO Respuestas (id_consulta, id_usuario, descripcion_respuesta)
                      VALUES (:id_consulta, :id_usuario, :descripcion)";

            $stmt = $db->prepare($query);

            $stmt->bindParam(':id_consulta', $data->id_consulta);
            $stmt->bindParam(':id_usuario', $data->id_usuario);
            $stmt->bindParam(':descripcion', $data->descripcion_respuesta);

            if ($stmt->execute()) {
                http_response_code(201); // Creado
                echo json_encode(array("message" => "Respuesta añadida exitosamente."));
            } else {
                http_response_code(503); // Servicio No Disponible
                echo json_encode(array("message" => "No se pudo añadir la respuesta."));
            }
        } else {
            http_response_code(400); // Solicitud Incorrecta
            echo json_encode(array("message" => "Datos incompletos."));
        }
        break;

    // ===================================
    // CASO 3: El cliente quiere MARCAR una respuesta como SOLUCIÓN (Método PUT)
    // ===================================
    case 'PUT':
        // Hada l-endpoint dyal l-boton [✅ Marcar como Solución]
        // Khassna l-ID dyal l-jawab
        $id_respuesta = isset($_GET['id_respuesta']) ? $_GET['id_respuesta'] : null;

        if ($id_respuesta) {
            // (Hna khassna normalment n-vérifiw wach l-user li kaydir had l-PUT
            // howa b-sifto mol l-MOCHKIL l-asli... Walakin daba gha nqbloha)

            $query = "UPDATE Respuestas 
                      SET es_solucion = true 
                      WHERE id_respuesta = :id_respuesta";

            $stmt = $db->prepare($query);
            $stmt->bindParam(':id_respuesta', $id_respuesta);

            if ($stmt->execute()) {
                http_response_code(200); // OK
                echo json_encode(array("message" => "Respuesta marcada como solución."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "No se pudo actualizar la respuesta."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Falta el id_respuesta en la URL."));
        }
        break;

    // ===================================
    // CASO 4: Otro método
    // ===================================
    default:
        http_response_code(405); // Método No Permitido
        echo json_encode(array("message" => "Método no permitido."));
        break;
}
?>