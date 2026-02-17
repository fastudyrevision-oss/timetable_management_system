<?php
require_once __DIR__ . '/config/db.php';

try {
    $sql = file_get_contents(__DIR__ . '/database.sql');
    $pdo->exec($sql);
    echo "Database setup successfully.\n";
} catch (PDOException $e) {
    echo "Error setting up database: " . $e->getMessage() . "\n";
}
