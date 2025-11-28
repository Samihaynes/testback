<?php
require_once '../config/Database.php';
require_once '../vendor/autoload.php'; // Asegurar carga de JWT
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// CORS
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
header("Access-Control-Allow-Origin: $origin");
header("Access-Control-Allow-Methods: PUT, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') exit();

// Validar Token Localmente (Más seguro que depender de middleware externo si fallaba)
$headers = getallheaders();
$jwt = str_replace('Bearer ', '', $headers['Authorization'] ?? '');
$key = "Samihaynesprohackersluxury@1996*";

try {
    $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
    $userId = $decoded->data->id;
} catch (Exception $e) {
    http_response_code(401); exit(json_encode(['error' => 'Token inválido']));
}

// Lógica de Actualización
$id_notificacion = $_GET['id_notificacion'] ?? null;

if ($id_notificacion) {
    $db = (new Database())->getConnection();
    // Forzamos leida = 1
    $stmt = $db->prepare("UPDATE notificaciones SET leida = 1 WHERE id_notificacion = :id AND id_usuario = :user");
    $stmt->bindParam(':id', $id_notificacion);
    $stmt->bindParam(':user', $userId);
    
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        http_response_code(500); echo json_encode(['error' => 'DB Error']);
    }
}
?>