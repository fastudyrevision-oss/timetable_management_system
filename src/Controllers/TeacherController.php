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

    public function edit($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM teachers WHERE id = ?");
        $stmt->execute([$id]);
        $teacher = $stmt->fetch();

        if (!$teacher) {
            die("Teacher not found");
        }

        require '../src/Views/admin/teachers/edit.php';
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $email = $_POST['email'];

            $stmt = $this->pdo->prepare("UPDATE teachers SET name = ?, email = ? WHERE id = ?");
            if ($stmt->execute([$name, $email, $id])) {
                $_SESSION['flash_message'] = "Teacher updated successfully.";
            } else {
                $_SESSION['error_message'] = "Failed to update teacher.";
            }
            header('Location: /admin/teachers');
            exit;
        }
    }
}
