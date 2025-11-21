<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once '../vendor/autoload.php';
include_once '../config/Database.php';

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
$id_respuesta = $_GET['id_respuesta'] ?? null;
$id_consulta = $_GET['id_consulta'] ?? null;

switch ($method) {
  case 'GET':
    if ($id_consulta) {
      $query = "SELECT r.*, u.nombre_usuario AS nombre_usuario
                FROM respuestas r
                JOIN usuarios u ON r.id_usuario = u.id_usuario
                WHERE r.id_consulta = :id_consulta
                ORDER BY r.fecha_respuesta DESC";
      $stmt = $db->prepare($query);
      $stmt->bindParam(':id_consulta', $id_consulta);
      $stmt->execute();
      $respuestas = $stmt->fetchAll(PDO::FETCH_ASSOC);

      http_response_code(200);
      echo json_encode($respuestas);
    } else {
      http_response_code(400);
      echo json_encode(["status" => "error", "message" => "Falta el id_consulta en la URL."]);
    }
    break;

  case 'POST':
    // Check if it's multipart/form-data (file upload)
    if (isset($_FILES['attachments'])) {
      // Handle multipart form data
      $id_consulta = $_POST['id_consulta'] ?? '';
      $contenido = $_POST['contenido'] ?? '';
    } else {
      // Handle JSON data
      $id_consulta = $data->id_consulta ?? '';
      $contenido = $data->contenido ?? '';
    }

    if (!empty($id_consulta) && !empty($contenido) && trim($contenido) !== '') {
      // Handle file uploads
      $attachments_paths = [];
      if (isset($_FILES['attachments'])) {
        $upload_dir = '../uploads/respuestas/';
        if (!is_dir($upload_dir)) {
          mkdir($upload_dir, 0777, true);
        }
        foreach ($_FILES['attachments']['tmp_name'] as $key => $tmp_name) {
          $file_name = $_FILES['attachments']['name'][$key];
          $file_tmp = $_FILES['attachments']['tmp_name'][$key];
          $file_path = $upload_dir . uniqid() . '_' . $file_name;
          if (move_uploaded_file($file_tmp, $file_path)) {
            $attachments_paths[] = $file_path;
          }
        }
      }

      $attachments_json = json_encode($attachments_paths);
      $query = "INSERT INTO respuestas (id_consulta, id_usuario, contenido, fecha_respuesta, attachments)
                VALUES (:id_consulta, :id_usuario, :contenido, NOW(), :attachments)";
      $stmt = $db->prepare($query);
      $stmt->bindParam(':id_consulta', $id_consulta);
      $stmt->bindParam(':id_usuario', $usuarioToken->id);
      $stmt->bindParam(':contenido', $contenido);
      $stmt->bindParam(':attachments', $attachments_json);

      if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(["status" => "success", "message" => "Respuesta publicada."]);
      } else {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "No se pudo guardar la respuesta."]);
      }
    } else {
      http_response_code(400);
      echo json_encode(["status" => "error", "message" => "Datos incompletos."]);
    }
    break;

  case 'PUT':
    if ($id_respuesta && isset($data->contenido) && trim($data->contenido) !== '') {
      $query = "UPDATE respuestas SET contenido = :contenido WHERE id_respuesta = :id_respuesta";
      $stmt = $db->prepare($query);
      $stmt->bindParam(':contenido', $data->contenido);
      $stmt->bindParam(':id_respuesta', $id_respuesta);

      if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(["status" => "success", "message" => "Respuesta actualizada."]);
      } else {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "No se pudo actualizar la respuesta."]);
      }
    } else {
      http_response_code(400);
      echo json_encode(["status" => "error", "message" => "Falta el ID de la respuesta o datos incompletos."]);
    }
    break;

  case 'DELETE':
    if ($id_respuesta) {
      $query = "DELETE FROM respuestas WHERE id_respuesta = :id_respuesta";
      $stmt = $db->prepare($query);
      $stmt->bindParam(':id_respuesta', $id_respuesta);

      if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(["status" => "success", "message" => "Respuesta eliminada."]);
      } else {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "No se pudo eliminar la respuesta."]);
      }
    } else {
      http_response_code(400);
      echo json_encode(["status" => "error", "message" => "Falta el ID de la respuesta en la URL."]);
    }
    break;

  default:
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Método no permitido."]);
    break;
}
?>
