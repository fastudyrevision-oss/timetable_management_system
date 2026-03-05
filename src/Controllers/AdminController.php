<?php
// src/Controllers/AdminController.php
namespace App\Controllers;

use App\Models\User;
use App\Services\AuditLogger;

class AdminController {
    private $pdo;
    private $userModel;
    private $logger;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->userModel = new User($pdo);
        $this->logger = new AuditLogger($pdo);
    }

    public function manageUsers() {
        $this->checkAdmin();
        $users = $this->userModel->getAll();
        require '../src/Views/admin/users/index.php';
    }

    public function auditLogs() {
        $this->checkAdmin();
        $logs = $this->logger->getLogs(200);
        require '../src/Views/admin/logs/index.php';
    }

    public function editUser($id) {
        $this->checkAdmin();
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch();
        
        $batches = $this->pdo->query("SELECT id, name FROM batches ORDER BY name")->fetchAll();
        $sections = $this->pdo->query("SELECT id, name, batch_id FROM sections ORDER BY name")->fetchAll();
        $societies = $this->pdo->query("SELECT id, name FROM societies ORDER BY name")->fetchAll();

        require '../src/Views/admin/users/edit.php';
    }

    public function updateUser() {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $username = $_POST['username'];
            $roll_number = $_POST['roll_number'];
            $email = $_POST['email'] ?? null;
            $phone_number = $_POST['phone_number'] ?? null;
            $role = $_POST['role'];
            $password = !empty($_POST['password']) ? $_POST['password'] : null;
            $batch_id = !empty($_POST['batch_id']) ? $_POST['batch_id'] : null;
            $section_id = !empty($_POST['section_id']) ? $_POST['section_id'] : null;
            $society_id = !empty($_POST['society_id']) ? $_POST['society_id'] : null;

            $profile_picture = $this->handleUpload('profile_picture', 'users');

            $sql = "UPDATE users SET username = ?, roll_number = ?, email = ?, phone_number = ?, role = ?, batch_id = ?, section_id = ?, society_id = ?";
            $params = [$username, $roll_number, $email, $phone_number, $role, $batch_id, $section_id, $society_id];

            if ($profile_picture) {
                $sql .= ", profile_picture = ?";
                $params[] = $profile_picture;
            }

            if ($password) {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $sql .= ", password = ?";
                $params[] = $hash;
            }

            $sql .= " WHERE id = ?";
            $params[] = $id;

            $stmt = $this->pdo->prepare($sql);
            $success = $stmt->execute($params);

            if ($success) {
                $this->logger->log('USER_UPDATE', "Updated user ID $id: $username", $_SESSION['user_id']);
                header("Location: /admin/users");
                exit;
            }
        }
    }

    public function approveUser($id) {
        $this->checkAdmin();
        if ($this->userModel->approve($id)) {
            $this->logger->log('USER_APPROVE', "Approved user ID $id", $_SESSION['user_id']);
            header("Location: /admin/users");
            exit;
        }
    }

    public function deleteUser($id) {
        $this->checkAdmin();
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        if ($stmt->execute([$id])) {
            $this->logger->log('USER_DELETE', "Deleted user ID $id", $_SESSION['user_id']);
            header("Location: /admin/users");
            exit;
        }
    }

    public function rejectUser($id) {
        $this->checkAdmin();
        $stmt = $this->pdo->prepare("SELECT username FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch();
        
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        if ($stmt->execute([$id])) {
            $this->logger->log('USER_REJECT', "Rejected and deleted user request ID $id: " . ($user['username'] ?? 'Unknown'), $_SESSION['user_id']);
            header("Location: /admin/users");
            exit;
        }
    }

    private function handleUpload($field, $folder) {
        if (isset($_FILES[$field]) && $_FILES[$field]['error'] === UPLOAD_ERR_OK) {
            $tmp_name = $_FILES[$field]['tmp_name'];
            $name = time() . '_' . basename($_FILES[$field]['name']);
            $upload_dir = __DIR__ . '/../../public/uploads/' . $folder;
            
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $dest = $upload_dir . '/' . $name;
            if (move_uploaded_file($tmp_name, $dest)) {
                return '/uploads/' . $folder . '/' . $name;
            }
        }
        return null;
    }

    private function checkAdmin() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: /login");
            exit;
        }
    }
}
