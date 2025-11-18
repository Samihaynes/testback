<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once '../vendor/autoload.php';
include_once '../config/Database.php';
require_once '../middleware/AuthMiddleware.php';

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
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
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

// ✅ قراءة البيانات النصية
$titulo = $_POST['titulo_articulo'] ?? '';
$categoria = $_POST['categoria_articulo'] ?? '';
$contenido = $_POST['contenido'] ?? '';
$id_admin = $usuarioToken->id ?? null;

// ✅ التحقق من البيانات الأساسية
if (!$titulo || !$categoria || !$contenido || !$id_admin) {
  http_response_code(400);
  echo json_encode(["status" => "error", "message" => "Datos incompletos."]);
  exit();
}

// ✅ إعداد مجلد الرفع
$uploadDir = "../uploads/";
if (!is_dir($uploadDir)) {
  mkdir($uploadDir, 0777, true);
}

// ✅ رفع الملفات (إذا كانت موجودة)
$imagenName = null;
$pdfName = null;
$videoName = null;

if (!empty($_FILES['imagen']['name'])) {
  $imagenName = time() . "_img_" . basename($_FILES['imagen']['name']);
  move_uploaded_file($_FILES['imagen']['tmp_name'], $uploadDir . $imagenName);
}

if (!empty($_FILES['pdf']['name'])) {
  $pdfName = time() . "_pdf_" . basename($_FILES['pdf']['name']);
  move_uploaded_file($_FILES['pdf']['tmp_name'], $uploadDir . $pdfName);
}

if (!empty($_FILES['video']['name'])) {
  $videoName = time() . "_vid_" . basename($_FILES['video']['name']);
  move_uploaded_file($_FILES['video']['tmp_name'], $uploadDir . $videoName);
}

// ✅ حفظ المقال فـ قاعدة البيانات
$query = "INSERT INTO articulos (
  id_admin, titulo_articulo, contenido, categoria_articulo,
  url_imagen, url_pdf, url_video
) VALUES (
  :id_admin, :titulo, :contenido, :categoria,
  :imagen, :pdf, :video
)";
$stmt = $db->prepare($query);
$stmt->bindParam(':id_admin', $id_admin);
$stmt->bindParam(':titulo', $titulo);
$stmt->bindParam(':contenido', $contenido);
$stmt->bindParam(':categoria', $categoria);
$stmt->bindParam(':imagen', $imagenName);
$stmt->bindParam(':pdf', $pdfName);
$stmt->bindParam(':video', $videoName);

if ($stmt->execute()) {
  http_response_code(201);
  echo json_encode(["status" => "success", "message" => "Artículo creado con archivos."]);
} else {
  http_response_code(500);
  echo json_encode(["status" => "error", "message" => "Error al guardar el artículo."]);
}
?>
