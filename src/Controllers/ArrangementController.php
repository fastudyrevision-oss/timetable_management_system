<?php
// src/Controllers/ArrangementController.php

namespace App\Controllers;

use App\Services\ArrangementEngine;

class ArrangementController
{
    private $pdo;
    private $engine;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        $this->engine = new ArrangementEngine($pdo);
    }

    public function editSlot($id)
    {
        // Fetch slot details
        $stmt = $this->pdo->prepare("SELECT * FROM timetable WHERE id = ?");
        $stmt->execute([$id]);
        $slot = $stmt->fetch();

        if (!$slot) {
            die("Slot not found");
        }

        // Fetch available resources for this slot's current time (default)
        $availableRooms = $this->engine->getAvailableRooms($slot['day'], $slot['time_slot']);
        $availableTeachers = $this->engine->getAvailableTeachers($slot['day'], $slot['time_slot']);

        // Include current room/teacher in specific lists so they can be kept
        // (This logic can be improved but for MVP we just list free ones)

        // Render edit modal/view (For MVP, a separate page)
        require '../src/Views/admin/edit_slot.php';
    }

    public function updateSlot()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $day = $_POST['day'];
            $time_slot = $_POST['time_slot'];
            $room_id = $_POST['room_id'];
            $teacher_id = $_POST['teacher_id'];
            $batch_id = $_POST['batch_id']; // Hidden field usually

            // Check conflicts (excluding self)
            // Implementation detail: The checkConflict logic in Engine needs to exclude the current record ID to be accurate for updates.
            // For MVP, we might skip self-exclusion check or handle it in Engine.

            // Allow update
            $stmt = $this->pdo->prepare("UPDATE timetable SET day=?, time_slot=?, room_id=?, teacher_id=?, status='scheduled' WHERE id=?");
            $stmt->execute([$day, $time_slot, $room_id, $teacher_id, $id]);

            $_SESSION['flash_message'] = "Slot updated successfully.";
            header('Location: /admin/timetable');
            exit;
        }
    }

    public function swapSlots()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);

            if (!isset($data['source_id']) || !isset($data['target_id'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Missing IDs']);
                return;
            }

            $this->pdo->beginTransaction();
            try {
                // Fetch Source and Target
                $source = $this->fetchSlot($data['source_id']);
                $target = $this->fetchSlot($data['target_id']);

                // Swap Days and Times
                // We keep the CLASS info (Subject, Batch, etc) anchored to the ID, 
                // but we swap the POSITION (Day, Time, Room?)
                // Actually, "Swapping Slots" in a drag and drop usually means:
                // "Class A is at Mon 9am Room 1" and "Class B is at Tue 10am Room 2"
                // Swap -> Class A goes to Tue 10am Room 2, Class B goes to Mon 9am Room 1.

                $s_day = $source['day'];
                $s_time = $source['time_slot'];
                $s_room = $source['room_id'];
                $t_day = $target['day'];
                $t_time = $target['time_slot'];
                $t_room = $target['room_id'];

                $stmt = $this->pdo->prepare("UPDATE timetable SET day=?, time_slot=?, room_id=? WHERE id=?");
                $stmt->execute([$t_day, $t_time, $t_room, $source['id']]);

                $stmt->execute([$s_day, $s_time, $s_room, $target['id']]);

                $this->pdo->commit();
                echo json_encode(['success' => true]);
            } catch (\Exception $e) {
                $this->pdo->rollBack();
                http_response_code(500);
                echo json_encode(['error' => $e->getMessage()]);
            }
            exit;
        }
    }

    // API to get available rooms
    public function apiAvailableRooms()
    {
        $day = $_GET['day'] ?? '';
        $time = $_GET['time'] ?? '';
        if (!$day || !$time) {
            echo json_encode([]);
            return;
        }

        $rooms = $this->engine->getAvailableRooms($day, $time);
        echo json_encode($rooms);
        exit;
    }

    // API to cancel/restore
    public function setStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
            $id = $data['id'] ?? 0;
            $status = $data['status'] ?? 'scheduled';

            $stmt = $this->pdo->prepare("UPDATE timetable SET status=? WHERE id=?");
            $stmt->execute([$status, $id]);
            echo json_encode(['success' => true]);
            exit;
        }
    }

    private function fetchSlot($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM timetable WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}
