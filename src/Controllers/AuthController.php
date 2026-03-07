<?php
// src/Controllers/AuthController.php

namespace App\Controllers;

use App\Models\User;

class AuthController
{
    private $userModel;
    private $pdo;
    private $logger;

    public function __construct($pdo)
    {
        $this->userModel = new User($pdo);
        $this->pdo = $pdo;
        $this->logger = new \App\Services\AuditLogger($pdo);
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $identifier = trim($_POST['identifier'] ?? ''); // Can be username or roll_number
            $password = $_POST['password'] ?? '';

            $user = $this->userModel->findByRollNumber($identifier);
            if (!$user) {
                $user = $this->userModel->findByUsername($identifier);
            }

            if ($user && password_verify($password, $user['password'])) {
                if ($user['is_approved'] == 0) {
                    $error = "Your account is awaiting admin approval. Please check back later.";
                    require '../src/Views/auth/login.php';
                    exit;
                }
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['batch_id'] = $user['batch_id']; // For CR/GR
                $_SESSION['section_id'] = $user['section_id']; // For CR

                $this->logger->log('LOGIN_SUCCESS', "User {$user['username']} logged in", $user['id']);

                // Redirect based on role
                switch ($user['role']) {
                    case 'admin':
                        header('Location: /admin/dashboard');
                        break;
                    case 'cr':
                        header('Location: /cr/dashboard');
                        break;
                    case 'gr':
                        header('Location: /gr/dashboard');
                        break;
                    case 'president':
                        $_SESSION['society_id'] = $user['society_id'];
                        header('Location: /society/dashboard');
                        break;
                    default:
                        header('Location: /');
                }
                exit;
            } else {
                $this->logger->log('LOGIN_FAILED', "Failed login attempt for identifier: $identifier");
                $error = "Invalid roll number/username or password";
                require '../src/Views/auth/login.php';
            }
        } else {
            $societies = $this->pdo->query("SELECT id, name FROM societies ORDER BY name")->fetchAll();
            require '../src/Views/auth/login.php';
        }
    }

    public function signup()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $roll_number = trim($_POST['roll_number'] ?? '');
            $email = $_POST['email'] ?? '';
            $phone_number = $_POST['phone_number'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            $role = $_POST['role'] ?? '';
            $batch_id = !empty($_POST['batch_id']) ? $_POST['batch_id'] : null;
            $section_id = !empty($_POST['section_id']) ? $_POST['section_id'] : null;
            $society_id = !empty($_POST['society_id']) ? $_POST['society_id'] : null;

            if (empty($username) || empty($roll_number) || empty($email) || empty($phone_number) || empty($password) || empty($role)) {
                $error = "Please fill in all required fields.";
            } else if ($password !== $confirm_password) {
                $error = "Passwords do not match.";
            } else if (strlen($password) < 8 || !preg_match("/[a-z]/i", $password) || !preg_match("/[0-9]/", $password)) {
                $error = "Password must be at least 8 characters long and contain both letters and numbers.";
            } else if ($this->userModel->findByRollNumber($roll_number)) {
                $error = "Roll number already registered.";
            } else if ($this->userModel->findByUsername($username)) {
                $error = "Username already exists.";
            } else {
                $success = $this->userModel->create($username, $roll_number, $email, $phone_number, $password, $role, $batch_id, $section_id, $society_id);
                if ($success) {
                    $this->logger->log('SIGNUP_SUCCESS', "User $username ($roll_number) registered as $role. Awaiting approval.");
                    $_SESSION['flash_message'] = "Account created successfully. Your account is awaiting admin approval.";
                    header('Location: /login');
                    exit;
                } else {
                    $this->logger->log('SIGNUP_FAILED', "Failed signup for $username");
                    $error = "Failed to create account. Please try again.";
                }
            }
        }
        
        // Fetch batches, sections, and societies for the form
        $batches = $this->pdo->query("SELECT id, name FROM batches ORDER BY name")->fetchAll();
        $sections = $this->pdo->query("SELECT id, name, batch_id FROM sections ORDER BY name")->fetchAll();
        $societies = $this->pdo->query("SELECT id, name FROM societies ORDER BY name")->fetchAll();

        require '../src/Views/auth/signup.php';
    }

    public function checkUniqueness()
    {
        $type = $_GET['type'] ?? '';
        $value = $_GET['value'] ?? '';
        
        $exists = false;
        if ($type === 'roll_number') {
            $exists = $this->userModel->findByRollNumber($value) !== false;
        } else if ($type === 'username') {
            $exists = $this->userModel->findByUsername($value) !== false;
        }
        
        header('Content-Type: application/json');
        echo json_encode(['exists' => $exists]);
        exit;
    }

    public function logout()
    {
        session_destroy();
        header('Location: /login');
        exit;
    }
}
