<?php
// src/Controllers/AdminController.php

namespace App\Controllers;

class AdminController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function dashboard()
    {
        // Fetch stats if needed
        require '../src/Views/admin/dashboard.php';
    }
}
