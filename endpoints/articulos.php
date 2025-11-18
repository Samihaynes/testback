<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once '../vendor/autoload.php';
include_once '../config/Database.php';

// ✅ إعدادات CORS
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
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Max-Age: 3600");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit();
}

// ✅ التحقق من التوكن JWT بطريقة موحدة
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

// ✅ الاتصال بقاعدة البيانات
$db = (new Database())->getConnection();

// ✅ تحديد نوع الطلب
$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"));
$id_articulo = $_GET['id_articulo'] ?? null;

switch ($method) {
  case 'GET':
    if ($id_articulo) {
      $query = "SELECT a.*, u.nombre_usuario AS nombre_admin
                FROM articulos a
                JOIN usuarios u ON a.id_admin = u.id_usuario
                WHERE a.id_articulo = :id_articulo";
      $stmt = $db->prepare($query);
      $stmt->bindParam(':id_articulo', $id_articulo);
    } else {
      $query = "SELECT a.*, u.nombre_usuario AS nombre_admin
                FROM articulos a
                JOIN usuarios u ON a.id_admin = u.id_usuario
                ORDER BY a.fecha_publicacion DESC";
      $stmt = $db->prepare($query);
    }

    $stmt->execute();
    $articulos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($articulos) {
      http_response_code(200);
      echo json_encode($articulos);
    } else {
      http_response_code(404);
      echo json_encode(["status" => "error", "message" => "No se encontraron artículos."]);
    }
    break;

  case 'POST':
    if (
      isset($data->titulo_articulo) && trim($data->titulo_articulo) !== '' &&
      isset($data->contenido) && trim($data->contenido) !== '' &&
      isset($data->categoria_articulo) && trim($data->categoria_articulo) !== ''
    ) {
      $query = "INSERT INTO articulos (id_admin, titulo_articulo, contenido, categoria_articulo, fecha_publicacion)
                VALUES (:id_admin, :titulo, :contenido, :categoria, NOW())";
      $stmt = $db->prepare($query);
      $stmt->bindParam(':id_admin', $usuarioToken->id);
      $stmt->bindParam(':titulo', $data->titulo_articulo);
      $stmt->bindParam(':contenido', $data->contenido);
      $stmt->bindParam(':categoria', $data->categoria_articulo);

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
      echo json_encode(["status" => "error", "message" => "Datos incompletos."]);
    }
    break;

  case 'PUT':
    if (
      $id_articulo &&
      isset($data->titulo_articulo) && trim($data->titulo_articulo) !== '' &&
      isset($data->contenido) && trim($data->contenido) !== '' &&
      isset($data->categoria_articulo)
    ) {
      $query = "UPDATE articulos
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
        http_response_code(200);
        echo json_encode(["status" => "success", "message" => "Artículo actualizado."]);
      } else {
        http_response_code(503);
        echo json_encode(["status" => "error", "message" => "No se pudo actualizar el artículo."]);
      }
    } else {
      http_response_code(400);
      echo json_encode(["status" => "error", "message" => "Falta el ID del artículo o datos incompletos."]);
    }
    break;

  case 'DELETE':
    if ($id_articulo) {
      $query = "DELETE FROM articulos WHERE id_articulo = :id_articulo";
      $stmt = $db->prepare($query);
      $stmt->bindParam(':id_articulo', $id_articulo);

      if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(["status" => "success", "message" => "Artículo eliminado."]);
      } else {
        http_response_code(503);
        echo json_encode(["status" => "error", "message" => "No se pudo eliminar el artículo."]);
      }
    } else {
      http_response_code(400);
      echo json_encode(["status" => "error", "message" => "Falta el ID del artículo en la URL."]);
    }
    break;

  default:
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Método no permitido."]);
    break;
}
?>
