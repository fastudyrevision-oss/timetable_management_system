<?php
// src/Controllers/SocietyController.php
namespace App\Controllers;

use App\Models\Society;

class SocietyController {
    private $pdo;
    private $societyModel;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->societyModel = new Society($pdo);
    }

    public function publicIndex() {
        $societies = $this->societyModel->getAll();
        require '../src/Views/public/societies.php';
    }

    public function publicView($id) {
        $society = $this->societyModel->getById($id);
        if (!$society) {
            header("Location: /societies");
            exit;
        }
        $members = $this->societyModel->getMembers($id);
        $events = $this->societyModel->getEvents($id);
        $news = $this->societyModel->getNews($id);
        require '../src/Views/public/society_detail.php';
    }

    public function dashboard() {
        $this->checkPresident();
        $society_id = $_SESSION['society_id'];
        $society = $this->societyModel->getById($society_id);
        $members = $this->societyModel->getMembers($society_id);
        $events = $this->societyModel->getEvents($society_id);
        $news = $this->societyModel->getNews($society_id);
        require '../src/Views/admin/societies/dashboard.php';
    }

    public function addMember() {
        $this->checkPresident();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'society_id' => $_SESSION['society_id'],
                'name' => $_POST['name'],
                'designation' => $_POST['designation'],
                'picture_path' => $this->handleUpload('member_pic', 'members')
            ];
            $this->societyModel->addMember($data);
            header("Location: /society/dashboard");
            exit;
        }
    }

    public function editMember($id) {
        $this->checkPresident();
        $member = $this->societyModel->getMemberById($id);
        if ($member && $member['society_id'] == $_SESSION['society_id']) {
            header('Content-Type: application/json');
            echo json_encode($member);
            exit;
        }
        http_response_code(403);
        echo json_encode(['error' => 'Unauthorized']);
    }

    public function updateMember() {
        $this->checkPresident();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $member = $this->societyModel->getMemberById($id);
            if ($member && $member['society_id'] == $_SESSION['society_id']) {
                $data = [
                    'name' => $_POST['name'],
                    'designation' => $_POST['designation'],
                    'picture_path' => $this->handleUpload('member_pic', 'members')
                ];
                $this->societyModel->updateMember($id, $data);
            }
            header("Location: /society/dashboard");
            exit;
        }
    }

    public function addEvent() {
        $this->checkPresident();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'society_id' => $_SESSION['society_id'],
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'event_date' => $_POST['event_date'],
                'poster_path' => $this->handleUpload('poster', 'events')
            ];
            $this->societyModel->addEvent($data);
            header("Location: /society/dashboard");
            exit;
        }
    }

    public function addNews() {
        $this->checkPresident();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'society_id' => $_SESSION['society_id'],
                'title' => $_POST['title'],
                'content' => $_POST['content']
            ];
            $this->societyModel->addNews($data);
            header("Location: /society/dashboard");
            exit;
        }
    }

    public function deleteMember($id) {
        $this->checkPresident();
        $member = $this->societyModel->getMemberById($id);
        if ($member && $member['society_id'] == $_SESSION['society_id']) {
            $this->societyModel->deleteMember($id);
        }
        header("Location: /society/dashboard");
        exit;
    }

    private function checkPresident() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'president' || !isset($_SESSION['society_id'])) {
            header("Location: /login");
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

    public function updateSociety() {
        $this->checkPresident();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $society_id = $_SESSION['society_id'];
            $data = [
                'name' => $_POST['name'],
                'description' => $_POST['description']
            ];

            // Handle Cropped Logo (Base64)
            if (!empty($_POST['cropped_logo'])) {
                $base64_image = $_POST['cropped_logo'];
                $image_parts = explode(";base64,", $base64_image);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);
                
                $filename = 'logo_' . time() . '.' . $image_type;
                $upload_dir = __DIR__ . '/../../public/uploads/societies';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                
                file_put_contents($upload_dir . '/' . $filename, $image_base64);
                $data['logo_path'] = '/uploads/societies/' . $filename;
            }

            $this->societyModel->update($society_id, $data);
            header("Location: /society/dashboard");
            exit;
        }
    }
}
