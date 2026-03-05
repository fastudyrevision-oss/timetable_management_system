<?php
// migrate_user_pictures.php
require_once 'config/db.php';

try {
    echo "Adding profile_picture column to users table...\n";
    $pdo->exec("ALTER TABLE users ADD COLUMN profile_picture VARCHAR(255) DEFAULT NULL AFTER role");
    echo "Migration completed successfully!\n";
} catch (PDOException $e) {
    if ($e->getCode() == '42S21') {
        echo "Column already exists. Skipping.\n";
    } else {
        die("Migration failed: " . $e->getMessage() . "\n");
    }
}
