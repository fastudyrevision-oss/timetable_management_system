<?php
// migrate_societies.php
require_once __DIR__ . '/config/db.php';

try {
    // 1. Update users table role enum and add society_id
    echo "Updating users table...\n";
    $pdo->exec("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'cr', 'gr', 'president') NOT NULL");
    $pdo->exec("ALTER TABLE users ADD COLUMN society_id INT NULL AFTER section_id");

    // 2. Create societies table
    echo "Creating societies table...\n";
    $pdo->exec("CREATE TABLE IF NOT EXISTS societies (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL UNIQUE,
        description TEXT,
        logo_path VARCHAR(255) NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // 3. Create society_members table
    echo "Creating society_members table...\n";
    $pdo->exec("CREATE TABLE IF NOT EXISTS society_members (
        id INT AUTO_INCREMENT PRIMARY KEY,
        society_id INT NOT NULL,
        name VARCHAR(100) NOT NULL,
        designation VARCHAR(100) NOT NULL,
        picture_path VARCHAR(255) NULL,
        is_president BOOLEAN DEFAULT FALSE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (society_id) REFERENCES societies(id) ON DELETE CASCADE
    )");

    // 4. Create society_events table
    echo "Creating society_events table...\n";
    $pdo->exec("CREATE TABLE IF NOT EXISTS society_events (
        id INT AUTO_INCREMENT PRIMARY KEY,
        society_id INT NOT NULL,
        title VARCHAR(200) NOT NULL,
        description TEXT,
        event_date DATETIME NOT NULL,
        poster_path VARCHAR(255) NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (society_id) REFERENCES societies(id) ON DELETE CASCADE
    )");

    // 5. Create society_news table
    echo "Creating society_news table...\n";
    $pdo->exec("CREATE TABLE IF NOT EXISTS society_news (
        id INT AUTO_INCREMENT PRIMARY KEY,
        society_id INT NOT NULL,
        title VARCHAR(200) NOT NULL,
        content TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (society_id) REFERENCES societies(id) ON DELETE CASCADE
    )");

    // 6. Insert initial societies
    echo "Inserting initial societies...\n";
    $societies = [
        ['name' => 'Event Management Society', 'description' => 'Organizing and managing department events.'],
        ['name' => 'GMS', 'description' => 'Graphics and Media Society.'],
        ['name' => 'Welfare Society', 'description' => 'Student welfare and social activities.']
    ];

    $stmt = $pdo->prepare("INSERT IGNORE INTO societies (name, description) VALUES (?, ?)");
    foreach ($societies as $s) {
        $stmt->execute([$s['name'], $s['description']]);
    }

    echo "Migration completed successfully!\n";

} catch (PDOException $e) {
    die("Error during migration: " . $e->getMessage() . "\n");
}
