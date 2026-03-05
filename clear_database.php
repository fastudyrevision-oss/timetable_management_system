<?php
// clear_database.php
require_once __DIR__ . '/config/db.php';

$tables = [
    'timetable',
    'subjects',
    'semesters',
    'sections',
    'batches',
    'teachers',
    'rooms',
    'users'
];

try {
    // Disable foreign key checks
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    
    foreach ($tables as $table) {
        $pdo->exec("TRUNCATE TABLE `$table`");
        echo "Truncated table: $table\n";
    }
    
    // Re-insert default admin
    $adminPassword = password_hash('password', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES ('admin', ?, 'admin')");
    $stmt->execute([$adminPassword]);
    echo "Default admin user re-created (password: password)\n";
    
    // Enable foreign key checks
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    
    echo "Database cleared successfully.\n";
} catch (Exception $e) {
    echo "Error clearing database: " . $e->getMessage() . "\n";
}
