<?php
// src/Controllers/AuthController.php

namespace App\Controllers;

use App\Models\User;

class AuthController
{
    private $userModel;

    public function __construct($pdo)
    {
        $this->userModel = new User($pdo);
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $user = $this->userModel->findByUsername($username);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['batch_id'] = $user['batch_id']; // For CR/GR
                $_SESSION['section_id'] = $user['section_id']; // For CR

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
                    default:
                        header('Location: /');
                }
                exit;
            } else {
                $error = "Invalid username or password";
                require '../src/Views/auth/login.php';
            }
        } else {
            require '../src/Views/auth/login.php';
        }
    }

    public function logout()
    {
        session_destroy();
        header('Location: /login');
        exit;
    }
}
