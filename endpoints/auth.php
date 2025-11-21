<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

use Firebase\JWT\JWT;

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
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 3600");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit();
}

// ✅ الاتصال بقاعدة البيانات
$database = new Database();
$db = $database->getConnection();

// ✅ قراءة البيانات
$data = json_decode(file_get_contents("php://input"));

if (!empty($data->email) && !empty($data->password)) {
  $query = "SELECT id_usuario, nombre_usuario, email, password_hash, rol, nombre, especialidad
            FROM usuarios WHERE email = :email";
  $stmt = $db->prepare($query);
  $stmt->bindParam(':email', $data->email);
  $stmt->execute();

  if ($stmt->rowCount() === 1) {
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (password_verify($data->password, $usuario['password_hash'])) {
      // ✅ إنشاء JWT
      $secretKey = "Samihaynesprohackersluxury@1996*";
      $issuedAt = time();
      $expiration = $issuedAt + 3600;

      $payload = [
        "iat" => $issuedAt,
        "exp" => $expiration,
        "data" => [
          "id" => $usuario['id_usuario'],
          "email" => $usuario['email'],
          "rol" => $usuario['rol']
        ]
      ];

      $jwt = JWT::encode($payload, $secretKey, 'HS256');

      http_response_code(200);
      echo json_encode([
        "status" => "success",
        "token" => $jwt,
        "user" => [
          "id" => $usuario['id_usuario'],
          "nombre_usuario" => $usuario['nombre_usuario'],
          "email" => $usuario['email'],
          "rol" => $usuario['rol'],
          "nombre" => $usuario['nombre'],
          "especialidad" => $usuario['especialidad']
        ]
      ]);
    } else {
      http_response_code(401);
      echo json_encode(["status" => "error", "message" => "Contraseña incorrecta."]);
    }
  } else {
    http_response_code(404);
    echo json_encode(["status" => "error", "message" => "Usuario no encontrado."]);
  }
} else {
  http_response_code(400);
  echo json_encode(["status" => "error", "message" => "Datos incompletos (email o contraseña)."]);
}
?>
