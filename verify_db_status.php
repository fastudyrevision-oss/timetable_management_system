<?php
require_once __DIR__ . '/config/db.php';
$tables = ['users', 'timetable', 'subjects', 'semesters', 'sections', 'batches', 'teachers', 'rooms'];
foreach ($tables as $table) {
    $count = $pdo->query("SELECT COUNT(*) FROM `$table`")->fetchColumn();
    echo "$table: $count\n";
}
