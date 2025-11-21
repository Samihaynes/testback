<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once '../vendor/autoload.php';
include_once '../config/Database.php';
require_once '../middleware/AuthMiddleware.php'; // ✅ التحقق الموحد من JWT

// ✅ إعدادات CORS
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
$allowedOrigins = ['http://localhost:3000', 'http://localhost:3001', 'http://127.0.0.1:3000', 'http://127.0.0.1:3001'];

if (in_array($origin, $allowedOrigins)) {
    header("Access-Control-Allow-Origin: $origin");
} else {
    // For development, allow all localhost origins
    if (strpos($origin, 'localhost') !== false || strpos($origin, '127.0.0.1') !== false) {
        header("Access-Control-Allow-Origin: $origin");
    }
}

header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit();
}

// ✅ التحقق من التوكن
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
$usuarioToken = obtenerToken($secretKey); // توحيد الاسم

// ✅ الاتصال بقاعدة البيانات
$db = (new Database())->getConnection();
$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"));
$id_articulo = $_GET['id_articulo'] ?? null;

switch ($method) {
  case 'GET':
    if ($id_articulo) {
      $query = "SELECT c.id_comentario, c.contenido, c.fecha_comentario, u.nombre_usuario
                FROM comentarios_articulo c
                JOIN usuarios u ON c.id_usuario = u.id_usuario
                WHERE c.id_articulo = :id_articulo
                ORDER BY c.fecha_comentario DESC";
      $stmt = $db->prepare($query);
      $stmt->bindParam(':id_articulo', $id_articulo);
      $stmt->execute();
      $comentarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

      http_response_code(200);
      echo json_encode([
        "status" => "success",
        "data" => $comentarios
      ]);
    } else {
      http_response_code(400);
      echo json_encode(["status" => "error", "message" => "Falta el id_articulo en la URL."]);
    }
    break;

  case 'POST':
    if (isset($data->id_articulo) && isset($data->contenido) && trim($data->contenido) !== '') {
      $query = "INSERT INTO comentarios_articulo (id_articulo, id_usuario, contenido, fecha_comentario)
                VALUES (:id_articulo, :id_usuario, :contenido, NOW())";
      $stmt = $db->prepare($query);
      $stmt->bindParam(':id_articulo', $data->id_articulo);
      $stmt->bindParam(':id_usuario', $usuarioToken->id);
      $stmt->bindParam(':contenido', $data->contenido);

      if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(["status" => "success", "message" => "✅ Comentario publicado."]);
      } else {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "❌ No se pudo guardar el comentario."]);
      }
    } else {
      http_response_code(400);
      echo json_encode(["status" => "error", "message" => "Datos incompletos."]);
    }
    break;

  default:
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Método no permitido."]);
    break;
}
?>
