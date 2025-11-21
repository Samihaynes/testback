<?php
require_once 'config/Database.php';

// Add notificaciones table if it doesn't exist
try {
    $db = (new Database())->getConnection();

    // Check if notificaciones table exists
    $stmt = $db->prepare("SHOW TABLES LIKE 'notificaciones'");
    $stmt->execute();
    $exists = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$exists) {
        // Create the notificaciones table
        $createTableQuery = "
            CREATE TABLE notificaciones (
                id_notificacion INT AUTO_INCREMENT PRIMARY KEY,
                id_usuario INT NOT NULL,
                tipo ENUM('respuesta','voto','sistema') NOT NULL,
                mensaje VARCHAR(255) NOT NULL,
                leida BOOLEAN DEFAULT FALSE,
                fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
            )
        ";
        $db->exec($createTableQuery);
        echo "Table 'notificaciones' created successfully.\n";
    } else {
        echo "Table 'notificaciones' already exists.\n";
    }

} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}

try {
    $db = (new Database())->getConnection();

    // Check if descripcion column exists
    $stmt = $db->prepare("SHOW COLUMNS FROM consultas LIKE 'descripcion'");
    $stmt->execute();
    $exists = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$exists) {
        // Add the descripcion column
        $alterQuery = "ALTER TABLE consultas ADD COLUMN descripcion TEXT NOT NULL AFTER titulo";
        $db->exec($alterQuery);
        echo "Column 'descripcion' added successfully to 'consultas' table.\n";
    } else {
        echo "Column 'descripcion' already exists.\n";
    }

    // Check if attachments column exists in consultas
    $stmt = $db->prepare("SHOW COLUMNS FROM consultas LIKE 'attachments'");
    $stmt->execute();
    $exists = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$exists) {
        // Add the attachments column
        $alterQuery = "ALTER TABLE consultas ADD COLUMN attachments JSON DEFAULT NULL";
        $db->exec($alterQuery);
        echo "Column 'attachments' added successfully to 'consultas' table.\n";
    } else {
        echo "Column 'attachments' already exists in 'consultas' table.\n";
    }

    // Check if attachments column exists in respuestas
    $stmt = $db->prepare("SHOW COLUMNS FROM respuestas LIKE 'attachments'");
    $stmt->execute();
    $exists = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$exists) {
        // Add the attachments column
        $alterQuery = "ALTER TABLE respuestas ADD COLUMN attachments JSON DEFAULT NULL";
        $db->exec($alterQuery);
        echo "Column 'attachments' added successfully to 'respuestas' table.\n";
    } else {
        echo "Column 'attachments' already exists in 'respuestas' table.\n";
    }

    // Check if consultas table has data
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM consultas");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Current consultas count: " . $result['count'] . "\n";

} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}
?>
