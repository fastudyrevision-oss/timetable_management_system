<?php
// src/Services/ArrangementEngine.php

namespace App\Services;

class ArrangementEngine
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Check for conflicts (Room, Teacher, Student Batch)
    public function checkConflict($day, $start, $end, $roomId, $teacherId, $batchId, $excludeId = null)
    {
        // 1. Room Conflict
        if ($roomId) {
            $sql = "SELECT * FROM timetable 
                    WHERE day = ? AND room_id = ? AND status != 'cancelled'
                    AND start_time < ? AND end_time > ?";
            $params = [$day, $roomId, $end, $start];
            if ($excludeId) {
                $sql .= " AND id != ?";
                $params[] = $excludeId;
            }
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            if ($stmt->fetch())
                return "Room Conflict";
        }

        // 2. Teacher Conflict
        if ($teacherId) {
            $sql = "SELECT * FROM timetable 
                    WHERE day = ? AND teacher_id = ? AND status != 'cancelled'
                    AND start_time < ? AND end_time > ?";
            $params = [$day, $teacherId, $end, $start];
            if ($excludeId) {
                $sql .= " AND id != ?";
                $params[] = $excludeId;
            }
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            if ($stmt->fetch())
                return "Teacher Conflict";
        }

        // 3. Batch Conflict
        if ($batchId) {
            $sql = "SELECT * FROM timetable 
                    WHERE day = ? AND batch_id = ? AND status != 'cancelled'
                    AND start_time < ? AND end_time > ?";
            $params = [$day, $batchId, $end, $start];
            if ($excludeId) {
                $sql .= " AND id != ?";
                $params[] = $excludeId;
            }
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            if ($stmt->fetch())
                return "Batch Conflict";
        }

        return null; // No conflict
    }

    public function getAvailableRooms($day, $start, $end)
    {
        // Find rooms NOT occupied during this interval
        // Overlap logic: A overlaps B if (StartA < EndB) AND (EndA > StartB)
        // We want rooms where NO class overlaps.
        
        $sql = "SELECT * FROM rooms 
                WHERE id NOT IN (
                    SELECT room_id FROM timetable 
                    WHERE day = ? 
                    AND status != 'cancelled' 
                    AND room_id IS NOT NULL
                    AND start_time IS NOT NULL 
                    AND end_time IS NOT NULL
                    AND (
                        (start_time < ? AND end_time > ?)
                    )
                )
                ORDER BY name";
        
        $stmt = $this->pdo->prepare($sql);
        // Param 1: Day
        // Param 2: Query End (If existing class starts before requested end)
        // Param 3: Query Start (If existing class ends after requested start)
        // Logic: Overlap = Class.Start < Req.End AND Class.End > Req.Start
        $stmt->execute([$day, $end, $start]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAvailableTeachers($day, $start, $end)
    {
        $sql = "SELECT * FROM teachers 
                WHERE id NOT IN (
                    SELECT teacher_id FROM timetable 
                    WHERE day = ? AND status != 'cancelled' AND teacher_id IS NOT NULL
                    AND start_time < ? AND end_time > ?
                )
                ORDER BY name";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$day, $end, $start]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Check if a specific slot swap is valid
    public function canSwap($slotId1, $slotId2)
    {
        $s1 = $this->fetchSlot($slotId1);
        $s2 = $this->fetchSlot($slotId2);

        if (!$s1 || !$s2)
            return false;

        // MVP: Allow swap without deep conflict check, or perform check here?
        // Let's assume Admin overrides.
        return true;
    }

    private function fetchSlot($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM timetable WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}
