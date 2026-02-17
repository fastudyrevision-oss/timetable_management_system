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
            <a class="navbar-brand" href="#">Timetable System (Admin)</a>
            <a href="/logout" class="btn btn-outline-light">Logout</a>
        </div>
    </nav>
    <div class="container mt-4">
        <h2>Welcome, Admin</h2>
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Manage Timetable</h5>
                        <p class="card-text">Upload and edit timetables.</p>
                        <a href="/admin/timetable" class="btn btn-primary">Go</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Academic Structure</h5>
                        <p class="card-text">Manage Batches, Sections, Subjects.</p>
                        <a href="/admin/academic" class="btn btn-secondary">Go</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>