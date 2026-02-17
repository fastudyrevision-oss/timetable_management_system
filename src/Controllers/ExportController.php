<?php
// src/Controllers/ExportController.php

namespace App\Controllers;

use PDO;

class ExportController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function exportJson()
    {
        $sql = "SELECT t.*, s.name as subject_name, tr.name as teacher_name, r.name as room_name, sec.name as section_name, b.name as batch_name 
                FROM timetable t
                LEFT JOIN subjects s ON t.subject_id = s.id
                LEFT JOIN teachers tr ON t.teacher_id = tr.id
                LEFT JOIN rooms r ON t.room_id = r.id
                LEFT JOIN sections sec ON t.section_id = sec.id
                LEFT JOIN batches b ON t.batch_id = b.id
                ORDER BY t.day, t.time_slot";

        $stmt = $this->pdo->query($sql);
        $data = $stmt->fetchAll();

        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="timetable.json"');
        echo json_encode($data, JSON_PRETTY_PRINT);
        exit;
    }

    public function exportCsv()
    {
        $sql = "SELECT t.day, t.time_slot, b.name as batch, sec.name as section, s.name as subject, tr.name as teacher, r.name as room 
                FROM timetable t
                LEFT JOIN subjects s ON t.subject_id = s.id
                LEFT JOIN teachers tr ON t.teacher_id = tr.id
                LEFT JOIN rooms r ON t.room_id = r.id
                LEFT JOIN sections sec ON t.section_id = sec.id
                LEFT JOIN batches b ON t.batch_id = b.id
                ORDER BY t.day, t.time_slot";

        $stmt = $this->pdo->query($sql);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="timetable.csv"');

        $output = fopen('php://output', 'w');

        // Header
        if (count($data) > 0) {
            fputcsv($output, array_keys($data[0]));
        }

        foreach ($data as $row) {
            fputcsv($output, $row);
        }

        fclose($output);
        exit;
    }
}
