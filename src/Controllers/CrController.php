<?php
// src/Controllers/CrController.php

namespace App\Controllers;

class CrController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function dashboard()
    {
        if (!isset($_SESSION['batch_id']) || !isset($_SESSION['section_id'])) {
            die("CR Access Denied: No Batch/Section assigned.");
        }

        $batch_id = $_SESSION['batch_id'];
        $section_id = $_SESSION['section_id'];

        $sql = "SELECT t.*, s.name as subject_name, tr.name as teacher_name, r.name as room_name 
                FROM timetable t
                LEFT JOIN subjects s ON t.subject_id = s.id
                LEFT JOIN teachers tr ON t.teacher_id = tr.id
                LEFT JOIN rooms r ON t.room_id = r.id
                WHERE t.batch_id = ? AND t.section_id = ?
                ORDER BY t.day, t.time_slot";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$batch_id, $section_id]);
        $timetables = $stmt->fetchAll();

        // Grid organization
        $grid = [];
        foreach ($timetables as $slot) {
            $grid[$slot['day']][$slot['time_slot']][] = $slot;
        }

        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        $timeSlots = ['09:00-10:00', '10:00-11:00', '11:00-12:00', '12:00-01:00', '02:00-03:00'];

        require '../src/Views/cr/dashboard.php';
    }
}
