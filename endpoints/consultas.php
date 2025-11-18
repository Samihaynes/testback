<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once '../vendor/autoload.php';
include_once '../config/Database.php';
require_once '../middleware/AuthMiddleware.php';

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

// =======================
// 🔑 Token (solo si necesario)
// =======================
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
$method = $_SERVER['REQUEST_METHOD'];
$id_consulta = $_GET['id_consulta'] ?? null;

// Para POST pedimos token, GET es público
$usuarioToken = null;
if ($method === 'POST') {
  $usuarioToken = obtenerToken($secretKey);
}

$db = (new Database())->getConnection();

// =======================
// 🔎 Función API VIN
// =======================
function getVinDataFromAPI($vin) {
    $apiKey = "ARd4P5MMVbB6Ny5hcSXiXMjfhSCfNsM9vyO88g8XaRqhdARMae";
    $appId = "u415i143neKCsA";
    $apiUrl = "https://zpk.systems/api/vin-analyzer/analyze";

    $postData = json_encode([
        'api_key' => $apiKey,
        'application_id' => $appId,
        'vins' => [$vin]
    ]);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);
    if (isset($data['success']) && $data['success'] === true && isset($data['results'][0]['vin'])) {
        $attrs = $data['results'][0]['vin'];
        return [
            "marca" => $attrs['manufacturer_name'] ?? 'Desconocido',
            "modelo" => $attrs['model'] ?? 'Desconocido',
            "ano" => $attrs['year'] ?? 0,
            "motor" => $attrs['engine_model'] ?? 'Desconocido'
        ];
    }
    return null;
}

// =======================
// 📌 Lógica principal
// =======================
switch ($method) {
  case 'GET':
  try {
    // 1) Base query always initialized
    $query = "
      SELECT
        c.id_consulta,
        c.titulo,
        c.estado,
        c.fecha_publicacion,
        u.nombre_usuario,
        v.marca, v.modelo, v.motor
      FROM consultas c
      LEFT JOIN usuarios u ON c.id_usuario = u.id_usuario
      LEFT JOIN vehiculos v ON c.id_vehiculo = v.id_vehiculo
    ";

    // 2) Default: hide 'pendiente' for everyone without admin role (including anonymous)
    $conditions = [];
    $params = [];

    // If user is not admin (or no token), hide pending
    if (!isset($usuarioToken) || !isset($usuarioToken->rol) || $usuarioToken->rol !== 'admin') {
      $conditions[] = "c.estado != :pendiente";
      $params[':pendiente'] = 'pendiente';
    }

    // 3) Apply conditions
    if (!empty($conditions)) {
      $query .= " WHERE " . implode(" AND ", $conditions);
    }

    // 4) Order by newest first
    $query .= " ORDER BY c.fecha_publicacion DESC";

    // 5) Prepare/execute
    $stmt = $db->prepare($query);
    foreach ($params as $k => $v) {
      $stmt->bindValue($k, $v);
    }
    $stmt->execute();

    $consultas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    echo json_encode($consultas);

  } catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["message" => "Erreur de base de données: " . $e->getMessage()]);
  }
  break;

  case 'POST':
    $data = json_decode(file_get_contents("php://input"));
    if (isset($data->checkVin) && !empty($data->vin)) {
      // solo verificar VIN
      $vinData = getVinDataFromAPI($data->vin);
      if ($vinData) {
        http_response_code(200);
        echo json_encode($vinData);
      } else {
        http_response_code(400);
        echo json_encode(["message" => "VIN inválido"]);
      }
      exit();
    }

    if (!empty($data->titulo) && !empty($data->vin)) {
      $vin = $data->vin;
      $vehiculoData = getVinDataFromAPI($vin);

      if ($vehiculoData) {
        // Verificar si el VIN ya existe
        $query_check_vin = "SELECT id_vehiculo FROM vehiculos WHERE vin = :vin";
        $stmt_check_vin = $db->prepare($query_check_vin);
        $stmt_check_vin->bindParam(':vin', $vin);
        $stmt_check_vin->execute();
        $existing_vehicle = $stmt_check_vin->fetch(PDO::FETCH_ASSOC);

        if ($existing_vehicle) {
          $id_vehiculo = $existing_vehicle['id_vehiculo'];
        } else {
          // insertar vehículo si no existe
          $query_new_vin = "INSERT INTO vehiculos (vin, marca, modelo, ano, motor)
                            VALUES (:vin, :marca, :modelo, :ano, :motor)";
          $stmt_new_vin = $db->prepare($query_new_vin);
          $stmt_new_vin->bindParam(':vin', $vin);
          $stmt_new_vin->bindParam(':marca', $vehiculoData['marca']);
          $stmt_new_vin->bindParam(':modelo', $vehiculoData['modelo']);
          $stmt_new_vin->bindParam(':ano', $vehiculoData['ano']);
          $stmt_new_vin->bindParam(':motor', $vehiculoData['motor']);
          $stmt_new_vin->execute();
          $id_vehiculo = $db->lastInsertId();
        }

        // insertar consulta
        $descripcion = $data->descripcion ?? '';
        $categoria = $data->categoria ?? '';
        $query_consulta = "INSERT INTO consultas (id_usuario, id_vehiculo, titulo, descripcion, categoria, estado)
                           VALUES (:id_usuario, :id_vehiculo, :titulo, :descripcion, :categoria, 'pendiente')";
        $stmt_consulta = $db->prepare($query_consulta);
        $stmt_consulta->bindParam(':id_usuario', $usuarioToken->id);
        $stmt_consulta->bindParam(':id_vehiculo', $id_vehiculo);
        $stmt_consulta->bindParam(':titulo', $data->titulo);
        $stmt_consulta->bindParam(':descripcion', $descripcion);
        $stmt_consulta->bindParam(':categoria', $categoria);

        if ($stmt_consulta->execute()) {
          http_response_code(201);
          echo json_encode(["message" => "Consulta creada exitosamente."]);
        } else {
          http_response_code(503);
          echo json_encode(["message" => "No se pudo crear la consulta."]);
        }
      } else {
        http_response_code(400);
        echo json_encode(["message" => "Error al obtener datos del VIN"]);
      }
    } else {
      http_response_code(400);
      echo json_encode(["message" => "Datos incompletos"]);
    }
    break;

  default:
    http_response_code(405);
    echo json_encode(["message" => "Método no permitido"]);
    break;
}
?>
