<?php
// test_db.php - Script para probar la conexión a la base de datos

require_once 'config/Database.php';

try {
    $db = new Database();
    $pdo = $db->getConnection();
    echo "✅ Conexión exitosa a la base de datos MySQL\n";

    // Verificar si las tablas existen
    $tables = ['usuarios', 'vehiculos', 'consultas', 'respuestas', 'votos', 'articulos', 'comentarios_articulo'];

    foreach ($tables as $table) {
        $stmt = $pdo->prepare("SHOW TABLES LIKE ?");
        $stmt->execute([$table]);
        if ($stmt->rowCount() > 0) {
            echo "✅ Tabla '$table' existe\n";
        } else {
            echo "❌ Tabla '$table' NO existe\n";
        }
    }

    // Contar registros en cada tabla
    echo "\n📊 Registros en las tablas:\n";
    foreach ($tables as $table) {
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM $table");
        $stmt->execute();
        $count = $stmt->fetch()['count'];
        echo "  $table: $count registros\n";
    }

} catch (PDOException $e) {
    echo "❌ Error de conexión: " . $e->getMessage() . "\n";
}
?>
