<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="/admin/dashboard">
                <img src="/assets/images/logo.png" alt="Logo" height="40" class="me-2">
                Timetable System (Admin)
            </a>
            <a href="/logout" class="btn btn-outline-light">Logout</a>
        </div>
    </nav>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Administrator Command Center</h2>
            <span class="badge bg-primary px-3 py-2">System Admin</span>
        </div>

        <div class="row g-4">
            <!-- Timetable & Academic -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm transition-hover">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-calendar3 display-4 text-primary mb-3"></i>
                        <h5 class="fw-bold">Timetable Master</h5>
                        <p class="text-muted small">Full control over class schedules and room assignments.</p>
                        <a href="/admin/timetable" class="btn btn-primary rounded-pill px-4">Manage</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm transition-hover">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-diagram-3 display-4 text-secondary mb-3"></i>
                        <h5 class="fw-bold">Academic Base</h5>
                        <p class="text-muted small">Define Batches, Sections, and Subjects structure.</p>
                        <a href="/admin/academic" class="btn btn-secondary rounded-pill px-4">Manage</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm transition-hover">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-person-badge display-4 text-info mb-3"></i>
                        <h5 class="fw-bold">Faculty Portal</h5>
                        <p class="text-muted small">Maintain the directory of esteemed faculty members.</p>
                        <a href="/admin/teachers" class="btn btn-info text-white rounded-pill px-4">Manage</a>
                    </div>
                </div>
            </div>

            <!-- Users & Security -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm transition-hover">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-people display-4 text-success mb-3"></i>
                        <h5 class="fw-bold">User Management</h5>
                        <p class="text-muted small">Manage CRs, GRs, Presidents and their identifiers.</p>
                        <a href="/admin/users" class="btn btn-success rounded-pill px-4">Manage</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm transition-hover">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-shield-lock display-4 text-danger mb-3"></i>
                        <h5 class="fw-bold">Audit & Security</h5>
                        <p class="text-muted small">View comprehensive system activity logs and audit trails.</p>
                        <a href="/admin/logs" class="btn btn-danger rounded-pill px-4">View Logs</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm transition-hover">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-award display-4 text-warning mb-3"></i>
                        <h5 class="fw-bold">Societies Control</h5>
                        <p class="text-muted small">Oversee all student societies, their events and news.</p>
                        <a href="/societies" class="btn btn-warning text-white rounded-pill px-4">Oversee</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
    .transition-hover {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .transition-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
    </style>
</body>

</html>