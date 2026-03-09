<?php
// migrate_gallery.php
require_once __DIR__ . '/config/db.php';

try {
    echo "Creating gallery table...\n";
    $pdo->exec("CREATE TABLE IF NOT EXISTS gallery (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(200) NOT NULL,
        media_path VARCHAR(255) NOT NULL,
        media_type ENUM('image', 'video') NOT NULL,
        category ENUM('event', 'department') NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    echo "Migration completed successfully!\n";

} catch (PDOException $e) {
    die("Error during migration: " . $e->getMessage() . "\n");
}
