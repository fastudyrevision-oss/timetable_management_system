<?php
// migrate_admin_approval.php
require_once __DIR__ . '/config/db.php';

try {
    echo "Updating users table with email, phone_number, and is_approved status...\n";
    
    // Check if columns already exist to avoid errors
    $columns = $pdo->query("SHOW COLUMNS FROM users")->fetchAll(PDO::FETCH_COLUMN);
    
    if (!in_array('email', $columns)) {
        $pdo->exec("ALTER TABLE users ADD COLUMN email VARCHAR(100) NULL AFTER roll_number");
        echo "Added 'email' column.\n";
    }
    
    if (!in_array('phone_number', $columns)) {
        $pdo->exec("ALTER TABLE users ADD COLUMN phone_number VARCHAR(20) NULL AFTER email");
        echo "Added 'phone_number' column.\n";
    }
    
    if (!in_array('is_approved', $columns)) {
        $pdo->exec("ALTER TABLE users ADD COLUMN is_approved TINYINT(1) DEFAULT 0 AFTER role");
        echo "Added 'is_approved' column.\n";
        
        // Auto-approve existing admin
        $pdo->exec("UPDATE users SET is_approved = 1 WHERE role = 'admin'");
        echo "Auto-approved existing admins.\n";
    }

    echo "Migration completed successfully!\n";

} catch (PDOException $e) {
    die("Error during migration: " . $e->getMessage() . "\n");
}
