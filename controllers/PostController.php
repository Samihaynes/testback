<?php
// controllers/PostController.php
class PostController {
    public function createPost($data, $userId) {
        // Sanitizar entrada para prevenir XSS
        $title = htmlspecialchars($data['title'], ENT_QUOTES, 'UTF-8');
        $description = htmlspecialchars($data['description'], ENT_QUOTES, 'UTF-8');
        $vin = strtoupper(trim($data['vin']));  // Normalizar VIN
        
        // Validar VIN y obtener datos
        $vinService = new VinService();
        try {
            $vehicleData = $vinService->getVehicleData($vin);
            $vehicleJson = json_encode($vehicleData);  // Guardar como JSON
        } catch (Exception $e) {
            return ['error' => 'Error al consultar VIN: ' . $e->getMessage()];
        }
        
        // Insertar en DB
        $stmt = $pdo->prepare("INSERT INTO posts (user_id, title, description, vin, vehicle_data) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$userId, $title, $description, $vin, $vehicleJson]);
        return ['success' => true, 'post_id' => $pdo->lastInsertId()];
    }
}
?>