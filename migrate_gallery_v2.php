<?php
// migrate_gallery_v2.php
require_once __DIR__ . '/config/db.php';

try {
    echo "Adding display_order column to gallery table...\n";
    $pdo->exec("ALTER TABLE gallery ADD COLUMN display_order INT DEFAULT 0");

    echo "Migration completed successfully!\n";

} catch (PDOException $e) {
    die("Error during migration: " . $e->getMessage() . "\n");
}
