<?php
// src/Models/Timetable.php

namespace App\Models;

use PDO;

class Timetable
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $stmt = $this->pdo->query("SELECT * FROM timetable");
        return $stmt->fetchAll();
    }

    // Todo: Add create, update, delete methods
}
