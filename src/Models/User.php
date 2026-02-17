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

    public function findByUsername($username)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
        $stmt->execute(['username' => $username]);
        return $stmt->fetch();
    }

    public function create($username, $password, $role, $batch_id = null, $section_id = null)
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO users (username, password, role, batch_id, section_id) VALUES (:username, :password, :role, :batch_id, :section_id)");
        return $stmt->execute([
            'username' => $username,
            'password' => $hash,
            'role' => $role,
            'batch_id' => $batch_id,
            'section_id' => $section_id
        ]);
    }

    public function getAll()
    {
        $stmt = $this->pdo->query("SELECT * FROM users");
        return $stmt->fetchAll();
    }
}
