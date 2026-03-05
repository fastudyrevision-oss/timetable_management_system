<?php
// src/Views/public/home.php
require '../src/Views/layouts/header.php';
?>

<!-- Hero Section -->
<div class="bg-primary text-white text-center py-5 mb-5 rounded shadow-sm">
    <div class="container py-4">
        <h1 class="display-4 fw-bold mb-3">Welcome to University Timetable</h1>
        <p class="lead mb-4">Your central hub for academic schedules, faculty information, and institutional excellence.</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="/timetable" class="btn btn-light btn-lg px-4 fw-bold text-primary shadow-sm hover-scale">View Timetable</a>
            <a href="/faculty" class="btn btn-outline-light btn-lg px-4 fw-bold hover-scale">Meet the Faculty</a>
        </div>
    </div>
</div>

<div class="container">
    <div class="row g-4 mb-5">
        <!-- Institution Section -->
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm transition-hover">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="bi bi-building text-primary" style="font-size: 3rem;"></i>
                    </div>
                    <h3 class="h4 fw-bold mb-3">Our Institution</h3>
                    <p class="text-muted">Founded on principles of excellence and innovation, our institution provides top-tier education with modern facilities, dedicated to shaping the leaders of tomorrow.</p>
                </div>
            </div>
        </div>
        
        <!-- Students Section -->
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm transition-hover">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="bi bi-people text-primary" style="font-size: 3rem;"></i>
                    </div>
                    <h3 class="h4 fw-bold mb-3">Our Students</h3>
                    <p class="text-muted">A diverse and vibrant community of learners. We foster an environment of collaboration, intellectual curiosity, and rigorous academic pursuit for all our students.</p>
                </div>
            </div>
        </div>
        
        <!-- Graduates Section -->
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm transition-hover">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="bi bi-mortarboard text-primary" style="font-size: 3rem;"></i>
                    </div>
                    <h3 class="h4 fw-bold mb-3">Our Graduates</h3>
                    <p class="text-muted">Our alumni network spans the globe, making significant impacts in technology, business, and research sectors. We prepare our graduates for immediate success.</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Links Section -->
    <div class="row bg-light rounded shadow-sm p-4 mb-5 text-center">
        <div class="col-12 mb-3">
            <h3 class="fw-bold text-dark">Quick Access</h3>
        </div>
        <div class="col-md-4 mb-3 mb-md-0">
            <a href="/timetable" class="text-decoration-none">
                <div class="p-3 border rounded bg-white transition-hover">
                    <i class="bi bi-calendar-week fs-2 text-primary d-block mb-2"></i>
                    <span class="fw-bold text-dark">Class Schedules</span>
                </div>
            </a>
        </div>
        <div class="col-md-4 mb-3 mb-md-0">
            <a href="/faculty" class="text-decoration-none">
                <div class="p-3 border rounded bg-white transition-hover">
                    <i class="bi bi-person-lines-fill fs-2 text-primary d-block mb-2"></i>
                    <span class="fw-bold text-dark">Faculty Directory</span>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="/societies" class="text-decoration-none">
                <div class="p-3 border rounded bg-white transition-hover">
                    <i class="bi bi-people-fill fs-2 text-primary d-block mb-2"></i>
                    <span class="fw-bold text-dark">Student Societies</span>
                </div>
            </a>
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
.hover-scale {
    transition: transform 0.2s ease;
}
.hover-scale:hover {
    transform: scale(1.05);
}
</style>

<?php require '../src/Views/layouts/footer.php'; ?>
