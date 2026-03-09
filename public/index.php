<?php
// public/index.php

session_start();

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/db.php';

use App\Controllers\AuthController;
use App\Controllers\TimetableController;
use App\Controllers\RoomController;
use App\Controllers\TeacherController;
use App\Controllers\AcademicController;
use App\Controllers\ArrangementController;
use App\Controllers\ExportController;
use App\Controllers\SocietyController;
use App\Controllers\AdminController;

// Simple Router
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Dependency Injection
$authController = new AuthController($pdo);
$timetableController = new TimetableController($pdo);
$roomController = new RoomController($pdo);
$teacherController = new TeacherController($pdo);
$academicController = new AcademicController($pdo);
$arrangementController = new ArrangementController($pdo);
$exportController = new ExportController($pdo);
$societyController = new SocietyController($pdo);
$adminController = new AdminController($pdo);

// Routing Logic
switch ($uri) {
    case '/':
        require '../src/Views/public/home.php';
        break;
    case '/academic-calendar':
        require '../src/Views/public/academic_calendar.php';
        break;
    case '/cgpa-calculator':
        require '../src/Views/public/cgpa_calculator.php';
        break;
    case '/timetable':
        $timetableController->publicIndex();
        break;
    case '/faculty':
        $timetableController->publicFaculty();
        break;
    case '/societies':
        $societyController->publicIndex();
        break;
    case '/gallery':
        require '../src/Views/public/gallery.php';
        break;
    case (preg_match('/^\/society\/(\d+)$/', $uri, $matches) ? true : false):
        $societyController->publicView($matches[1]);
        break;
    case '/login':
        $authController->login();
        break;
    case '/signup':
        $authController->signup();
        break;
    case '/auth/check-uniqueness':
        $authController->checkUniqueness();
        break;
    case '/logout':
        $authController->logout();
        break;

    // Admin Routes
    case '/admin/dashboard':
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: /login');
            exit;
        }
        require '../src/Views/admin/dashboard.php';
        break;

    case '/admin/timetable':
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: /login');
            exit;
        }
        $timetableController->index();
        break;

    case '/admin/timetable/upload':
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: /login');
            exit;
        }
        $timetableController->upload();
        break;

    case '/admin/timetable/save':
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: /login');
            exit;
        }
        $timetableController->save();
        break;

    case '/admin/timetable/clear':
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: /login');
            exit;
        }
        $timetableController->clear();
        break;

    // Room Routes
    case '/admin/rooms':
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: /login');
            exit;
        }
        $roomController->index();
        break;
    case '/admin/rooms/create':
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: /login');
            exit;
        }
        $roomController->create();
        break;

    // Teacher Routes
    case '/admin/teachers':
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: /login');
            exit;
        }
        $teacherController->index();
        break;
    case '/admin/teachers/create':
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: /login');
            exit;
        }
        $teacherController->create();
        break;
    case (preg_match('/^\/admin\/teachers\/edit\/(\d+)$/', $uri, $matches) ? true : false):
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: /login');
            exit;
        }
        $teacherController->edit($matches[1]);
        break;
    case '/admin/teachers/update':
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin')
            exit;
        $teacherController->update();
        break;
    case '/admin/teachers/check':
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('HTTP/1.1 401 Unauthorized');
            exit;
        }
        $teacherController->check();
        break;
    case (preg_match('/^\/admin\/teachers\/delete\/(\d+)$/', $uri, $matches) ? true : false):
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: /login');
            exit;
        }
        $teacherController->delete($matches[1]);
        break;

    // Academic Routes (Batches, Sections, Semesters, Subjects)
    case '/admin/academic':
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: /login');
            exit;
        }
        $academicController->index();
        break;
    case '/admin/academic/create-batch':
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin')
            exit;
        $academicController->createBatch();
        break;
    case (preg_match('/^\/admin\/academic\/batch\/delete\/(\d+)$/', $uri, $matches) ? true : false):
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin')
            exit;
        $academicController->deleteBatch($matches[1]);
        break;
    case '/admin/academic/create-section':
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin')
            exit;
        $academicController->createSection();
        break;
    case '/admin/academic/create-semester':
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin')
            exit;
        $academicController->createSemester();
        break;
    case '/admin/academic/create-subject':
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin')
            exit;
        $academicController->createSubject();
        break;
    case (preg_match('/^\/admin\/academic\/subject\/edit\/(\d+)$/', $uri, $matches) ? true : false):
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin')
            exit;
        $academicController->editSubject($matches[1]);
        break;
    case '/admin/academic/subject/update':
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin')
            exit;
        $academicController->updateSubject();
        break;
    case '/admin/academic/subject/merge':
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin')
            exit;
        $academicController->mergeDuplicateSubjects();
        break;

    // Admin User & Log Routes
    case '/admin/users':
        $adminController->manageUsers();
        break;
    case (preg_match('/^\/admin\/users\/edit\/(\d+)$/', $uri, $matches) ? true : false):
        $adminController->editUser($matches[1]);
        break;
    case '/admin/users/update':
        $adminController->updateUser();
        break;
    case (preg_match('/^\/admin\/users\/delete\/(\d+)$/', $uri, $matches) ? true : false):
        $adminController->deleteUser($matches[1]);
        break;
    case '/admin/user/approve':
        $adminController->approveUser($_GET['id']);
        break;
    case '/admin/user/reject':
        $adminController->rejectUser($_GET['id']);
        break;
    case '/admin/logs':
        $adminController->auditLogs();
        break;
    case '/admin/leadership':
        $adminController->manageLeadership();
        break;
    case '/admin/leadership/update':
        $adminController->updateLeadership();
        break;
    case '/admin/gallery':
        $adminController->manageGallery();
        break;
    case '/admin/gallery/add':
        $adminController->addGalleryItem();
        break;
    case '/admin/gallery/update-order':
        $adminController->updateGalleryOrder();
        break;
    case '/admin/gallery/update':
        $adminController->updateGalleryItem();
        break;
    case (preg_match('/^\/admin\/gallery\/delete\/(\d+)$/', $uri, $matches) ? true : false):
        $adminController->deleteGalleryItem($matches[1]);
        break;

    // Arrangement Routes
    case (preg_match('/^\/admin\/arrangement\/edit\/(\d+)$/', $uri, $matches) ? true : false):
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: /login');
            exit;
        }
        $arrangementController->editSlot($matches[1]);
        break;
    case '/admin/arrangement/update':
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin')
            exit;
        $arrangementController->updateSlot();
        break;
    case '/api/arrangement/swap':
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin')
            exit;
        $arrangementController->swapSlots();
        break;
    case '/api/arrangement/available-rooms':
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin')
            exit;
        $arrangementController->apiAvailableRooms();
        break;
    case '/api/arrangement/status':
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin')
            exit;
        $arrangementController->setStatus();
        break;

    // Export Routes
    case '/admin/export/json':
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin')
            exit;
        $exportController->exportJson();
        break;
    case '/admin/export/csv':
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin')
            exit;
        $exportController->exportCsv();
        break;

    // CR Routes
    case '/cr/dashboard':
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'cr') {
            header('Location: /login');
            exit;
        }
        require '../src/Views/cr/dashboard.php';
        break;

    // Society President Routes
    case '/society/dashboard':
        $societyController->dashboard();
        break;
    case '/society/member/add':
        $societyController->addMember();
        break;
    case (preg_match('/^\/society\/member\/edit\/(\d+)$/', $uri, $matches) ? true : false):
        $societyController->editMember($matches[1]);
        break;
    case '/society/member/update':
        $societyController->updateMember();
        break;
    case (preg_match('/^\/society\/member\/delete\/(\d+)$/', $uri, $matches) ? true : false):
        $societyController->deleteMember($matches[1]);
        break;
    case '/society/event/add':
        $societyController->addEvent();
        break;
    case '/society/news/add':
        $societyController->addNews();
        break;
    case '/society/update':
        $societyController->updateSociety();
        break;
    case '/society/profile/update':
        $societyController->updateProfile();
        break;
    case (preg_match('/^\/society\/event\/edit\/(\d+)$/', $uri, $matches) ? true : false):
        $societyController->editEvent($matches[1]);
        break;
    case '/society/event/update':
        $societyController->updateEvent();
        break;
    case (preg_match('/^\/society\/event\/delete\/(\d+)$/', $uri, $matches) ? true : false):
        $societyController->deleteEvent($matches[1]);
        break;
    case (preg_match('/^\/society\/news\/edit\/(\d+)$/', $uri, $matches) ? true : false):
        $societyController->editNews($matches[1]);
        break;
    case '/society/news/update':
        $societyController->updateNews();
        break;
    case (preg_match('/^\/society\/news\/delete\/(\d+)$/', $uri, $matches) ? true : false):
        $societyController->deleteNews($matches[1]);
        break;

    // GR Routes
    case '/gr/dashboard':
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'gr') {
            header('Location: /login');
            exit;
        }
        require '../src/Views/gr/dashboard.php';
        break;

    default:
        http_response_code(404);
        echo "404 Not Found";
        break;
}
