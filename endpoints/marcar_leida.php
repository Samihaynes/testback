<?php
require_once '../config/Database.php';
require_once '../middleware/AuthMiddleware.php';

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
    exit(0);
}

// Verificar autenticación JWT
$user = AuthMiddleware::verifyToken();
if (!$user) {
    http_response_code(401);
    echo json_encode(['error' => 'Token inválido o expirado']);
    exit;
}

$db = (new Database())->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Marcar notificación como leída
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id_notificacion'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Faltan campos requeridos: id_notificacion']);
        exit;
    }

    $id_notificacion = $data['id_notificacion'];
    $id_usuario = $user['id_usuario'];

    // Verificar que la notificación existe y pertenece al usuario
    $stmt = $db->prepare("SELECT id_usuario FROM notificaciones WHERE id_notificacion = ?");
    $stmt->execute([$id_notificacion]);
    $notificacion = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$notificacion) {
        http_response_code(404);
        echo json_encode(['error' => 'Notificación no encontrada']);
        exit;
    }

    if ($notificacion['id_usuario'] != $id_usuario) {
        http_response_code(403);
        echo json_encode(['error' => 'No tienes permiso para marcar esta notificación como leída']);
        exit;
    }

    // Marcar como leída
    $stmt = $db->prepare("UPDATE notificaciones SET leida = TRUE WHERE id_notificacion = ?");
    $stmt->execute([$id_notificacion]);

    echo json_encode([
        'message' => 'Notificación marcada como leída exitosamente'
    ]);

} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
}
?>
