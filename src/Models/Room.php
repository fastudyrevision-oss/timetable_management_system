<?php
// src/Models/Room.php

namespace App\Models;

use PDO;

class Room
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $stmt = $this->pdo->query("SELECT * FROM rooms");
        return $stmt->fetchAll();
    }
}
