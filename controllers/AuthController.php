<?php
// controllers/AuthController.php
class AuthController {
    public function register($data) {
        // Validar email
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return ['error' => 'Correo inválido'];
        }
        // Validar contraseña (RF2)
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $data['password'])) {
            return ['error' => 'Contraseña debe tener al menos 8 caracteres, con mayúsculas, minúsculas, números y símbolos'];
        }
        // Verificar si email existe
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$data['email']]);
        if ($stmt->fetch()) {
            return ['error' => 'Email ya registrado'];
        }
        // Hash y guardar
        $hash = password_hash($data['password'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO usuarios (email, password_hash, role) VALUES (?, ?, 'usuario')");
        $stmt->execute([$data['email'], $hash]);
        return ['success' => true, 'message' => 'Usuario registrado'];
    }
}
?>