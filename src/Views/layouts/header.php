<!-- src/Views/layouts/header.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT Department Portal (IT-DP)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="/assets/css/timetable.css" rel="stylesheet">

    <!-- jQuery (required for Select2) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                width: '100%' // Fix width issue in Bootstrap modals/forms
            });
        });
    </script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white mb-4 border-bottom shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="/">
                <img src="/assets/images/final_logo.png" alt="University of Sargodha" height="50" class="me-2">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <?php $current_uri = $_SERVER['REQUEST_URI']; ?>
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link nav-hover <?= ($current_uri == '/' || $current_uri == '') ? 'active-nav' : '' ?>" href="/">
                            <i class="bi bi-house-door me-1"></i>Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-hover <?= ($current_uri == '/timetable') ? 'active-nav' : '' ?>" href="/timetable">
                            <i class="bi bi-calendar3 me-1"></i>Timetable
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-hover <?= ($current_uri == '/academic-calendar') ? 'active-nav' : '' ?>" href="/academic-calendar">
                            <i class="bi bi-calendar-check me-1"></i>Academic Calendar
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-hover <?= ($current_uri == '/faculty') ? 'active-nav' : '' ?>" href="/faculty">
                            <i class="bi bi-people me-1"></i>Faculty
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-hover <?= ($current_uri == '/societies') ? 'active-nav' : '' ?>" href="/societies">
                            <i class="bi bi-mortarboard me-1"></i>Societies
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item"><a class="nav-link" href="/logout"><i class="bi bi-box-arrow-right me-1"></i>Logout</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="/login"><i class="bi bi-box-arrow-in-right me-1"></i>Login</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">