<?php
// src/Models/User.php

namespace App\Models;

use PDO;

class User
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findByRollNumber($roll_number)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE roll_number = :roll_number LIMIT 1");
        $stmt->execute(['roll_number' => $roll_number]);
        return $stmt->fetch();
    }

    public function findByUsername($username)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
        $stmt->execute(['username' => $username]);
        return $stmt->fetch();
    }

    public function create($username, $roll_number, $email, $phone_number, $password, $role, $batch_id = null, $section_id = null, $society_id = null)
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO users (username, roll_number, email, phone_number, password, role, is_approved, batch_id, section_id, society_id) VALUES (:username, :roll_number, :email, :phone_number, :password, :role, :is_approved, :batch_id, :section_id, :society_id)");
        return $stmt->execute([
            'username' => $username,
            'roll_number' => $roll_number,
            'email' => $email,
            'phone_number' => $phone_number,
            'password' => $hash,
            'role' => $role,
            'is_approved' => 0, // Default to unapproved
            'batch_id' => $batch_id,
            'section_id' => $section_id,
            'society_id' => $society_id
        ]);
    }

    public function approve($id)
    {
        $stmt = $this->pdo->prepare("UPDATE users SET is_approved = 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function updatePassword($id, $new_password)
    {
        $hash = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        return $stmt->execute([$hash, $id]);
    }

    public function getAll()
    {
        $stmt = $this->pdo->query("SELECT * FROM users");
        return $stmt->fetchAll();
    }
}
