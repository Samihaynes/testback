<?php
// middleware/AuthMiddleware.php
use Firebase\JWT\JWT;

class AuthMiddleware {
    public function __invoke($request, $response, $next) {
        $token = $request->getHeader('Authorization')[0] ?? '';
        if (!$token || !preg_match('/Bearer\s+(.*)$/i', $token, $matches)) {
            return $response->withJson(['error' => 'Token requerido'], 401);
        }
        try {
            $decoded = JWT::decode($matches[1], getenv('JWT_SECRET'), ['HS256']);
            // Verificar si usuario existe y está activo
            $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE id = ? AND active = 1");
            $stmt->execute([$decoded->user_id]);
            if (!$stmt->fetch()) {
                return $response->withJson(['error' => 'Usuario no autorizado'], 401);
            }
            $request = $request->withAttribute('user_id', $decoded->user_id);
        } catch (Exception $e) {
            return $response->withJson(['error' => 'Token inválido'], 401);
        }
        return $next($request, $response);
    }
}
?>