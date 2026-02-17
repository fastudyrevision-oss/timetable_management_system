<?php
// src/Controllers/RoomController.php

namespace App\Controllers;

use App\Models\Room;

class RoomController
{
    private $pdo;
    private $roomModel;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        $this->roomModel = new Room($pdo);
    }

    public function index()
    {
        $rooms = $this->roomModel->getAll();
        require '../src/Views/admin/rooms.php';
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $capacity = $_POST['capacity'];
            $type = $_POST['type'];

            $stmt = $this->pdo->prepare("INSERT INTO rooms (name, capacity, type) VALUES (:name, :capacity, :type)");
            if ($stmt->execute(['name' => $name, 'capacity' => $capacity, 'type' => $type])) {
                $_SESSION['flash_message'] = "Room added successfully.";
            } else {
                $_SESSION['error_message'] = "Failed to add room.";
            }
            header('Location: /admin/rooms');
            exit;
        }
    }
}
