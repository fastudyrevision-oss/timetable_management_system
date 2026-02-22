<?php
// src/Controllers/AcademicController.php

namespace App\Controllers;

class AcademicController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function index()
    {
        $batches = $this->pdo->query("SELECT * FROM batches")->fetchAll();
        $sections = $this->pdo->query("SELECT s.*, b.name as batch_name FROM sections s JOIN batches b ON s.batch_id = b.id")->fetchAll();
        $semesters = $this->pdo->query("SELECT s.*, b.name as batch_name FROM semesters s JOIN batches b ON s.batch_id = b.id")->fetchAll();
        $subjects = $this->pdo->query("SELECT sub.*, sem.number as sem_number, b.name as batch_name FROM subjects sub JOIN semesters sem ON sub.semester_id = sem.id JOIN batches b ON sem.batch_id = b.id")->fetchAll();

        require '../src/Views/admin/academic.php';
    }

    public function createBatch()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $stmt = $this->pdo->prepare("INSERT INTO batches (name) VALUES (:name)");
            if ($stmt->execute(['name' => $name])) {
                $_SESSION['flash_message'] = "Batch added successfully.";
            }
            header('Location: /admin/academic');
            exit;
        }
    }

    public function createSection()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $batch_id = $_POST['batch_id'];
            $name = $_POST['name'];
            $type = $_POST['type'];
            $stmt = $this->pdo->prepare("INSERT INTO sections (batch_id, name, type) VALUES (:batch_id, :name, :type)");
            if ($stmt->execute(['batch_id' => $batch_id, 'name' => $name, 'type' => $type])) {
                $_SESSION['flash_message'] = "Section added successfully.";
            }
            header('Location: /admin/academic');
            exit;
        }
    }

    public function createSemester()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $batch_id = $_POST['batch_id'];
            $number = $_POST['number'];
            $stmt = $this->pdo->prepare("INSERT INTO semesters (batch_id, number) VALUES (:batch_id, :number)");
            if ($stmt->execute(['batch_id' => $batch_id, 'number' => $number])) {
                $_SESSION['flash_message'] = "Semester added successfully.";
            }
            header('Location: /admin/academic');
            exit;
        }
    }

    public function createSubject()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $semester_id = $_POST['semester_id'];
            $name = $_POST['name'];
            $code = $_POST['code'];
            $stmt = $this->pdo->prepare("INSERT INTO subjects (semester_id, name, code) VALUES (:semester_id, :name, :code)");
            if ($stmt->execute(['semester_id' => $semester_id, 'name' => $name, 'code' => $code])) {
                $_SESSION['flash_message'] = "Subject added successfully.";
            }
            header('Location: /admin/academic');
            exit;
        }
    }

    public function editSubject($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM subjects WHERE id = ?");
        $stmt->execute([$id]);
        $subject = $stmt->fetch();

        if (!$subject) {
            die("Subject not found");
        }

        require '../src/Views/admin/academic/edit_subject.php';
    }

    public function updateSubject()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $code = $_POST['code'];

            $stmt = $this->pdo->prepare("UPDATE subjects SET name = ?, code = ? WHERE id = ?");
            if ($stmt->execute([$name, $code, $id])) {
                $_SESSION['flash_message'] = "Subject updated successfully.";
            } else {
                $_SESSION['error_message'] = "Failed to update subject.";
            }
            header('Location: /admin/academic');
            exit;
        }
    }
    public function mergeDuplicateSubjects()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->pdo->beginTransaction();
                
                // 1. Find duplicates (Same Code only)
                // We want groups having count > 1
                $sql = "SELECT code, GROUP_CONCAT(id ORDER BY id ASC) as ids 
                        FROM subjects 
                        WHERE code IS NOT NULL AND code != ''
                        GROUP BY code 
                        HAVING COUNT(id) > 1";
                $duplicates = $this->pdo->query($sql)->fetchAll();

                $mergedCount = 0;

                foreach ($duplicates as $group) {
                    $ids = explode(',', $group['ids']);
                    $masterId = $ids[0]; // Keep the first created one (lowest ID)
                    $idsToDelete = array_slice($ids, 1);

                    if (empty($idsToDelete)) continue;

                    // 2. Update Timetable to point to Master ID
                    // Using implode strictly for safe IDs (they come from DB INT pk)
                    $placeholders = implode(',', array_fill(0, count($idsToDelete), '?'));
                    
                    // Update valid timetable entries
                    $updateSql = "UPDATE timetable SET subject_id = ? WHERE subject_id IN ($placeholders)";
                    $params = array_merge([$masterId], $idsToDelete);
                    $stmtUpdate = $this->pdo->prepare($updateSql);
                    $stmtUpdate->execute($params);

                    // 3. Delete duplicates
                    $deleteSql = "DELETE FROM subjects WHERE id IN ($placeholders)";
                    $stmtDelete = $this->pdo->prepare($deleteSql);
                    $stmtDelete->execute($idsToDelete);

                    $mergedCount += count($idsToDelete);
                }

                $this->pdo->commit();
                $_SESSION['flash_message'] = "Merged $mergedCount duplicate subjects.";

            } catch (\Exception $e) {
                $this->pdo->rollBack();
                $_SESSION['error_message'] = "Merge failed: " . $e->getMessage();
            }

            header('Location: /admin/academic');
            exit;
        }
    }

    public function deleteBatch($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->pdo->beginTransaction();

                // 1. Delete associated Timetable entries
                $stmt = $this->pdo->prepare("DELETE FROM timetable WHERE batch_id = ?");
                $stmt->execute([$id]);

                // 2. Delete Batch (Cascade will handle sections, semesters, subjects if set, 
                //    but based on schema, sections/semesters have ON DELETE CASCADE so that's fine.
                //    Subjects depend on semesters with CASCADE, so that's fine too.)
                $stmt = $this->pdo->prepare("DELETE FROM batches WHERE id = ?");
                $stmt->execute([$id]);

                $this->pdo->commit();
                $_SESSION['flash_message'] = "Batch and all related data deleted successfully.";

            } catch (\Exception $e) {
                $this->pdo->rollBack();
                $_SESSION['error_message'] = "Failed to delete batch: " . $e->getMessage();
            }

            header('Location: /admin/academic');
            exit;
        }
    }
}
