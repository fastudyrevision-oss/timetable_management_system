<?php
require_once __DIR__ . '/config/db.php';
try {
    $pdo->exec("ALTER TABLE teachers ADD COLUMN `is_faculty` BOOLEAN DEFAULT FALSE;");
    echo "Column 'is_faculty' added successfully.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
