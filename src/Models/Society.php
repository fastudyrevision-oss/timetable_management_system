<?php
// src/Models/Society.php
namespace App\Models;

use PDO;

class Society {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("
            SELECT s.*, 
                   COALESCE(
                       (SELECT username FROM users WHERE society_id = s.id AND role = 'president' LIMIT 1),
                       (SELECT name FROM society_members WHERE society_id = s.id AND is_president = 1 LIMIT 1)
                   ) as president_name,
                   COALESCE(
                       (SELECT profile_picture FROM users WHERE society_id = s.id AND role = 'president' LIMIT 1),
                       (SELECT picture_path FROM society_members WHERE society_id = s.id AND is_president = 1 LIMIT 1)
                   ) as president_picture 
            FROM societies s
        ");
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("
            SELECT s.*, 
                   COALESCE(
                       (SELECT username FROM users WHERE society_id = s.id AND role = 'president' LIMIT 1),
                       (SELECT name FROM society_members WHERE society_id = s.id AND is_president = 1 LIMIT 1)
                   ) as president_name,
                   COALESCE(
                       (SELECT profile_picture FROM users WHERE society_id = s.id AND role = 'president' LIMIT 1),
                       (SELECT picture_path FROM society_members WHERE society_id = s.id AND is_president = 1 LIMIT 1)
                   ) as president_picture 
            FROM societies s 
            WHERE s.id = ?
            LIMIT 1
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getMembers($society_id) {
        $stmt = $this->pdo->prepare("
            SELECT m.*, 
                   COALESCE(u.profile_picture, m.picture_path) as picture_path
            FROM society_members m
            LEFT JOIN users u ON m.society_id = u.society_id AND u.role = 'president' AND m.is_president = 1
            WHERE m.society_id = ? 
            ORDER BY m.is_president DESC, m.name ASC
        ");
        $stmt->execute([$society_id]);
        return $stmt->fetchAll();
    }

    public function getEvents($society_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM society_events WHERE society_id = ? ORDER BY event_date DESC");
        $stmt->execute([$society_id]);
        return $stmt->fetchAll();
    }

    public function getNews($society_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM society_news WHERE society_id = ? ORDER BY created_at DESC");
        $stmt->execute([$society_id]);
        return $stmt->fetchAll();
    }

    public function addMember($data) {
        $stmt = $this->pdo->prepare("INSERT INTO society_members (society_id, name, designation, picture_path, is_president) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['society_id'],
            $data['name'],
            $data['designation'],
            $data['picture_path'] ?? null,
            $data['is_president'] ?? 0
        ]);
    }

    public function getMemberById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM society_members WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function updateMember($id, $data) {
        if (!empty($data['picture_path'])) {
            $stmt = $this->pdo->prepare("UPDATE society_members SET name = ?, designation = ?, picture_path = ? WHERE id = ?");
            return $stmt->execute([$data['name'], $data['designation'], $data['picture_path'], $id]);
        } else {
            $stmt = $this->pdo->prepare("UPDATE society_members SET name = ?, designation = ? WHERE id = ?");
            return $stmt->execute([$data['name'], $data['designation'], $id]);
        }
    }

    public function deleteMember($id) {
        $stmt = $this->pdo->prepare("DELETE FROM society_members WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function addEvent($data) {
        $stmt = $this->pdo->prepare("INSERT INTO society_events (society_id, title, description, event_date, poster_path) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['society_id'],
            $data['title'],
            $data['description'],
            $data['event_date'],
            $data['poster_path'] ?? null
        ]);
    }

    public function addNews($data) {
        $stmt = $this->pdo->prepare("INSERT INTO society_news (society_id, title, content) VALUES (?, ?, ?)");
        return $stmt->execute([
            $data['society_id'],
            $data['title'],
            $data['content']
        ]);
    }

    public function update($id, $data) {
        $fields = [];
        $params = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = ?";
            $params[] = $value;
        }
        $params[] = $id;
        $sql = "UPDATE societies SET " . implode(", ", $fields) . " WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }
}
