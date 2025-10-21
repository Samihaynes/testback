<?php
// Archivo: endpoints/votos.php

// 1. Headers del API (ESENCIALES)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS"); // Solo POST y OPTIONS
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

// 4. Asegurarse de que es un método POST
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    http_response_code(405); // Método No Permitido
    echo json_encode(array("message" => "Método no permitido."));
    exit();
}

// 5. Leer los datos JSON recibidos de React
$data = json_decode(file_get_contents("php://input"));

// 6. Validar que todos los datos necesarios están presentes
if (
    !empty($data->id_respuesta) &&
    !empty($data->id_usuario) && // (Esto debería venir del Token)
    !empty($data->tipo_voto) && ($data->tipo_voto == 'up' || $data->tipo_voto == 'down')
) {
    
    // 7. Esta es la consulta clave:
    // Intenta insertar un nuevo voto.
    // Si la llave ÚNICA (id_respuesta, id_usuario) ya existe,
    // simplemente actualiza el tipo_voto al nuevo valor.
    $query = "INSERT INTO Votos (id_respuesta, id_usuario, tipo_voto)
              VALUES (:id_respuesta, :id_usuario, :tipo_voto)
              ON DUPLICATE KEY UPDATE
                tipo_voto = :tipo_voto_update";

    $stmt = $db->prepare($query);

    // Bind (enlazar) los parámetros
    $stmt->bindParam(':id_respuesta', $data->id_respuesta);
    $stmt->bindParam(':id_usuario', $data->id_usuario);
    $stmt->bindParam(':tipo_voto', $data->tipo_voto);
    $stmt->bindParam(':tipo_voto_update', $data->tipo_voto); // Para la parte de UPDATE

    if ($stmt->execute()) {
        http_response_code(201); // Creado (o Actualizado)
        echo json_encode(array("message" => "Voto registrado/actualizado exitosamente."));
    } else {
        http_response_code(503); // Servicio No Disponible
        echo json_encode(array("message" => "No se pudo registrar el voto."));
    }
} else {
    http_response_code(400); // Solicitud Incorrecta
    echo json_encode(array("message" => "Datos incompletos o 'tipo_voto' inválido."));
}
?>