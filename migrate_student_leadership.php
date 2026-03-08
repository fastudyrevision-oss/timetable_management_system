<?php
// migrate_student_leadership.php
require_once __DIR__ . '/config/db.php';

try {
    echo "Creating student_leadership table...\n";
    $pdo->exec("CREATE TABLE IF NOT EXISTS student_leadership (
        id INT AUTO_INCREMENT PRIMARY KEY,
        role ENUM('head_cr', 'gr') NOT NULL UNIQUE,
        name VARCHAR(100) NOT NULL,
        description TEXT,
        picture VARCHAR(255) NULL,
        linkedin_url VARCHAR(255) NULL,
        email VARCHAR(100) NULL,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )");

    // Insert initial data if table is empty
    $count = $pdo->query("SELECT COUNT(*) FROM student_leadership")->fetchColumn();
    if ($count == 0) {
        echo "Inserting default student leadership data...\n";
        $stmt = $pdo->prepare("INSERT INTO student_leadership (role, name, description, picture, email) VALUES (?, ?, ?, ?, ?)");
        
        $stmt->execute([
            'head_cr', 
            'Abdullah Tariq', 
            'Leading the student body and ensuring effective communication between students and faculty.', 
            '/assets/images/head_cr_placeholder.png',
            'headcr@uos.edu.pk'
        ]);

        $stmt->execute([
            'gr', 
            'Ayesha Noor', 
            'Representing the interests and concerns of female students within the department.', 
            '/assets/images/gr_placeholder.png',
            'gr@uos.edu.pk'
        ]);
    }

    echo "Migration completed successfully!\n";

} catch (PDOException $e) {
    die("Error during migration: " . $e->getMessage() . "\n");
}
