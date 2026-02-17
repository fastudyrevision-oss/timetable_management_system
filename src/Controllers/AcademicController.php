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
}
