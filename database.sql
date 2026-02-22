CREATE DATABASE IF NOT EXISTS timetable_db;
USE timetable_db;

-- 1. Users Table (Admin, CR, GR)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'cr', 'gr') NOT NULL,
    batch_id INT NULL,
    section_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Batches (e.g., IT-2022)
CREATE TABLE IF NOT EXISTS batches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    department_id INT DEFAULT 1 -- MVP: Single Dept
);

-- 3. Sections (Regular, Self Support)
CREATE TABLE IF NOT EXISTS sections (
    id INT AUTO_INCREMENT PRIMARY KEY,
    batch_id INT NOT NULL,
    name VARCHAR(50) NOT NULL, -- 'A', 'B', 'Regular', 'Self'
    type ENUM('regular', 'self_support') NOT NULL,
    FOREIGN KEY (batch_id) REFERENCES batches(id) ON DELETE CASCADE
);

-- 4. Semesters (1-8)
CREATE TABLE IF NOT EXISTS semesters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    number INT NOT NULL, -- 1 to 8
    batch_id INT NOT NULL,
    active BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (batch_id) REFERENCES batches(id) ON DELETE CASCADE
);

-- 5. Subjects
CREATE TABLE IF NOT EXISTS subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    code VARCHAR(20) NULL UNIQUE,
    semester_id INT NOT NULL,
    FOREIGN KEY (semester_id) REFERENCES semesters(id) ON DELETE CASCADE
);

-- 6. Teachers
CREATE TABLE IF NOT EXISTS teachers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NULL,
    availability_json JSON NULL -- Stores available slots: {"monday":["9-10", "10-11"], ...}
);

-- 7. Rooms
CREATE TABLE IF NOT EXISTS rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE, -- 'Room 101', 'Lab 2'
    capacity INT DEFAULT 60,
    type ENUM('classroom', 'lab') DEFAULT 'classroom',
    availability_json JSON NULL
);

-- 8. Timetable (The Core)
CREATE TABLE IF NOT EXISTS timetable (
    id INT AUTO_INCREMENT PRIMARY KEY,
    batch_id INT NOT NULL,
    section_id INT NOT NULL,
    semester_id INT NOT NULL,
    subject_id INT NOT NULL,
    teacher_id INT NULL,
    room_id INT NULL,
    day VARCHAR(20) NOT NULL, -- Monday, Tuesday...
    time_slot VARCHAR(20) NOT NULL, -- '09:00-10:00' (Keep for display)
    start_time TIME NULL, -- For overlap checks
    end_time TIME NULL, -- For overlap checks
    status ENUM('scheduled', 'pending', 'conflict', 'cancelled') DEFAULT 'scheduled',
    FOREIGN KEY (batch_id) REFERENCES batches(id),
    FOREIGN KEY (section_id) REFERENCES sections(id),
    FOREIGN KEY (semester_id) REFERENCES semesters(id),
    FOREIGN KEY (subject_id) REFERENCES subjects(id),
    FOREIGN KEY (teacher_id) REFERENCES teachers(id),
    FOREIGN KEY (room_id) REFERENCES rooms(id)
);

-- Insert Default Admin
-- password: admin (hashed would be better in prod, using plain text or simple hash for MVP if needed, but let's stick to standard PHP password_hash)
-- For MVP setup, we'll insert a placeholder and update it via a script or let the user register.
INSERT INTO users (username, password, role) VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'); -- password: password
