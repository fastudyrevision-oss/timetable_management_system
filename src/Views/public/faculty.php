<?php
// src/Views/public/faculty.php
require '../src/Views/layouts/header.php';
?>

<div class="container py-5">
    <div class="row mb-5 text-center">
        <div class="col-lg-8 mx-auto">
            <h1 class="display-4 fw-bold mb-3">Our Faculty</h1>
            <p class="lead text-muted">Dedicated educators shaping the future of Information Technology.</p>
            <div class="header-line mx-auto"></div>
        </div>
    </div>

    <div class="row g-4 justify-content-center">
        <?php if (empty($teachers)): ?>
            <div class="col-12 text-center">
                <div class="alert alert-light shadow-sm">No faculty members found.</div>
            </div>
        <?php else: ?>
            <?php foreach ($teachers as $teacher): ?>
                <?php 
                    $name = trim($teacher['name']);
                    if (strtolower($name) === 'tba' || stripos($name, 'allocated') !== false) {
                        continue;
                    }
                    $pic = !empty($teacher['picture']) ? htmlspecialchars($teacher['picture']) : null;
                    $post = $teacher['post'] ?? 'Faculty Member';
                    $isTop = (stripos($post, 'Chairman') !== false || stripos($post, 'CSA') !== false || stripos($post, 'Deputy Registrar') !== false);
                ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="faculty-card <?= $isTop ? 'top-official' : '' ?>">
                        <div class="card-front">
                            <div class="profile-header">
                                <?php if ($pic): ?>
                                    <img src="<?= $pic ?>" alt="Faculty" class="profile-img">
                                <?php else: ?>
                                    <div class="profile-placeholder">
                                        <i class="bi bi-person-fill"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="profile-body">
                                <h4 class="name h5"><?= htmlspecialchars($name) ?></h4>
                                <span class="badge-post"><?= htmlspecialchars($post) ?></span>
                                <?php if(!empty($teacher['email'])): ?>
                                    <div class="email-info">
                                        <a href="mailto:<?= htmlspecialchars($teacher['email']) ?>" class="text-reset text-decoration-none">
                                            <i class="bi bi-envelope-at me-1"></i><?= htmlspecialchars($teacher['email']) ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                                <?php if(!empty($teacher['qualification'])): ?>
                                    <div class="qualification mt-2">
                                        <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 0.75rem;">Qualification</small>
                                        <p class="mb-0 x-small"><?= nl2br(htmlspecialchars($teacher['qualification'])) ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <?php if(!empty($teacher['research_interest'])): ?>
                            <div class="card-hover-info">
                                <div class="hover-content w-100">
                                    <h6 class="text-uppercase fw-bold mb-3" style="font-size: 0.75rem; letter-spacing: 1.5px; color: rgba(255,255,255,0.7);">Research Interests</h6>
                                    <ol class="research-list text-start">
                                        <?php 
                                            $interests = preg_split('/\r\n|\r|\n/', $teacher['research_interest']);
                                            foreach ($interests as $interest): 
                                                $interest = trim($interest);
                                                if (empty($interest)) continue;
                                        ?>
                                            <li><?= htmlspecialchars($interest) ?></li>
                                        <?php endforeach; ?>
                                    </ol>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<style>
:root {
    --primary-color: #2563eb;
    --card-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    --card-shadow-hover: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
}

.header-line {
    width: 80px;
    height: 4px;
    background: var(--primary-color);
    border-radius: 2px;
}

.faculty-card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e5e7eb;
    position: relative;
    overflow: hidden;
    height: 100%;
    min-height: 360px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: var(--card-shadow);
    display: flex;
    flex-direction: column;
}

.faculty-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--card-shadow-hover);
    border-color: var(--primary-color);
}

.top-official {
    border-top: 5px solid var(--primary-color);
}

.profile-header {
    padding-top: 1.5rem;
    display: flex;
    justify-content: center;
}

.profile-img, .profile-placeholder {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #f8fafc;
    background: #f1f5f9;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.profile-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    color: #94a3b8;
}

.profile-body {
    padding: 1.25rem;
    text-align: center;
    flex-grow: 1;
}

.name {
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.2rem;
    font-size: 1.3rem;
}

.badge-post {
    display: inline-block;
    padding: 0.2rem 0.6rem;
    background: #eff6ff;
    color: #1d4ed8;
    border-radius: 9999px;
    font-size: 0.85rem;
    font-weight: 600;
    margin-bottom: 0.75rem;
}

.email-info {
    font-size: 0.95rem;
    color: #64748b;
    margin-bottom: 0.5rem;
    word-break: break-all;
}

.qualification {
    text-align: left;
    background: #f8fafc;
    padding: 0.6rem;
    border-radius: 10px;
}

.x-small {
    font-size: 0.9rem;
    line-height: 1.3;
}

/* Hover reveal effect */
.card-hover-info {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(30, 58, 138, 0.96); /* Darker blue for better contrast */
    color: #fff;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transform: translateY(100%);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 10;
}

.faculty-card:hover .card-hover-info {
    opacity: 1;
    transform: translateY(0);
}

.hover-content {
    text-align: center;
}

.hover-content h6 {
    color: rgba(255, 255, 255, 0.9);
}

.research-list {
    font-size: 1rem;
    padding-left: 2rem;
    margin-bottom: 0;
    max-height: 250px;
    overflow-y: auto;
}

.research-list li {
    margin-bottom: 0.5rem;
    padding-left: 0.25rem;
    line-height: 1.4;
}

.research-list li::marker {
    color: rgba(255, 255, 255, 0.6);
    font-weight: bold;
}

/* Custom Scrollbar for list */
.research-list::-webkit-scrollbar {
    width: 4px;
}
.research-list::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
}
.research-list::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.3);
    border-radius: 10px;
}
</style>

<?php require '../src/Views/layouts/footer.php'; ?>
