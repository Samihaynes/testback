<?php
// Archivo: endpoints/articulos.php

// 1. Headers del API (ESENCIALES)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); // Métodos CRUD completos
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

// 5. Leer los datos JSON (para POST y PUT)
$data = json_decode(file_get_contents("php://input"));

// 6. Leer los IDs de la URL (para GET, PUT y DELETE)
$id_articulo = isset($_GET['id_articulo']) ? $_GET['id_articulo'] : null;

// TODO: Aquí debería ir la lógica para verificar el Token JWT
// y asegurarse de que el rol es 'admin' para POST, PUT y DELETE.
// $id_admin_token = 1; // ID de admin (ejemplo hardcodeado)


switch ($method) {
    // ===================================
    // CASO 1: Listar Artículos (Método GET)
    // ===================================
    case 'GET':
        // Si nos dan un ID, devolvemos solo ese artículo
        if ($id_articulo) {
            $query = "SELECT a.*, u.nombre_usuario AS nombre_admin 
                      FROM Articulos a 
                      JOIN Usuarios u ON a.id_admin = u.id_usuario
                      WHERE a.id_articulo = :id_articulo";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':id_articulo', $id_articulo);
        } else {
            // Si no, los listamos todos
            $query = "SELECT a.*, u.nombre_usuario AS nombre_admin 
                      FROM Articulos a 
                      JOIN Usuarios u ON a.id_admin = u.id_usuario
                      ORDER BY a.fecha_creacion DESC";
            $stmt = $db->prepare($query);
        }
        
        $stmt->execute();
        $articulos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($articulos) {
            http_response_code(200); // OK
            echo json_encode($articulos);
        } else {
            http_response_code(404); // Not Found
            echo json_encode(array("message" => "No se encontraron artículos."));
        }
        break;

    // ===================================
    // CASO 2: Crear Artículo (Método POST)
    // ===================================
    case 'POST':
        // TODO: Verificar que el $id_admin_token tiene rol 'admin'

        if (
            !empty($data->id_admin) && // (Temporalmente, React envía el ID del admin)
            !empty($data->titulo_articulo) &&
            !empty($data->contenido)
        ) {
            $query = "INSERT INTO Articulos (id_admin, titulo_articulo, contenido, categoria_articulo)
                      VALUES (:id_admin, :titulo, :contenido, :categoria)";
            
            $stmt = $db->prepare($query);

            $stmt->bindParam(':id_admin', $data->id_admin); // Debería ser $id_admin_token
            $stmt->bindParam(':titulo', $data->titulo_articulo);
            $stmt->bindParam(':contenido', $data->contenido);
            $stmt->bindParam(':categoria', $data->categoria_articulo);

            if ($stmt->execute()) {
                http_response_code(201); // Creado
                echo json_encode(array("message" => "Artículo creado exitosamente."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "No se pudo crear el artículo."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Datos incompletos."));
        }
        break;

    // ===================================
    // CASO 3: Actualizar Artículo (Método PUT)
    // ===================================
    case 'PUT':
        // TODO: Verificar que el $id_admin_token tiene rol 'admin'
        
        // Necesitamos el ID del artículo (de la URL) y los datos (del JSON)
        if ($id_articulo && !empty($data->titulo_articulo) && !empty($data->contenido)) {
            
            $query = "UPDATE Articulos 
                      SET titulo_articulo = :titulo, 
                          contenido = :contenido, 
                          categoria_articulo = :categoria
                      WHERE id_articulo = :id_articulo";

            $stmt = $db->prepare($query);

            $stmt->bindParam(':titulo', $data->titulo_articulo);
            $stmt->bindParam(':contenido', $data->contenido);
            $stmt->bindParam(':categoria', $data->categoria_articulo);
            $stmt->bindParam(':id_articulo', $id_articulo);

            if ($stmt->execute()) {
                http_response_code(200); // OK
                echo json_encode(array("message" => "Artículo actualizado."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "No se pudo actualizar el artículo."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Falta el ID del artículo o datos incompletos."));
        }
        break;

    // ===================================
    // CASO 4: Borrar Artículo (Método DELETE)
    // ===================================
    case 'DELETE':
        // TODO: Verificar que el $id_admin_token tiene rol 'admin'
        
        if ($id_articulo) {
            $query = "DELETE FROM Articulos WHERE id_articulo = :id_articulo";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':id_articulo', $id_articulo);

            if ($stmt->execute()) {
                http_response_code(200); // OK
                echo json_encode(array("message" => "Artículo eliminado."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "No se pudo eliminar el artículo."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Falta el ID del artículo en la URL."));
        }
        break;

    // ===================================
    // CASO 5: Otro método
    // ===================================
    default:
        http_response_code(405); // Método No Permitido
        echo json_encode(array("message" => "Método no permitido."));
        break;
}
?>