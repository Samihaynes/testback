<?php
// utils/NotificationUtils.php
// Este archivo está diseñado para manejar la lógica de creación de notificaciones (RF9).

class NotificationUtils {
    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    // Añadimos el parámetro $id_item (el ID de la consulta)
    public function crearNotificacion($id_usuario_destino, $mensaje, $tipo = 'general', $id_item = null) {
        $query = "INSERT INTO notificaciones (id_usuario, mensaje, tipo, id_item, fecha) 
                  VALUES (:id_usuario, :mensaje, :tipo, :id_item, NOW())";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id_usuario', $id_usuario_destino);
        $stmt->bindParam(':mensaje', $mensaje);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':id_item', $id_item); // Guardamos el ID para el enlace
        
        try {
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>