<?php
// utils/NotificationUtils.php
// Este archivo está diseñado para manejar la lógica de creación de notificaciones (RF9).

class NotificationUtils {
    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /**
     * Crea una nueva notificación en la tabla 'notificaciones'.
     * @param int $id_usuario_destino ID del usuario que recibirá la notificación.
     * @param string $mensaje Mensaje de la notificación.
     * @param string $tipo Tipo de notificación (ej: 'voto', 'respuesta').
     */
    public function crearNotificacion($id_usuario_destino, $mensaje, $tipo = 'general') {
        // Lógica de inserción en la tabla 'notificaciones'
        $query = "INSERT INTO notificaciones (id_usuario, mensaje, tipo, fecha) 
                  VALUES (:id_usuario, :mensaje, :tipo, NOW())";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id_usuario', $id_usuario_destino);
        $stmt->bindParam(':mensaje', $mensaje);
        $stmt->bindParam(':tipo', $tipo);
        
        try {
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            // Manejar error silenciosamente o loguear
            error_log("Error al crear notificación: " . $e->getMessage());
            return false;
        }
    }
}
?>