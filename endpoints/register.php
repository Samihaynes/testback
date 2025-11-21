<?php

$allowedOrigins = ['http://localhost:3000', 'http://localhost:3001', 'http://localhost:3002', 'http://localhost:3003', 'http://192.168.1.237:3000'];
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

if (in_array($origin, $allowedOrigins)) {
    header("Access-Control-Allow-Origin: $origin");
}

header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

include_once '../config/Database.php';
$database = new Database();
$db = $database->getConnection();

// 1. قراءة البيانات القادمة من React
$data = json_decode(file_get_contents("php://input"));

// 2. التحقق من الحقول الأساسية
if (

    !empty($data->password) &&
    !empty($data->email)
) {
    // 3. التحقق من عدم وجود المستخدم مسبقاً
    $checkQuery = "SELECT id_usuario FROM usuarios WHERE nombre_usuario = :usuario OR email = :email";
    $checkStmt = $db->prepare($checkQuery);
    $checkStmt->bindParam(':usuario', $data->nombre_usuario);
    $checkStmt->bindParam(':email', $data->email);
    $checkStmt->execute();

    if ($checkStmt->rowCount() > 0) {
        http_response_code(409); // Conflict
        echo json_encode(["message" => "El usuario o email ya existe."]);
        exit();
    }

    // 4. تشفير كلمة المرور
    $hashedPassword = password_hash($data->password, PASSWORD_DEFAULT);

    // 5. إدخال البيانات في قاعدة البيانات
    $query = "INSERT INTO usuarios (
        nombre_usuario, email, password_hash, rol,
        nombre, apellido, telefono, pais, ciudad
    ) VALUES (
        :usuario, :email, :pass, 'usuario',
        :nombre, :apellido, :telefono, :pais, :ciudad
    )";

    $stmt = $db->prepare($query);
    $stmt->bindParam(':usuario', $data->nombre_usuario);
    $stmt->bindParam(':email', $data->email);
    $stmt->bindParam(':pass', $hashedPassword);
    $stmt->bindParam(':nombre', $data->nombre);
    $stmt->bindParam(':apellido', $data->apellido);
    $stmt->bindParam(':telefono', $data->telefono);
    $stmt->bindParam(':pais', $data->pais);
    $stmt->bindParam(':ciudad', $data->ciudad);

    if ($stmt->execute()) {
        http_response_code(201); // Created
        echo json_encode(["message" => "Usuario registrado correctamente."]);
    } else {
        http_response_code(503); // Service Unavailable
        echo json_encode(["message" => "Error al registrar usuario."]);
    }
} else {
    http_response_code(400); // Bad Request
    echo json_encode(["message" => "Faltan campos obligatorios."]);
}
?>
