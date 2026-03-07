<?php
// src/Controllers/TimetableController.php

namespace App\Controllers;

use App\Services\PdfParserService;

class TimetableController
{
    private $pdo;
    private $pdfParser;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        $this->pdfParser = new PdfParserService();
    }

    public function index()
    {
        $data = $this->getTimetableData();
        extract($data);
        require '../src/Views/admin/timetable.php';
    }

    public function publicIndex()
    {
        $data = $this->getTimetableData();
        extract($data);
        require '../src/Views/public/timetable.php';
    }

    public function publicFaculty()
    {
        $stmt = $this->pdo->query("SELECT * FROM teachers WHERE is_faculty = 1 ORDER BY 
            (CASE 
                WHEN post LIKE '%Chairman%' THEN 0 
                WHEN post LIKE '%CSA%' THEN 1
                WHEN post LIKE '%Deputy Registrar%' THEN 2
                ELSE 3 
            END), name ASC");
        $teachers = $stmt->fetchAll();
        require '../src/Views/public/faculty.php';
    }

    public function publicSocieties()
    {
        require '../src/Views/public/societies.php';
    }

    private function getTimetableData()
    {
        // 1. Fetch Filter Data
        $semStmt = $this->pdo->query("SELECT s.id, s.number, b.name as batch_name 
                                      FROM semesters s 
                                      JOIN batches b ON s.batch_id = b.id 
                                      ORDER BY b.name, s.number");
        $semesters = $semStmt->fetchAll();

        $batchesStmt = $this->pdo->query("SELECT * FROM batches ORDER BY name");
        $batches = $batchesStmt->fetchAll();

        // Fetch Sections if Batch selected, else all (or empty?)
        // For UI simplicity, we might fetch all and let JS filter, or just fetch all.
        $sectionsStmt = $this->pdo->query("SELECT s.*, b.name as batch_name FROM sections s JOIN batches b ON s.batch_id = b.id ORDER BY b.name, s.name");
        $sections = $sectionsStmt->fetchAll();

        // 2. Build Query with Filters
        $where = ["1=1"];
        $params = [];

        if (!empty($_GET['semester_id'])) {
            $where[] = "t.semester_id = ?";
            $params[] = $_GET['semester_id'];
        }

        if (!empty($_GET['batch_id'])) {
            $where[] = "t.batch_id = ?";
            $params[] = $_GET['batch_id'];
        }

        if (!empty($_GET['section_id'])) {
            $where[] = "t.section_id = ?";
            $params[] = $_GET['section_id'];
        }

        if (!empty($_GET['subject_search'])) {
            $where[] = "s.name LIKE ?";
            $params[] = '%' . $_GET['subject_search'] . '%';
        }

        if (!empty($_GET['teacher_search'])) {
            $where[] = "tr.name LIKE ?";
            $params[] = '%' . $_GET['teacher_search'] . '%';
        }

        $whereClause = implode(" AND ", $where);

        $sql = "SELECT t.*, s.name as subject_name, s.code as subject_code, 
                       tr.name as teacher_name, r.name as room_name, 
                       sec.name as section_name, sec.type as section_type, b.name as batch_name, sem.number as semester_num 
                FROM timetable t
                LEFT JOIN subjects s ON t.subject_id = s.id
                LEFT JOIN teachers tr ON t.teacher_id = tr.id
                LEFT JOIN rooms r ON t.room_id = r.id
                LEFT JOIN sections sec ON t.section_id = sec.id
                LEFT JOIN batches b ON t.batch_id = b.id
                LEFT JOIN semesters sem ON t.semester_id = sem.id
                WHERE $whereClause
                ORDER BY t.day, t.time_slot";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $timetables = $stmt->fetchAll();

        // 3. Organize data
        $roomStmt = $this->pdo->query("SELECT * FROM rooms ORDER BY name");
        $allRooms = $roomStmt->fetchAll();

        $matrix = [];
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        
        foreach ($timetables as $row) {
            $d = $row['day'];
            $rid = $row['room_id'] ?? 0;
            $matrix[$d][$rid][] = $row;
        }

        return compact('semesters', 'batches', 'sections', 'timetables', 'allRooms', 'matrix', 'days');
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle manual creation
            $batchId = $_POST['batch_id'] ?? null;
            $sectionId = $_POST['section_id'] ?? null;
            $subjectId = $_POST['subject_id'] ?? null;
            $teacherId = $_POST['teacher_id'] ?? null;
            $roomId = $_POST['room_id'] ?? null;
            $day = $_POST['day'] ?? null;
            $timeSlot = $_POST['time_slot'] ?? null;

            if ($batchId && $sectionId && $subjectId && $day && $timeSlot) {
                // Fetch semester for batch (MVP: assume sem 1 or user selects)
                // For now, let's just insert.
                // Ideally user selects semester too.
                $semesterId = $_POST['semester_id'] ?? 1; // Default
                
                $times = $this->parseTimeSlot($timeSlot);

                $stmt = $this->pdo->prepare("INSERT INTO timetable 
                    (batch_id, section_id, semester_id, subject_id, teacher_id, room_id, day, time_slot, start_time, end_time, status)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'scheduled')");
                $stmt->execute([$batchId, $sectionId, $semesterId, $subjectId, $teacherId, $roomId, $day, $timeSlot, $times[0], $times[1]]);
                $_SESSION['flash_message'] = "Class added successfully.";
            } else {
                $_SESSION['flash_message'] = "Error: Missing required fields.";
            }
            header('Location: /admin/timetable');
            exit;
        }
    }

    public function upload()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['timetable_pdf'])) {
            $file = $_FILES['timetable_pdf'];
            $uploadDir = __DIR__ . '/../../public/uploads/';
            if (!is_dir($uploadDir))
                mkdir($uploadDir, 0777, true);

            $filePath = $uploadDir . basename($file['name']);
            move_uploaded_file($file['tmp_name'], $filePath);

            try {
                // Call Python Parser via Service
                $parsedData = $this->pdfParser->parseTimetable($filePath);

                if (isset($parsedData['error'])) {
                    $_SESSION['flash_message'] = "Error parsing PDF: " . $parsedData['error'];
                } else {
                    $_SESSION['parsed_data'] = $parsedData;
                    $_SESSION['flash_message'] = "PDF parsed successfully! Please review data.";
                }

            } catch (\Exception $e) {
                $_SESSION['flash_message'] = "Error: " . $e->getMessage();
            }

            header('Location: /admin/timetable');
            exit;
        }
        header('Location: /admin/timetable');
        exit;
    }

    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['parsed_data'])) {
            $data = $_SESSION['parsed_data'];
            $count = 0;

            try {
                $this->pdo->beginTransaction();

                // 1. First, identify all unique groups in the parsed data and clear their existing timetable
                $affectedGroups = [];
                foreach ($data as $row) {
                    // New parser uses 'programme' instead of 'batch' and 'section'
                    $programme = $row['programme'] ?? 'General';
                    // Very basic extraction: 
                    // Assume everything before "Semester#" is the batch/section info
                    // This is a naive split just to keep it unique per group as it was before.
                    $parts = explode('Semester#', $programme);
                    $batchInfo = trim($parts[0]);
                    
                    // We'll treat the whole name as the batch for now if we can't extract section easily
                    // Or we can try to find "Practical" or section details.
                    // For the clearing phase, uniqueness is what matters most.
                    $batchName = substr($batchInfo, 0, 50);
                    
                    $sectionName = 'Regular';
                    if (preg_match('/(Self Support \d+)/i', $programme, $smatch)) {
                        $sectionName = $smatch[1];
                    } elseif (stripos($programme, 'Self Support') !== false) {
                        $sectionName = 'Self Support';
                    }

                    if (stripos($programme, 'Practical') !== false) {
                        $sectionName = ($sectionName === 'Regular') ? 'Practical' : $sectionName . ' (Practical)';
                    }
                    
                    $batchId = $this->getOrCreate('batches', 'name', $batchName);
                    $sectionId = $this->getOrCreate('sections', 'name', $sectionName, ['batch_id' => $batchId]);
                    $groupKey = "$batchId-$sectionId";
                    if (!isset($affectedGroups[$groupKey])) {
                        $affectedGroups[$groupKey] = ['batch_id' => $batchId, 'section_id' => $sectionId];
                        // Clear existing timetable for this specific Group
                        $delStmt = $this->pdo->prepare("DELETE FROM timetable WHERE batch_id = ? AND section_id = ?");
                        $delStmt->execute([$batchId, $sectionId]);
                    }
                }

                // 2. Insert new data
                foreach ($data as $row) {
                    if (empty($row['day']) || empty($row['start_time']))
                        continue;

                    $subjectName = substr($row['subject'] ?? 'Unknown Subject', 0, 100);
                    $teacherName = substr($row['teacher'] ?? 'TBA', 0, 100);
                    $roomName = substr($row['room'] ?? 'TBA', 0, 50);
                    $subjectCode = substr($row['course_code'] ?? null, 0, 20);
                    
                    $programme = $row['programme'] ?? 'General';
                    $parts = explode('Semester#', $programme);
                    $batchName = substr(trim($parts[0]), 0, 50);
                    
                    // Identify sections
                    $sectionsFound = [];
                    if (preg_match_all('/Self Support (\d+)/i', $programme, $matches)) {
                        foreach ($matches[1] as $num) {
                            $sectionsFound[] = "Self Support $num";
                        }
                    } elseif (stripos($programme, 'Self Support') !== false) {
                        $sectionsFound = ['Self Support'];
                    }

                    // Also check for things like "1 & 2" or "1, 2" after "Self Support"
                    if (stripos($programme, 'Self Support') !== false && empty($sectionsFound)) {
                         if (preg_match('/Self Support\s*(\d+)\s*(?:&|,|and)\s*(\d+)/i', $programme, $sm)) {
                             $sectionsFound = ["Self Support {$sm[1]}", "Self Support {$sm[2]}"];
                         }
                    }

                    // If no specific Self Support sections found, default to 'Regular'
                    if (empty($sectionsFound)) {
                        $sectionsFound = ['Regular'];
                    }

                    // Add 'Practical' if present
                    if (stripos($programme, 'Practical') !== false) {
                        foreach ($sectionsFound as $key => $section) {
                            $sectionsFound[$key] = ($section === 'Regular') ? 'Practical' : $section . ' (Practical)';
                        }
                    }
                    
                    $semNum = '1';
                    if (count($parts) > 1) {
                         if (preg_match('/(\d+)/', $parts[1], $matches)) {
                             $semNum = $matches[1];
                         }
                    }

                    $semesterId = $this->getOrCreateSemester($semNum, $batchName);

                    // Subject Matching: Code is unique identifier
                    $subjectId = null;
                    if (!empty($subjectCode) && $subjectCode !== 'TBA') {
                        $stmtCode = $this->pdo->prepare("SELECT id FROM subjects WHERE code = ?");
                        $stmtCode->execute([$subjectCode]);
                        $subjectId = $stmtCode->fetchColumn();
                    }
                    if (!$subjectId) {
                        $stmtName = $this->pdo->prepare("SELECT id FROM subjects WHERE name = ?");
                        $stmtName->execute([$subjectName]);
                        $subjectId = $stmtName->fetchColumn();
                        if ($subjectId && !empty($subjectCode) && $subjectCode !== 'TBA') {
                            $this->pdo->prepare("UPDATE subjects SET code = ? WHERE id = ?")->execute([$subjectCode, $subjectId]);
                        }
                    }
                    if (!$subjectId) {
                        $subjectId = $this->getOrCreate('subjects', 'name', $subjectName, ['code' => $subjectCode, 'semester_id' => $semesterId]);
                    }

                    $teacherId = $this->getOrCreate('teachers', 'name', $teacherName);
                    $roomId = (!empty($roomName) && $roomName !== 'TBA') ? $this->getOrCreate('rooms', 'name', $roomName) : null;
                    $batchId = $this->getOrCreate('batches', 'name', $batchName);

                    $start_time = $row['start_time'] ?? null;
                    $end_time = $row['end_time'] ?? null;
                    
                    // The new parser doesn't provide time_slot directly, so we reconstruct it
                    $time_slot = "$start_time - $end_time";
                    
                    if (!$start_time || !$end_time) {
                        $times = $this->parseTimeSlot($row['time_slot']);
                        $start_time = $times[0];
                        $end_time = $times[1];
                    }
                    
                    foreach ($sectionsFound as $sectionName) {
                        $sectionId = $this->getOrCreate('sections', 'name', $sectionName, ['batch_id' => $batchId]);

                        $stmt = $this->pdo->prepare("INSERT INTO timetable 
                            (batch_id, section_id, semester_id, subject_id, teacher_id, room_id, day, time_slot, start_time, end_time, status)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'scheduled')");
                        $stmt->execute([$batchId, $sectionId, $semesterId, $subjectId, $teacherId, $roomId, $row['day'], $time_slot, $start_time, $end_time]);
                        $count++;
                    }
                }

                $this->pdo->commit();
                unset($_SESSION['parsed_data']);
                $_SESSION['flash_message'] = "Successfully processed $count classes (Merged/Saved)!";

            } catch (\Exception $e) {
                $this->pdo->rollBack();
                // Log error for debugging
                error_log($e->getMessage());
                $_SESSION['flash_message'] = "Error saving data: " . $e->getMessage();
            }

            header('Location: /admin/timetable');
            exit;
        }
    }

    public function clear()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin')
                exit;

            try {
                $this->pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
                
                $tables = ['timetable', 'subjects', 'semesters', 'sections', 'batches', 'rooms'];
                foreach ($tables as $table) {
                    $this->pdo->exec("TRUNCATE TABLE `$table`");
                }
                
                // Preserve faculty teachers
                $this->pdo->exec("DELETE FROM teachers WHERE is_faculty = 0");
                
                $this->pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
                
                $_SESSION['flash_message'] = "All database data has been cleared successfully.";
            } catch (\Exception $e) {
                $_SESSION['flash_message'] = "Error clearing data: " . $e->getMessage();
            }
            header('Location: /admin/timetable');
            exit;
        }
    }

    private function getOrCreate($table, $col, $value, $extra = [])
    {
        if (empty($value))
            return null;
        $stmt = $this->pdo->prepare("SELECT id FROM $table WHERE $col = ?");
        $stmt->execute([$value]);
        $id = $stmt->fetchColumn();
        if ($id)
            return $id;

        $cols = [$col];
        $vals = [$value];
        foreach ($extra as $k => $v) {
            if ($v !== null) {
                $cols[] = $k;
                $vals[] = $v;
            }
        }
        $sql = "INSERT INTO $table (" . implode(',', $cols) . ") VALUES (" . implode(',', array_fill(0, count($cols), '?')) . ")";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($vals);
        return $this->pdo->lastInsertId();
    }

    private function getOrCreateSemester($number, $batchName)
    {
        $batchId = $this->getOrCreate('batches', 'name', $batchName);

        $cleanNumber = 0;
        if (is_numeric($number)) {
            $cleanNumber = (int) $number;
        } else {
            if (preg_match('/(\d+)/', $number, $matches)) {
                $cleanNumber = (int) $matches[1];
            }
        }
        if ($cleanNumber < 0)
            $cleanNumber = 0;

        $stmt = $this->pdo->prepare("SELECT id FROM semesters WHERE batch_id = ? AND number = ?");
        $stmt->execute([$batchId, $cleanNumber]);
        $id = $stmt->fetchColumn();

        if ($id)
            return $id;

        $stmt = $this->pdo->prepare("INSERT INTO semesters (batch_id, number) VALUES (?, ?)");
        $stmt->execute([$batchId, $cleanNumber]);
        return $this->pdo->lastInsertId();
    }

    private function parseTimeSlot($slot)
    {
        // Expected format: "08:00 - 09:30"
        if (preg_match('/(\d{1,2}:\d{2})\s*-\s*(\d{1,2}:\d{2})/', $slot, $matches)) {
            $start = date("H:i:s", strtotime($matches[1]));
            $end = date("H:i:s", strtotime($matches[2]));
            return [$start, $end];
        }
        return [null, null];
    }
}
