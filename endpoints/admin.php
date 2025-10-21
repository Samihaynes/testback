<?php
// Archivo: endpoints/admin.php

// 1. Headers del API (ESENCIALES)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, PUT, DELETE, OPTIONS"); // Métodos para gestionar usuarios
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

// 5. Leer los datos JSON (para PUT)
$data = json_decode(file_get_contents("php://input"));

// 6. Leer el ID del usuario de la URL (para PUT y DELETE)
$id_usuario = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : null;

// ===================================================================
// IMPORTANTE: Seguridad (Placeholder)
// ===================================================================
// TODO:
// En un proyecto real, aquí iría la verificación del Token JWT.
// Se debe comprobar que el usuario que hace la petición tiene rol='admin'.
// Si no es 'admin', se debe detener la ejecución con:
// http_response_code(403); // Prohibido (Forbidden)
// echo json_encode(array("message" => "Acceso denegado."));
// exit();
// ===================================================================


switch ($method) {
    // ===================================
    // CASO 1: Listar todos los Usuarios (Método GET)
    // ===================================
    case 'GET':
        // Seleccionamos los campos más relevantes (nunca la contraseña)
        $query = "SELECT id_usuario, nombre_usuario, email, rol, fecha_registro, puntos_reputacion 
                  FROM Usuarios 
                  ORDER BY fecha_registro DESC";
        
        $stmt = $db->prepare($query);
        $stmt->execute();
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        http_response_code(200); // OK
        echo json_encode($usuarios);
        break;

    // ===================================
    // CASO 2: Actualizar el Rol de un Usuario (Método PUT)
    // ===================================
    case 'PUT':
        // El Admin quiere cambiar el rol de un usuario (ej: de 'usuario' a 'admin')
        
        if ($id_usuario && !empty($data->rol) && ($data->rol == 'admin' || $data->rol == 'usuario')) {
            
            $query = "UPDATE Usuarios 
                      SET rol = :rol 
                      WHERE id_usuario = :id_usuario";

            $stmt = $db->prepare($query);
            $stmt->bindParam(':rol', $data->rol);
            $stmt->bindParam(':id_usuario', $id_usuario);

            if ($stmt->execute()) {
                http_response_code(200); // OK
                echo json_encode(array("message" => "Rol del usuario actualizado."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "No se pudo actualizar el rol."));
            }
        } else {
            http_response_code(400); // Bad Request
            echo json_encode(array("message" => "Falta id_usuario en la URL o el 'rol' es inválido."));
        }
        break;

    // ===================================
    // CASO 3: Borrar un Usuario (Método DELETE)
    // ===================================
    case 'DELETE':
        
        if ($id_usuario) {
            // Nota: Borrar un usuario puede fallar si tiene FK (claves foráneas)
            // Por ejemplo, si ha escrito artículos o consultas.
            // (En nuestra BD, las Consultas y Artículos bloquearán el borrado).
            try {
                $query = "DELETE FROM Usuarios WHERE id_usuario = :id_usuario";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':id_usuario', $id_usuario);

                if ($stmt->execute()) {
                    if ($stmt->rowCount() > 0) {
                        http_response_code(200); // OK
                        echo json_encode(array("message" => "Usuario eliminado."));
                    } else {
                        http_response_code(404); // Not Found
                        echo json_encode(array("message" => "Usuario no encontrado."));
                    }
                } else {
                    http_response_code(503);
                    echo json_encode(array("message" => "Error al ejecutar la consulta."));
                }
            } catch (PDOException $e) {
                // Capturar error de FK (Foreign Key)
                http_response_code(409); // Conflicto
                echo json_encode(array("message" => "Conflicto: No se puede eliminar el usuario. Es posible que tenga consultas, artículos o respuestas asociadas.", "error" => $e->getMessage()));
            }
        } else {
            http_response_code(400); // Bad Request
            echo json_encode(array("message" => "Falta el id_usuario en la URL."));
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