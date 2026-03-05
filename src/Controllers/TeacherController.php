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

    private function handlePictureUpload($file)
    {
        if (isset($file) && $file['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/assets/uploads/faculty/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (in_array($fileExtension, $allowedExtensions)) {
                $fileName = uniqid() . '.' . $fileExtension;
                $destination = $uploadDir . $fileName;
                if (move_uploaded_file($file['tmp_name'], $destination)) {
                    return '/assets/uploads/faculty/' . $fileName;
                }
            }
        }
        return null;
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $post = $_POST['post'] ?? '';
            $qualification = $_POST['qualification'] ?? '';
            $research_interest = $_POST['research_interest'] ?? '';
            
            $picturePath = $this->handlePictureUpload($_FILES['picture'] ?? null);

            $stmt = $this->pdo->prepare("INSERT INTO teachers (name, email, post, picture, qualification, research_interest, is_faculty) VALUES (:name, :email, :post, :picture, :qualification, :research_interest, 1)");
            if ($stmt->execute([
                'name' => $name, 
                'email' => $email,
                'post' => $post,
                'picture' => $picturePath,
                'qualification' => $qualification,
                'research_interest' => $research_interest
            ])) {
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
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $post = $_POST['post'] ?? '';
            $qualification = $_POST['qualification'] ?? '';
            $research_interest = $_POST['research_interest'] ?? '';

            $picturePath = $this->handlePictureUpload($_FILES['picture'] ?? null);

            if ($picturePath) {
                $stmt = $this->pdo->prepare("UPDATE teachers SET name = ?, email = ?, post = ?, picture = ?, qualification = ?, research_interest = ?, is_faculty = 1 WHERE id = ?");
                $success = $stmt->execute([$name, $email, $post, $picturePath, $qualification, $research_interest, $id]);
            } else {
                $stmt = $this->pdo->prepare("UPDATE teachers SET name = ?, email = ?, post = ?, qualification = ?, research_interest = ?, is_faculty = 1 WHERE id = ?");
                $success = $stmt->execute([$name, $email, $post, $qualification, $research_interest, $id]);
            }


            if ($success) {
                $_SESSION['flash_message'] = "Teacher updated successfully.";
            } else {
                $_SESSION['error_message'] = "Failed to update teacher.";
            }
            header('Location: /admin/teachers');
            exit;
        }
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("UPDATE teachers SET is_faculty = 0 WHERE id = ?");
        if ($stmt->execute([$id])) {
            $_SESSION['flash_message'] = "Faculty member removed successfully.";
        } else {
            $_SESSION['error_message'] = "Failed to remove faculty member.";
        }
        header('Location: /admin/teachers');
        exit;
    }

    public function check()
    {
        header('Content-Type: application/json');
        $name = $_GET['name'] ?? '';
        
        if (trim($name) === '') {
            echo json_encode(['exists' => false]);
            exit;
        }

        $stmt = $this->pdo->prepare("SELECT id, is_faculty FROM teachers WHERE name = ?");
        $stmt->execute([$name]);
        $teacher = $stmt->fetch();

        if ($teacher) {
            echo json_encode(['exists' => true, 'id' => $teacher['id'], 'is_faculty' => (bool)$teacher['is_faculty']]);
        } else {
            echo json_encode(['exists' => false]);
        }
        exit;
    }
}
