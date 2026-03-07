<?php
// src/Views/public/home.php
require '../src/Views/layouts/header.php';
?>

<!-- Hero Section -->
<div class="text-white text-center py-5 mb-0 rounded-bottom shadow-sm" style="background-color: #192f59;">
    <div class="container py-4">
        <h1 class="display-4 fw-bold mb-3">Welcome to University Timetable</h1>
        <p class="lead mb-4">Your central hub for academic schedules, faculty information, and institutional excellence.</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="/timetable" class="btn btn-light btn-lg px-4 fw-bold text-primary shadow-sm hover-scale">View Timetable</a>
            <a href="/faculty" class="btn btn-outline-light btn-lg px-4 fw-bold hover-scale">Meet the Faculty</a>
        </div>
    </div>
</div>

<!-- Message from the Chairman Section -->
<section class="chairman-message-section py-5 mb-5 bg-white shadow-sm border-bottom">
    <div class="container px-4">
        <div class="row align-items-center">
            <div class="col-lg-4 mb-4 mb-lg-0 text-center">
                <div class="chairman-img-wrapper">
                    <img src="/assets/images/chairman.jpg" alt="Chairman" class="img-fluid chairman-img shadow-lg">
                    <div class="chairman-badge bg-primary text-white">
                        <span class="small fw-bold">CHAIRMAN</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 ps-lg-5">
                <?php
                // Fetch Chairman's name directly from database
                $stmt = $pdo->prepare("SELECT name FROM teachers WHERE post LIKE :post LIMIT 1");
                $stmt->execute(['post' => '%Chairman%']);
                $chairmanName = $stmt->fetchColumn();
                ?>
                <h2 class="display-6 fw-bold text-dark mb-3">Message from the Chairman</h2>
                <div class="title-accent mb-4"></div>
                <div class="chairman-content text-muted lead">
                    <p class="mb-4">Our faculty is a dynamic community of educators and researchers dedicated to excellence in IT. In this era of Artificial Intelligence, we are committed to preparing future leaders with the innovative expertise needed to solve real-world problems. We welcome all devoted individuals seeking to excel and push the boundaries of knowledge at the Department of IT, UOS.</p>
                    <div class="chairman-signature mt-4">
                        <p class="mb-0 fw-bold text-dark"><?= htmlspecialchars($chairmanName ?: 'Chairman') ?></p>
                        <p class="small text-muted mb-0">Chairman, Department of IT</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container">
    <div class="row g-4 mb-5">
        <!-- Vision Card -->
        <div class="col-lg-6">
            <div class="card h-100 border-0 shadow-sm transition-hover vision-card">
                <div class="card-body p-4 p-xl-5">
                    <div class="d-flex align-items-center mb-4">
                        <div class="icon-box bg-primary-soft text-primary me-3">
                            <i class="bi bi-eye-fill fs-3"></i>
                        </div>
                        <h3 class="h4 fw-bold mb-0">Our Vision</h3>
                    </div>
                    <p class="text-muted lead-sm mb-0">
                        Department of Information Technology aspires to societal betterment through a commitment to quality education, technical skills, fostering creativity via analytical learning, and conducting impactful research in the ever dynamic field of Information Technology.
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Mission Card -->
        <div class="col-lg-6">
            <div class="card h-100 border-0 shadow-sm transition-hover mission-card">
                <div class="card-body p-4 p-xl-5">
                    <div class="d-flex align-items-center mb-4">
                        <div class="icon-box bg-primary-soft text-primary me-3">
                            <i class="bi bi-bullseye fs-3"></i>
                        </div>
                        <h3 class="h4 fw-bold mb-0">Our Mission</h3>
                    </div>
                    <ul class="mission-list text-muted ps-0">
                        <li class="mb-3 d-flex align-items-start">
                            <i class="bi bi-check2-circle text-primary me-2 mt-1"></i>
                            <span>Imparting students with an enriching learning experience in the field of Information Technology centered on in-depth knowledge, critical thinking, innovation, and technical proficiency.</span>
                        </li>
                        <li class="mb-3 d-flex align-items-start">
                            <i class="bi bi-check2-circle text-primary me-2 mt-1"></i>
                            <span>Cultivating a professional and collaborative work environment for faculty and staff of the department, fostering the attainment of professional excellence</span>
                        </li>
                        <li class="d-flex align-items-start">
                            <i class="bi bi-check2-circle text-primary me-2 mt-1"></i>
                            <span>Contributing to knowledge economy, drive social transformation and deliver community services through advanced studies and research in the field of Information Technology.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Degree Programs Section -->
    <div class="row mb-5">
        <div class="col-12 text-center mb-5">
            <h2 class="display-6 fw-bold text-dark mb-3">Degree Programs</h2>
            <div class="title-accent mx-auto mb-4"></div>
            <p class="lead text-muted mx-auto" style="max-width: 800px;">Our diverse and varied degree programs are designed to uplift research aptitude and instill skill based knowledge to cater the needs of job market.</p>
        </div>
        
        <!-- BS 4 Years -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="program-card h-100 shadow-sm transition-hover border-0">
                <div class="program-card-inner">
                    <div class="program-card-front d-flex flex-column align-items-center justify-content-center p-4">
                        <i class="bi bi-mortarboard-fill fs-1 text-primary mb-3"></i>
                        <h4 class="fw-bold mb-0">BS 4 Years</h4>
                    </div>
                    <div class="program-card-back p-4">
                        <h5 class="fw-bold text-white mb-3">Programs Offered</h5>
                        <ul class="list-unstyled text-white-50 small mb-0">
                            <li class="mb-2"><i class="bi bi-chevron-right me-2"></i>BS Information Technology</li>
                            <li><i class="bi bi-chevron-right me-2"></i>BS Cyber Security</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- BS Intake -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="program-card h-100 shadow-sm transition-hover border-0">
                <div class="program-card-inner">
                    <div class="program-card-front d-flex flex-column align-items-center justify-content-center p-4">
                        <i class="bi bi-mortarboard fs-1 text-primary mb-3"></i>
                        <h4 class="fw-bold mb-0">BS Intake</h4>
                    </div>
                    <div class="program-card-back p-4">
                        <h5 class="fw-bold text-white mb-3">Programs Offered</h5>
                        <ul class="list-unstyled text-white-50 small mb-0">
                            <li><i class="bi bi-chevron-right me-2"></i>BS 5th Intake IT</li>
                            <li class="mt-2 small opacity-75">(After ADP)</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- MS Programs -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="program-card h-100 shadow-sm transition-hover border-0">
                <div class="program-card-inner">
                    <div class="program-card-front d-flex flex-column align-items-center justify-content-center p-4">
                        <i class="bi bi-journal-check fs-1 text-primary mb-3"></i>
                        <h4 class="fw-bold mb-0">MS Programs</h4>
                    </div>
                    <div class="program-card-back p-4">
                        <h5 class="fw-bold text-white mb-3">Programs Offered</h5>
                        <ul class="list-unstyled text-white-50 small mb-0">
                            <li><i class="bi bi-chevron-right me-2"></i>MS IT</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- PhD Programs -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="program-card h-100 shadow-sm transition-hover border-0">
                <div class="program-card-inner">
                    <div class="program-card-front d-flex flex-column align-items-center justify-content-center p-4">
                        <i class="bi bi-award-fill fs-1 text-primary mb-3"></i>
                        <h4 class="fw-bold mb-0">PhD Programs</h4>
                    </div>
                    <div class="program-card-back p-4">
                        <h5 class="fw-bold text-white mb-3">Programs Offered</h5>
                        <ul class="list-unstyled text-white-50 small mb-0">
                            <li><i class="bi bi-chevron-right me-2"></i>PhD IT</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Facts Section -->
    <div class="row mb-5">
        <div class="col-12 text-center mb-5">
            <h2 class="display-6 fw-bold text-dark mb-3">Quick Facts</h2>
            <div class="title-accent mx-auto mb-4"></div>
            <p class="lead text-muted mx-auto" style="max-width: 900px;">At UOS we are proud of our accomplishments. Together, faculty, students and staff contribute to create an amazing experience. Here are just a few reasons why UOS is the top varsity in the province of Punjab, Pakistan.</p>
        </div>

        <div class="row g-4 text-center">
            <!-- Faculty Stat -->
            <div class="col-6 col-lg-2">
                <div class="fact-card h-100 p-4 rounded shadow-sm bg-white transition-hover">
                    <i class="bi bi-people fs-1 mb-2 d-block"></i>
                    <h3 class="fw-bold display-6 counter" data-target="12">0</h3>
                    <p class="small text-uppercase fw-bold mb-0">Faculty</p>
                </div>
            </div>

            <!-- Degree Programs Stat -->
            <div class="col-6 col-lg-2">
                <div class="fact-card h-100 p-4 rounded shadow-sm bg-white transition-hover">
                    <i class="bi bi-mortarboard fs-1 mb-2 d-block"></i>
                    <h3 class="fw-bold display-6 counter" data-target="5">0</h3>
                    <p class="small text-uppercase fw-bold mb-0">Degree Programs</p>
                </div>
            </div>

            <!-- Graduated Students Stat -->
            <div class="col-6 col-lg-2">
                <div class="fact-card h-100 p-4 rounded shadow-sm bg-white transition-hover">
                    <i class="bi bi-mortarboard fs-1 mb-2 d-block"></i>
                    <h3 class="fw-bold display-6"><span class="counter" data-target="500">0</span>+</h3>
                    <p class="small text-uppercase fw-bold mb-0">Graduates</p>
                </div>
            </div>

            <!-- Enrolled Students Stat -->
            <div class="col-6 col-lg-2">
                <div class="fact-card h-100 p-4 rounded shadow-sm bg-white transition-hover">
                    <i class="bi bi-people fs-1 mb-2 d-block"></i>
                    <h3 class="fw-bold display-6"><span class="counter" data-target="1000">0</span>+</h3>
                    <p class="small text-uppercase fw-bold mb-0">Enrolled</p>
                </div>
            </div>

            <!-- Visiting Faculty Stat -->
            <div class="col-6 col-lg-2">
                <div class="fact-card h-100 p-4 rounded shadow-sm bg-white transition-hover">
                    <i class="bi bi-briefcase fs-1 mb-2 d-block"></i>
                    <h3 class="fw-bold display-6"><span class="counter" data-target="30">0</span>+</h3>
                    <p class="small text-uppercase fw-bold mb-0">Visiting</p>
                </div>
            </div>

            <!-- Research Stat -->
            <div class="col-6 col-lg-2">
                <div class="fact-card h-100 p-4 rounded shadow-sm bg-white transition-hover">
                    <i class="bi bi-journal-text fs-1 mb-2 d-block"></i>
                    <h3 class="fw-bold display-6"><span class="counter" data-target="200">0</span>+</h3>
                    <p class="small text-uppercase fw-bold mb-0">Publications</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const counters = document.querySelectorAll('.counter');
    const animationDuration = 2000; // 2 seconds

    const animateCounter = (counter) => {
        const target = +counter.getAttribute('data-target');
        const increment = target / (animationDuration / 16); // 16ms per frame approx

        let currentCount = 0;
        const updateCount = () => {
            currentCount += increment;
            if (currentCount < target) {
                counter.innerText = Math.ceil(currentCount);
                setTimeout(updateCount, 16);
            } else {
                counter.innerText = target;
            }
        };
        updateCount();
    };

    // Use Intersection Observer to start animation when in view
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    counters.forEach(counter => observer.observe(counter));
});
</script>

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
.chairman-message-section {
    position: relative;
    z-index: 1;
}
.chairman-content {
    line-height: 1.8;
    font-size: 1.1rem;
}
.chairman-img-wrapper {
    position: relative;
    display: inline-block;
}
.chairman-img {
    border-radius: 20px;
    max-height: 400px;
    width: auto;
    border: 8px solid #f8fafc;
}
.chairman-badge {
    position: absolute;
    bottom: 20px;
    right: -10px;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}
.title-accent {
    width: 60px;
    height: 4px;
    background: var(--bs-primary);
    border-radius: 2px;
}
.bg-primary-soft {
    background-color: rgba(37, 99, 235, 0.1);
}
.icon-box {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
}
.mission-list {
    list-style: none;
}
.lead-sm {
    font-size: 1.1rem;
    line-height: 1.6;
}
.vision-card, .mission-card {
    transition: all 0.4s ease;
}
.vision-card:hover, .mission-card:hover {
    background-color: #1b3062 !important;
    transform: translateY(-10px);
}
.vision-card:hover .text-muted, 
.mission-card:hover .text-muted,
.vision-card:hover h3,
.mission-card:hover h3 {
    color: #fff !important;
}
.vision-card:hover .bg-primary-soft,
.mission-card:hover .bg-primary-soft {
    background-color: rgba(255, 255, 255, 0.15) !important;
}
.vision-card:hover .text-primary,
.mission-card:hover .text-primary {
    color: #fff !important;
}

/* Program Cards Styles */
.program-card {
    height: 180px !important;
    perspective: 1000px;
    background: transparent;
}
.program-card-inner {
    position: relative;
    width: 100%;
    height: 180px;
    text-align: center;
    transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    transform-style: preserve-3d;
}
.program-card:hover .program-card-inner {
    transform: rotateY(180deg);
}
.program-card-front, .program-card-back {
    position: absolute;
    width: 100%;
    height: 100%;
    backface-visibility: hidden;
    border-radius: 16px;
    background-color: #fff;
}
.program-card-back {
    background-color: #1b3062;
    transform: rotateY(180deg);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
}

/* Fact Cards Styles */
.fact-card {
    transition: all 0.3s ease;
    color: #1b3062;
    border: 1px solid rgba(27, 48, 98, 0.1);
}
.fact-card i {
    transition: color 0.3s ease;
}
.fact-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
}
.fact-card:hover,
.fact-card:hover h3,
.fact-card:hover p,
.fact-card:hover i {
    color: #dc3545 !important; /* Bootstrap red */
}
</style>

<?php require '../src/Views/layouts/footer.php'; ?>
