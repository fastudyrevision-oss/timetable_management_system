<?php
// src/Controllers/TeacherController.php

namespace App\Controllers;

use App\Models\Teacher;

class TeacherController
{
    private $pdo;
    private $teacherModel;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        $this->teacherModel = new Teacher($pdo);
    }

    public function index()
    {
        $teachers = $this->teacherModel->getAll();
        require '../src/Views/admin/teachers.php';
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $email = $_POST['email'];

            $stmt = $this->pdo->prepare("INSERT INTO teachers (name, email) VALUES (:name, :email)");
            if ($stmt->execute(['name' => $name, 'email' => $email])) {
                $_SESSION['flash_message'] = "Teacher added successfully.";
            } else {
                $_SESSION['error_message'] = "Failed to add teacher.";
            }
            header('Location: /admin/teachers');
            exit;
        }
    }
}
