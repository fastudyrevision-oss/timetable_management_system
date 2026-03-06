<?php
// src/Views/public/societies.php
require '../src/Views/layouts/header.php';
?>

<div class="row mb-5">
    <div class="col-md-12 text-center py-5">
        <h2 class="display-3 text-primary fw-bold" style="letter-spacing: -1px;">Department Societies</h2>
        <p class="text-muted lead mx-auto" style="max-width: 700px;">Experience a new dimension of student life. Click on the 3D cards to explore each society's vision and team.</p>
    </div>
</div>

<div class="row g-5 mb-5 justify-content-center">
    <?php foreach ($societies as $society): ?>
    <div class="col-lg-4 col-md-6">
        <div class="scene">
            <div class="card-3d" onclick="window.location.href='/society/<?= $society['id'] ?>'">
                <div class="card__face card__face--front" style="<?= $society['president_picture'] ? "background-image: url('".htmlspecialchars($society['president_picture'])."'); background-size: cover; background-position: center;" : "" ?>">
                    <div class="card-overlay"></div>
                    <div class="card-content">
                        <div class="icon-box mb-4">
                            <?php if (!empty($society['logo_path'])): ?>
                                <img src="<?= htmlspecialchars($society['logo_path']) ?>" alt="<?= htmlspecialchars($society['name']) ?> Logo" class="img-fluid rounded-circle" style="width: 100%; height: 100%; object-fit: cover; border: 2px solid rgba(255,255,255,0.2);">
                            <?php else: ?>
                                <?php 
                                    $icon = 'bi-people';
                                    if (stripos($society['name'], 'Event') !== false) $icon = 'bi-megaphone';
                                    if (stripos($society['name'], 'GMS') !== false) $icon = 'bi-palette';
                                    if (stripos($society['name'], 'Welfare') !== false) $icon = 'bi-heart-pulse';
                                ?>
                                <i class="bi <?= $icon ?> display-3 text-white"></i>
                            <?php endif; ?>
                        </div>
                        <h3 class="fw-bold text-white mb-1"><?= htmlspecialchars($society['name']) ?></h3>
                        <p class="text-white-50 small mb-3">President: <?= htmlspecialchars($society['president_name'] ?? 'TBA') ?></p>
                        <p class="text-white px-3 fw-medium">Leading the path to innovation and student empowerment.</p>
                        <div class="mt-4">
                            <span class="btn btn-primary btn-sm rounded-pill px-4 shadow">Explore Sphere</span>
                        </div>
                    </div>
                </div>
                <div class="card__face card__face--back">
                    <div class="card-content">
                        <h4 class="text-white mb-3">Our Vision</h4>
                        <p class="text-white-50 small"><?= htmlspecialchars($society['description']) ?></p>
                        <div class="mt-4">
                            <i class="bi bi-arrow-right-circle display-4 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<style>
:root {
    --primary-gradient: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
}

.scene {
    width: 100%;
    height: 400px;
    perspective: 1200px;
}

.card-3d {
    width: 100%;
    height: 100%;
    position: relative;
    transition: transform 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    transform-style: preserve-3d;
    cursor: pointer;
}

.card-3d:hover {
    transform: rotateY(180deg) scale(1.05);
}

.card__face {
    position: absolute;
    width: 100%;
    height: 100%;
    backface-visibility: hidden;
    border-radius: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    padding: 2rem;
}

.card__face--front {
    background: var(--primary-gradient);
    border: 1px solid rgba(255,255,255,0.1);
    overflow: hidden;
}

.card-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(to bottom, rgba(0,0,0,0.2) 0%, rgba(0,0,0,0.7) 100%);
    z-index: 1;
}

.card-content {
    transform: translateZ(60px);
    z-index: 2;
    position: relative;
}

.card__face--back {
    background: #1e1b4b;
    transform: rotateY(180deg);
    border: 1px solid rgba(255,255,255,0.05);
}

.icon-box {
    width: 100px;
    height: 100px;
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(10px);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    border: 2px solid rgba(255,255,255,0.2);
}


@media (max-width: 768px) {
    .scene {
        height: 350px;
    }
}
</style>

<?php require '../src/Views/layouts/footer.php'; ?>
