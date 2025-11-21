<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once '../vendor/autoload.php';
include_once '../config/Database.php';
require_once '../middleware/AuthMiddleware.php'; // ✅ استدعاء التحقق الموحد
require_once '../utils/NotificationUtils.php';

// =======================
// 🔧 Configuración CORS
// =======================
$allowedOrigins = [
  'http://localhost:3000',
  'http://localhost:3001',
  'http://localhost:3002',
  'http://localhost:3003',
  'http://127.0.0.1:3000',
  'http://127.0.0.1:3001',
  'http://127.0.0.1:3002',
  'http://192.168.1.237:3000',
  'http://192.168.1.237:3001',
  'http://192.168.1.237:3002'
];
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if (in_array($origin, $allowedOrigins)) {
  header("Access-Control-Allow-Origin: $origin");
} else {
  // Para desarrollo, permitir cualquier origen localhost
  if (strpos($origin, 'localhost') !== false || strpos($origin, '127.0.0.1') !== false) {
    header("Access-Control-Allow-Origin: $origin");
  }
}
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 3600");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit();
}
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
$usuarioToken = obtenerToken($secretKey);

// ✅ التحقق من نوع الطلب
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(["message" => "Método no permitido."]);
  exit();
}

// ✅ الاتصال بقاعدة البيانات
$db = (new Database())->getConnection();
$data = json_decode(file_get_contents("php://input"));

// ✅ التحقق من البيانات
if (
  !empty($data->id_respuesta) &&
  !empty($data->tipo_voto) &&
  in_array($data->tipo_voto, ['up', 'down'])
) {
  try {
    $fecha_voto = date('Y-m-d H:i:s');
    $id_usuario = $usuarioToken->id;

    // ✅ إدراج أو تحديث التصويت
    $query = "INSERT INTO votos (id_respuesta, id_usuario, tipo_voto, fecha_voto)
              VALUES (:id_respuesta, :id_usuario, :tipo_voto, :fecha_voto)
              ON DUPLICATE KEY UPDATE
                tipo_voto = :tipo_voto_update,
                fecha_voto = :fecha_voto_update";

    $stmt = $db->prepare($query);
    $stmt->bindParam(':id_respuesta', $data->id_respuesta);
    $stmt->bindParam(':id_usuario', $id_usuario);
    $stmt->bindParam(':tipo_voto', $data->tipo_voto);
    $stmt->bindParam(':fecha_voto', $fecha_voto);
    $stmt->bindParam(':tipo_voto_update', $data->tipo_voto);
    $stmt->bindParam(':fecha_voto_update', $fecha_voto);
    $stmt->execute();

    // ✅ حساب عدد التصويتات
    $countQuery = "SELECT
                      SUM(tipo_voto = 'up') AS likes,
                      SUM(tipo_voto = 'down') AS dislikes
                   FROM votos
                   WHERE id_respuesta = :id_respuesta";

    $countStmt = $db->prepare($countQuery);
    $countStmt->bindParam(':id_respuesta', $data->id_respuesta);
    $countStmt->execute();
    $result = $countStmt->fetch(PDO::FETCH_ASSOC);

    http_response_code(201);
    echo json_encode([
      "status" => "success",
      "message" => "Voto registrado/actualizado exitosamente.",
      "likes" => intval($result['likes']),
      "dislikes" => intval($result['dislikes'])
    ]);
  } catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
      "status" => "error",
      "message" => "Error interno: " . $e->getMessage()
    ]);
  }
} else {
  http_response_code(400);
  echo json_encode([
    "status" => "error",
    "message" => "Datos incompletos o tipo_voto inválido."
  ]);
}
?>
