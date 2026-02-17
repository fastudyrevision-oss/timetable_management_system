<?php
// src/Models/Teacher.php

namespace App\Models;

use PDO;

class Teacher
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $stmt = $this->pdo->query("SELECT * FROM teachers");
        return $stmt->fetchAll();
    }
}
