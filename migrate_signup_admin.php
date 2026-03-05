<?php
// migrate_signup_admin.php
require_once __DIR__ . '/config/db.php';

try {
    // 1. Add roll_number to users table
    echo "Adding roll_number to users table...\n";
    $pdo->exec("ALTER TABLE users ADD COLUMN roll_number VARCHAR(50) NULL UNIQUE AFTER username");

    // 2. Create audit_logs table
    echo "Creating audit_logs table...\n";
    $pdo->exec("CREATE TABLE IF NOT EXISTS audit_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NULL,
        action VARCHAR(100) NOT NULL,
        details TEXT,
        ip_address VARCHAR(45) NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
    )");

    echo "Migration completed successfully!\n";

} catch (PDOException $e) {
    die("Error during migration: " . $e->getMessage() . "\n");
}
