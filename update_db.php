<?php
require_once 'config/Database.php';

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

    // Check if consultas table has data
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM consultas");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Current consultas count: " . $result['count'] . "\n";

} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}
?>
