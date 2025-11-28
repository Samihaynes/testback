<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once '../vendor/autoload.php';
include_once '../config/Database.php';

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
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 3600");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit();
}

// 🔐 التحقق من JWT
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

// اتصال بقاعدة البيانات
$db = (new Database())->getConnection();

// ✅ جلب النوتيفيكاسيونات للمستخدم
$query = "SELECT * FROM notificaciones WHERE id_usuario = :id_usuario ORDER BY fecha DESC";
$stmt = $db->prepare($query);
$stmt->bindParam(':id_usuario', $usuarioToken->id);
$stmt->execute();

$notificaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

// --- DEBUGGING CRÍTICO ---
if (empty($notificaciones)) {
    // Si la BD devuelve vacío, al menos que veamos que el JSON está correcto
    $response_data = ["status" => "success", "data" => []];
} else {
    $response_data = ["status" => "success", "data" => $notificaciones];
}

http_response_code(200);
// **AÑADIR ESTO:** Esto forzará una salida si el código llega aquí.
// echo "DEBUG: Llegó al final."; // <- Quitar esta línea después del test
echo json_encode($response_data);
exit();
?>
