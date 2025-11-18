<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once '../vendor/autoload.php';
include_once '../config/Database.php';

// ... (إعدادات CORS والاتصال بالـ Token تبقى كما هي) ...
$allowedOrigins = [
  'http://localhost:3000',
  'http://localhost:3001',
  'http://localhost:3002',
  'http://localhost:3003',
  'http://192.168.1.237:3000'
];
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if (in_array($origin, $allowedOrigins)) {
  header("Access-Control-Allow-Origin: $origin");
}
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit();
}
function obtenerToken($secretKey) {
  $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
  if (!$authHeader) {
    $headers = getallheaders();
    $authHeader = $headers['Authorization'] ?? '';
  }
  if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Token no proporcionado"]);
    exit();
  }
  $jwt = str_replace('Bearer ', '', $authHeader);
  try {
    $decoded = JWT::decode($jwt, new Key($secretKey, 'HS256'));
    return $decoded->data;
  } catch (Exception $e) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Token inválido"]);
    exit();
  }
}
$secretKey = "Samihaynesprohackersluxury@1996*";
$usuarioToken = obtenerToken($secretKey);
$database = new Database();
$db = $database->getConnection();
$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"));
$id_usuario = $_GET['id_usuario'] ?? null;
$id_publicacion = $_GET['id_publicacion'] ?? null;
$id_consulta = $_GET['id_consulta'] ?? null;
// ... (نهاية الكود المشترك) ...


switch ($method) {
  case 'GET':
    // 🛑 [مصحح] تم تحديث أسماء الجداول
    if ($usuarioToken->rol !== 'admin') {
      http_response_code(403);
      echo json_encode(["status" => "error", "message" => "Acceso denegado. Solo administradores."]);
      exit();
    }

    // [مصحح] قراءة من 'usuarios'
    $usuariosQuery = "SELECT id_usuario, nombre_usuario, email, rol, fecha_registro, nombre, especialidad
                      FROM usuarios ORDER BY fecha_registro DESC";
    $stmtUsuarios = $db->prepare($usuariosQuery);
    $stmtUsuarios->execute();
    $usuarios = $stmtUsuarios->fetchAll(PDO::FETCH_ASSOC);

    // [مصحح] قراءة من 'articulos' - solo pendientes
    $publicacionesQuery = "SELECT id_articulo AS id, titulo_articulo AS titulo, categoria_articulo AS categoria, descripcion, estado, fecha_publicacion AS fecha_creacion
                           FROM articulos  ORDER BY fecha_publicacion DESC";
    $stmtPublicaciones = $db->prepare($publicacionesQuery);
    $stmtPublicaciones->execute();
    $publicaciones = $stmtPublicaciones->fetchAll(PDO::FETCH_ASSOC);

    // [مصحح] قراءة من 'consultas' - solo pendientes
    $consultasQuery = "SELECT id_consulta AS id, titulo, categoria, estado, fecha_publicacion AS fecha_creacion, descripcion
                       FROM consultas ORDER BY fecha_publicacion DESC";
    $stmtConsultas = $db->prepare($consultasQuery);
    $stmtConsultas->execute();
    $consultas = $stmtConsultas->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
      "usuarios" => $usuarios,
      "publicaciones" => $publicaciones,
      "consultas" => $consultas
    ]);
    break;

  case 'POST':
    if ($usuarioToken->rol !== 'admin') {
      http_response_code(403);
      echo json_encode(["status" => "error", "message" => "Acceso denegado. Solo administradores."]);
      exit();
    }

    // Crear nuevo artículo
    if (
      isset($data->titulo_articulo) && trim($data->titulo_articulo) !== '' &&
      isset($data->categoria_articulo) && trim($data->categoria_articulo) !== '' &&
      isset($data->descripcion) && trim($data->descripcion) !== '' &&
      isset($data->contenido) && trim($data->contenido) !== ''
    ) {
      $query = "INSERT INTO articulos (id_admin, titulo_articulo, categoria_articulo, descripcion, contenido, estado, fecha_publicacion)
                VALUES (:id_admin, :titulo, :categoria, :descripcion, :contenido, 'pendiente', NOW())";
      $stmt = $db->prepare($query);
      $stmt->bindParam(':id_admin', $usuarioToken->id);
      $stmt->bindParam(':titulo', $data->titulo_articulo);
      $stmt->bindParam(':categoria', $data->categoria_articulo);
      $stmt->bindParam(':descripcion', $data->descripcion);
      $stmt->bindParam(':contenido', $data->contenido);

      if ($stmt->execute()) {
        $id_nuevo = $db->lastInsertId();
        http_response_code(201);
        echo json_encode([
          "status" => "success",
          "message" => "Artículo creado exitosamente.",
          "id_articulo" => $id_nuevo
        ]);
      } else {
        http_response_code(503);
        echo json_encode(["status" => "error", "message" => "No se pudo crear el artículo."]);
      }
    } else {
      http_response_code(400);
      echo json_encode(["status" => "error", "message" => "Datos incompletos. Se requieren: titulo_articulo, categoria_articulo, descripcion, contenido."]);
    }
    break;

  // 🛑 [مصحح بالكامل] تم إعادة بناء قسم PUT
  case 'PUT':
    if ($usuarioToken->rol !== 'admin') {
      http_response_code(403);
      echo json_encode(["status" => "error", "message" => "Acceso denegado."]);
      exit();
    }

    // --- الحالة 1: تعديل مستخدم ---
    if ($id_usuario) {
      $campos = [];
      $params = [];

      // التحقق من كل حقل يريد المسؤول تعديله
      if (isset($data->rol) && in_array($data->rol, ['admin', 'mecanico', 'usuario', 'taller'])) {
        $campos[] = "rol = :rol";
        $params[':rol'] = $data->rol;
      }
      if (isset($data->nombre_usuario)) {
        $campos[] = "nombre_usuario = :nombre_usuario";
        $params[':nombre_usuario'] = $data->nombre_usuario;
      }
      if (isset($data->email)) {
        $campos[] = "email = :email";
        $params[':email'] = $data->email;
      }
      if (isset($data->nombre)) { // اسم الورشة
        $campos[] = "nombre = :nombre";
        $params[':nombre'] = $data->nombre;
      }
      if (isset($data->especialidad)) {
        $campos[] = "especialidad = :especialidad";
        $params[':especialidad'] = $data->especialidad;
      }

      if (count($campos) > 0) {
        $query = "UPDATE usuarios SET " . implode(", ", $campos) . " WHERE id_usuario = :id_usuario";
        $stmt = $db->prepare($query);
        $params[':id_usuario'] = $id_usuario; // إضافة ID المستخدم إلى البارامترات
        
        $stmt->execute($params);
        echo json_encode(["status" => "success", "message" => "Usuario actualizado."]);
      } else {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "No se proporcionaron datos válidos para actualizar."]);
      }
    
    // --- الحالة 2: تعديل منشور (مقال) ---
    } elseif ($id_publicacion) {
      $campos = [];
      $params = [];

      if (isset($data->titulo)) {
        $campos[] = "titulo_articulo = :titulo";
        $params[':titulo'] = $data->titulo;
      }
      if (isset($data->categoria)) {
        $campos[] = "categoria_articulo = :categoria";
        $params[':categoria'] = $data->categoria;
      }
      if (isset($data->descripcion)) {
        $campos[] = "descripcion = :descripcion";
        $params[':descripcion'] = $data->descripcion;
      }
      if (isset($data->estado) && in_array($data->estado, ['pendiente', 'publicada', 'rechazada'])) {
        $campos[] = "estado = :estado";
        $params[':estado'] = $data->estado;
      }

      if (count($campos) > 0) {
        $query = "UPDATE articulos SET " . implode(", ", $campos) . " WHERE id_articulo = :id_publicacion";
        $stmt = $db->prepare($query);
        $params[':id_publicacion'] = $id_publicacion; // إضافة ID المقال

        $stmt->execute($params);
        echo json_encode(["status" => "success", "message" => "Publicación actualizada."]);
      } else {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "No se proporcionaron datos válidos para actualizar."]);
      }

    // --- الحالة 3: تعديل consulta ---
    } elseif ($id_consulta) {
      $campos = [];
      $params = [];

      if (isset($data->estado) && in_array($data->estado, ['pendiente', 'abierta', 'cerrada', 'resuelta'])) {
        $campos[] = "estado = :estado";
        $params[':estado'] = $data->estado;
      }
      if (isset($data->titulo)) {
        $campos[] = "titulo = :titulo";
        $params[':titulo'] = $data->titulo;
      }
      if (isset($data->categoria)) {
        $campos[] = "categoria = :categoria";
        $params[':categoria'] = $data->categoria;
      }
      if (isset($data->descripcion)) {
        $campos[] = "descripcion = :descripcion";
        $params[':descripcion'] = $data->descripcion;
      }

      if (count($campos) > 0) {
        $query = "UPDATE consultas SET " . implode(", ", $campos) . " WHERE id_consulta = :id_consulta";
        $stmt = $db->prepare($query);
        $params[':id_consulta'] = $id_consulta;

        $stmt->execute($params);
        echo json_encode(["status" => "success", "message" => "Consulta actualizada."]);
      } else {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "No se proporcionaron datos válidos para actualizar."]);
      }

    } else {
      http_response_code(400);
      echo json_encode(["status" => "error", "message" => "Falta id_usuario, id_publicacion o id_consulta en la URL."]);
    }
    break;

  case 'DELETE':
    // 🛑 [مصحح] تم تحديث أسماء الجداول
    if ($usuarioToken->rol !== 'admin') {
      http_response_code(403);
      echo json_encode(["status" => "error", "message" => "Acceso denegado. Solo administradores."]);
      exit();
    }

    if ($id_usuario) {
      try {
        $query = "DELETE FROM usuarios WHERE id_usuario = :id_usuario";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->execute();
        echo json_encode(["status" => "success", "message" => "Usuario eliminado."]);
      } catch (PDOException $e) {
        http_response_code(409);
        echo json_encode(["status" => "error", "message" => "No se puede eliminar el usuario.", "error" => $e->getMessage()]);
      }
    } elseif ($id_publicacion) {
      try {
        $query = "DELETE FROM articulos WHERE id_articulo = :id_publicacion";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id_publicacion', $id_publicacion);
        $stmt->execute();
        echo json_encode(["status" => "success", "message" => "Publicación eliminada."]);
      } catch (PDOException $e) {
        http_response_code(409);
        echo json_encode(["status" => "error", "message" => "No se puede eliminar la publicación.", "error" => $e->getMessage()]);
      }
    } elseif ($id_consulta) {
      try {
        $query = "DELETE FROM consultas WHERE id_consulta = :id_consulta";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id_consulta', $id_consulta);
        $stmt->execute();
        echo json_encode(["status" => "success", "message" => "Consulta eliminada."]);
      } catch (PDOException $e) {
        http_response_code(409);
        echo json_encode(["status" => "error", "message" => "No se puede eliminar la consulta.", "error" => $e->getMessage()]);
      }
    } else {
      http_response_code(400);
      echo json_encode(["status" => "error", "message" => "Falta id_usuario, id_publicacion o id_consulta en la URL."]);
    }
    break;

  default:
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Método no permitido."]);
    break;
}
?>