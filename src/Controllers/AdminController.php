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

    public function manageLeadership() {
        $this->checkAdmin();
        $leaders = $this->pdo->query("SELECT * FROM student_leadership")->fetchAll(\PDO::FETCH_ASSOC);
        require '../src/Views/admin/leadership.php';
    }

    public function updateLeadership() {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $role = $_POST['role'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $email = $_POST['email'];
            $linkedin_url = $_POST['linkedin_url'] ?? null;

            $picture = $this->handleUpload('picture', 'leadership');

            $sql = "UPDATE student_leadership SET name = ?, description = ?, email = ?, linkedin_url = ?";
            $params = [$name, $description, $email, $linkedin_url];

            if ($picture) {
                $sql .= ", picture = ?";
                $params[] = $picture;
            }

            $sql .= " WHERE role = ?";
            $params[] = $role;

            $stmt = $this->pdo->prepare($sql);
            if ($stmt->execute($params)) {
                $this->logger->log('LEADER_UPDATE', "Updated student leader: $role ($name)", $_SESSION['user_id']);
                header("Location: /admin/leadership?success=1");
                exit;
            }
        }
    }

    public function manageGallery() {
        $this->checkAdmin();
        $items = $this->pdo->query("SELECT * FROM gallery ORDER BY display_order ASC, created_at DESC")->fetchAll(\PDO::FETCH_ASSOC);
        require '../src/Views/admin/gallery/index.php';
    }

    public function addGalleryItem() {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'];
            $media_type = $_POST['media_type'];
            $category = $_POST['category'];
            $display_order = (int)($_POST['display_order'] ?? 0);

            $files = $_FILES['media_files'];
            $success_count = 0;

            for ($i = 0; $i < count($files['name']); $i++) {
                if ($files['error'][$i] === UPLOAD_ERR_OK) {
                    $tmp_name = $files['tmp_name'][$i];
                    $name = time() . '_' . $i . '_' . basename($files['name'][$i]);
                    $folder = ($media_type === 'video') ? 'videos' : 'images';
                    $upload_dir = __DIR__ . '/../../public/uploads/gallery/' . $folder;

                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0777, true);
                    }

                    $dest = $upload_dir . '/' . $name;
                    if (move_uploaded_file($tmp_name, $dest)) {
                        $media_path = '/uploads/gallery/' . $folder . '/' . $name;
                        $stmt = $this->pdo->prepare("INSERT INTO gallery (title, media_path, media_type, category, display_order) VALUES (?, ?, ?, ?, ?)");
                        $stmt->execute([$title, $media_path, $media_type, $category, $display_order]);
                        $success_count++;
                    }
                }
            }

            if ($success_count > 0) {
                $this->logger->log('GALLERY_ADD', "Added $success_count gallery items for event: $title", $_SESSION['user_id']);
                header("Location: /admin/gallery?success=1");
            } else {
                header("Location: /admin/gallery?error=upload_failed");
            }
            exit;
        }
    }

    public function updateGalleryOrder() {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['orders'])) {
            foreach ($_POST['orders'] as $id => $order) {
                $stmt = $this->pdo->prepare("UPDATE gallery SET display_order = ? WHERE id = ?");
                $stmt->execute([(int)$order, (int)$id]);
            }
            $this->logger->log('GALLERY_ORDER_UPDATE', "Updated gallery display orders", $_SESSION['user_id']);
            header("Location: /admin/gallery?ordered=1");
            exit;
        }
    }

    public function updateGalleryItem() {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $title = $_POST['title'];
            $category = $_POST['category'];
            $media_type = $_POST['media_type'];
            $display_order = (int)$_POST['display_order'];

            $stmt = $this->pdo->prepare("UPDATE gallery SET title = ?, category = ?, media_type = ?, display_order = ? WHERE id = ?");
            if ($stmt->execute([$title, $category, $media_type, $display_order, $id])) {
                $this->logger->log('GALLERY_UPDATE', "Updated gallery item ID $id: $title", $_SESSION['user_id']);
                header("Location: /admin/gallery?updated=1");
                exit;
            }
        }
    }

    public function deleteGalleryItem($id) {
        $this->checkAdmin();
        $stmt = $this->pdo->prepare("SELECT media_path FROM gallery WHERE id = ?");
        $stmt->execute([$id]);
        $item = $stmt->fetch();

        if ($item) {
            // Delete file from server
            $file_path = __DIR__ . '/../../public' . $item['media_path'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }

            $stmt = $this->pdo->prepare("DELETE FROM gallery WHERE id = ?");
            $stmt->execute([$id]);
            $this->logger->log('GALLERY_DELETE', "Deleted gallery item ID $id", $_SESSION['user_id']);
            header("Location: /admin/gallery?deleted=1");
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
