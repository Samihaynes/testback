<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Cargar dependencias de Composer (incluyendo Firebase/PHP-JWT)
require_once '../vendor/autoload.php';
include_once '../config/Database.php';
// Nota: AuthMiddleware y NotificationUtils deben estar configurados en el sistema
require_once '../middleware/AuthMiddleware.php'; 
require_once '../utils/NotificationUtils.php';

// =======================
// 🔧 Configuración CORS UNIFICADA
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
  if (strpos($origin, 'localhost') !== false || strpos( $origin, '127.0.0.1') !== false) {
    header("Access-Control-Allow-Origin: $origin");
  }
}

// Configuración de métodos permitidos
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS"); // Solo permitimos POST para votar
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 3600");

// Manejar la petición OPTIONS (preflight request)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit();
}

// =======================
// ✅ Función de Verificación JWT (Autenticación)
// =======================
function obtenerToken($secretKey) {
  $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
  if (!$authHeader) {
    $headers = getallheaders();
    $authHeader = $headers['Authorization'] ?? '';
  }
  // Verificar formato 'Bearer <token>'
  if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Token no proporcionado"]);
    exit();
  }
  $jwt = str_replace('Bearer ', '', $authHeader);
  
  // Decodificar y validar el token
  try {
    $decoded = JWT::decode($jwt, new Key($secretKey, 'HS256'));
    return $decoded->data; // Devolver los datos del usuario (id, rol, email)
  } catch (Exception $e) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Token inválido"]);
    exit();
  }
}

$secretKey = "Samihaynesprohackersluxury@1996*";
// Llamar a la función para obtener y validar el token del usuario logueado
$usuarioToken = obtenerToken($secretKey); 

// =======================
// ✅ Lógica de Votación (POST)
// =======================

// Verificar que el método sea POST (único permitido para esta ruta)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(["message" => "Método no permitido."]);
  exit();
}

// Conexión a la base de datos
$db = (new Database())->getConnection();
// Leer datos del cuerpo de la petición (JSON)
$data = json_decode(file_get_contents("php://input"));

// Validar que los datos necesarios existan
if (
  !empty($data->id_respuesta) &&
  !empty($data->tipo_voto) &&
  in_array($data->tipo_voto, ['up', 'down'])
) {
  try {
    $fecha_voto = date('Y-m-d H:i:s');
    $id_usuario = $usuarioToken->id;

    // Insertar o actualizar el voto. Si el usuario ya votó (clave duplicada), se actualiza el tipo_voto.
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
    
    // Aquí se debería llamar a NotificationUtils::crearNotificacion si el voto es UP
    
    // Consultar el recuento actual de votos para devolver la respuesta al cliente
    $countQuery = "SELECT
                      SUM(tipo_voto = 'up') AS likes,
                      SUM(tipo_voto = 'down') AS dislikes
                   FROM votos
                   WHERE id_respuesta = :id_respuesta";

    $countStmt = $db->prepare($countQuery);
    $countStmt->bindParam(':id_respuesta', $data->id_respuesta);
    $countStmt->execute();
    $result = $countStmt->fetch(PDO::FETCH_ASSOC);

    http_response_code(201); // Created
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
  http_response_code(400); // Bad Request
  echo json_encode([
    "status" => "error",
    "message" => "Datos incompletos o tipo_voto inválido."
  ]);
}
?>