<?php
require_once __DIR__ . '/config/db.php';
try {
    $pdo->exec("ALTER TABLE teachers ADD COLUMN `picture` VARCHAR(255) NULL, ADD COLUMN `post` VARCHAR(100) NULL, ADD COLUMN `qualification` TEXT NULL, ADD COLUMN `research_interest` TEXT NULL;");
    echo "Columns added successfully.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
