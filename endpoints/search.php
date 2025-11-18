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
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 3600");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit();
}

// =======================
// 🔑 Token (opcional para búsqueda pública)
// =======================
function obtenerToken($secretKey) {
  $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
  if (!$authHeader) {
    $headers = getallheaders();
    $authHeader = $headers['Authorization'] ?? '';
  }
  if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
    return null; // No token, búsqueda pública
  }
  $jwt = str_replace('Bearer ', '', $authHeader);
  try {
    $decoded = JWT::decode($jwt, new Key($secretKey, 'HS256'));
    return $decoded->data;
  } catch (Exception $e) {
    return null; // Token inválido, continuar como público
  }
}

$secretKey = "Samihaynesprohackersluxury@1996*";
$method = $_SERVER['REQUEST_METHOD'];
$usuarioToken = obtenerToken($secretKey);

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
      $mode = $_GET['mode'] ?? 'keyword'; // 'vin' or 'keyword'
      $query = $_GET['query'] ?? '';
      $categoria = $_GET['categoria'] ?? ''; // optional category filter

      $results = [];

      if ($mode === 'vin' && !empty($query)) {
        // Get vehicle data from VIN
        $vinData = getVinDataFromAPI($query);
        if (!$vinData) {
          http_response_code(400);
          echo json_encode(["message" => "VIN inválido"]);
          exit();
        }

        // Search consultas by vehicle data
        $query_consultas = "
          SELECT
            'consulta' as type,
            c.id_consulta as id,
            c.titulo as title,
            c.categoria as category,
            u.nombre_usuario as author,
            c.fecha_publicacion as date,
            c.descripcion as content
          FROM consultas c
          LEFT JOIN usuarios u ON c.id_usuario = u.id_usuario
          LEFT JOIN vehiculos v ON c.id_vehiculo = v.id_vehiculo
          WHERE v.marca = :marca AND v.modelo = :modelo AND v.ano = :ano AND v.motor = :motor
          AND c.estado != 'pendiente'
        ";
        $params_consultas = [
          ':marca' => $vinData['marca'],
          ':modelo' => $vinData['modelo'],
          ':ano' => $vinData['ano'],
          ':motor' => $vinData['motor']
        ];

        // Search articulos by vehicle data (assuming categoria_articulo might match)
        $query_articulos = "
          SELECT
            'articulo' as type,
            a.id_articulo as id,
            a.titulo_articulo as title,
            a.categoria_articulo as category,
            u.nombre_usuario as author,
            a.fecha_publicacion as date,
            a.contenido as content
          FROM articulos a
          LEFT JOIN usuarios u ON a.id_admin = u.id_usuario
          WHERE a.estado = 'publicada'
          AND (a.contenido LIKE :query OR a.titulo_articulo LIKE :query)
        ";
        $params_articulos = [':query' => '%' . implode('%', explode(' ', $vinData['marca'] . ' ' . $vinData['modelo'] . ' ' . $vinData['motor'])) . '%'];

        $stmt_consultas = $db->prepare($query_consultas);
        foreach ($params_consultas as $k => $v) {
          $stmt_consultas->bindValue($k, $v);
        }
        $stmt_consultas->execute();
        $consultas = $stmt_consultas->fetchAll(PDO::FETCH_ASSOC);

        $stmt_articulos = $db->prepare($query_articulos);
        foreach ($params_articulos as $k => $v) {
          $stmt_articulos->bindValue($k, $v);
        }
        $stmt_articulos->execute();
        $articulos = $stmt_articulos->fetchAll(PDO::FETCH_ASSOC);

        $results = array_merge($consultas, $articulos);

      } elseif ($mode === 'keyword' && !empty($query)) {
        // Keyword search in consultas and articulos
        $query_consultas = "
          SELECT
            'consulta' as type,
            c.id_consulta as id,
            c.titulo as title,
            c.categoria as category,
            u.nombre_usuario as author,
            c.fecha_publicacion as date,
            c.descripcion as content
          FROM consultas c
          LEFT JOIN usuarios u ON c.id_usuario = u.id_usuario
          WHERE (c.titulo LIKE :query OR c.descripcion LIKE :query)
          AND c.estado != 'pendiente'
        ";
        $query_articulos = "
          SELECT
            'articulo' as type,
            a.id_articulo as id,
            a.titulo_articulo as title,
            a.categoria_articulo as category,
            u.nombre_usuario as author,
            a.fecha_publicacion as date,
            a.contenido as content
          FROM articulos a
          LEFT JOIN usuarios u ON a.id_admin = u.id_usuario
          WHERE (a.titulo_articulo LIKE :query OR a.contenido LIKE :query)
          AND a.estado = 'publicada'
        ";
        $params = [':query' => '%' . $query . '%'];

        $stmt_consultas = $db->prepare($query_consultas);
        $stmt_consultas->bindValue(':query', '%' . $query . '%');
        $stmt_consultas->execute();
        $consultas = $stmt_consultas->fetchAll(PDO::FETCH_ASSOC);

        $stmt_articulos = $db->prepare($query_articulos);
        $stmt_articulos->bindValue(':query', '%' . $query . '%');
        $stmt_articulos->execute();
        $articulos = $stmt_articulos->fetchAll(PDO::FETCH_ASSOC);

        $results = array_merge($consultas, $articulos);
      }

      // Apply category filter if provided
      if (!empty($categoria)) {
        $results = array_filter($results, function($item) use ($categoria) {
          return $item['category'] === $categoria;
        });
      }

      // Sort by date descending
      usort($results, function($a, $b) {
        return strtotime($b['date']) - strtotime($a['date']);
      });

      http_response_code(200);
      echo json_encode($results);

    } catch (PDOException $e) {
      http_response_code(500);
      echo json_encode(["message" => "Error de base de datos: " . $e->getMessage()]);
    }
    break;

  default:
    http_response_code(405);
    echo json_encode(["message" => "Método no permitido"]);
    break;
}
?>
