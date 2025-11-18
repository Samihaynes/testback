<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once '../vendor/autoload.php';
include_once '../config/Database.php';
require_once '../middleware/AuthMiddleware.php'; // ✅ التحقق الموحد من JWT

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
header("Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true");
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

// ✅ الاتصال بقاعدة البيانات
$db = (new Database())->getConnection();
$method = $_SERVER['REQUEST_METHOD'];
$id_consulta = $_GET['id_consulta'] ?? null;

switch ($method) {
  case 'GET':
    if ($id_consulta) {
      $query = "SELECT
                  r.id_respuesta, r.descripcion_respuesta, r.es_solucion, r.fecha_respuesta,
                  u.nombre_usuario,
                  (SELECT COUNT(*) FROM votos v WHERE v.id_respuesta = r.id_respuesta AND tipo_voto = 'up') AS votos_up,
                  (SELECT COUNT(*) FROM votos v WHERE v.id_respuesta = r.id_respuesta AND tipo_voto = 'down') AS votos_down
                FROM respuestas r
                JOIN usuarios u ON r.id_usuario = u.id_usuario
                WHERE r.id_consulta = :id_consulta
                ORDER BY r.es_solucion DESC, votos_up DESC, r.fecha_respuesta ASC";

      $stmt = $db->prepare($query);
      $stmt->bindParam(':id_consulta', $id_consulta);
      $stmt->execute();
      $respuestas = $stmt->fetchAll(PDO::FETCH_ASSOC);

      foreach ($respuestas as &$respuesta) {
        $respuesta['votos_up'] = intval($respuesta['votos_up'] ?? 0);
        $respuesta['votos_down'] = intval($respuesta['votos_down'] ?? 0);
      }

      http_response_code(200);
      echo json_encode($respuestas);
    } else {
      http_response_code(400);
      echo json_encode(["message" => "Falta el id_consulta en la URL."]);
    }
    break;

  case 'POST':
    $data = json_decode(file_get_contents("php://input"));
    if (!empty($data->id_consulta) && !empty($data->descripcion_respuesta)) {
      $query = "INSERT INTO respuestas (id_consulta, id_usuario, descripcion_respuesta)
                VALUES (:id_consulta, :id_usuario, :descripcion)";
      $stmt = $db->prepare($query);
      $stmt->bindParam(':id_consulta', $data->id_consulta);
      $stmt->bindParam(':id_usuario', $usuarioToken->id);
      $stmt->bindParam(':descripcion', $data->descripcion_respuesta);

      if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(["message" => "Respuesta añadida exitosamente."]);
      } else {
        http_response_code(503);
        echo json_encode(["message" => "No se pudo añadir la respuesta."]);
      }
    } else {
      http_response_code(400);
      echo json_encode(["message" => "Datos incompletos."]);
    }
    break;

  case 'PUT':
    $id_respuesta = $_GET['id_respuesta'] ?? null;
    if ($id_respuesta) {
      $query = "UPDATE respuestas SET es_solucion = true WHERE id_respuesta = :id_respuesta";
      $stmt = $db->prepare($query);
      $stmt->bindParam(':id_respuesta', $id_respuesta);

      if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(["message" => "Respuesta marcada como solución."]);
      } else {
        http_response_code(503);
        echo json_encode(["message" => "No se pudo actualizar la respuesta."]);
      }
    } else {
      http_response_code(400);
      echo json_encode(["message" => "Falta el id_respuesta en la URL."]);
    }
    break;

  default:
    http_response_code(405);
    echo json_encode(["message" => "Método no permitido."]);
    break;
}
?>
