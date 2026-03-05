<?php
// src/Views/public/faculty.php
require '../src/Views/layouts/header.php';
?>

<div class="row mb-4">
    <div class="col-md-12 text-center">
        <h2 class="display-5 text-primary fw-bold">Faculty Directory</h2>
        <p class="text-muted lead">Meet our dedicated teaching staff</p>
    </div>
</div>

<div class="row g-4">
    <?php if (empty($teachers)): ?>
        <div class="col-12 text-center">
            <div class="alert alert-info">No faculty members found.</div>
        </div>
    <?php else: ?>
        <?php foreach ($teachers as $teacher): ?>
            <?php 
                $name = trim($teacher['name']);
                if (strtolower($name) === 'tba' || stripos($name, 'allocated') !== false) {
                    continue;
                }
                $pic = !empty($teacher['picture']) ? htmlspecialchars($teacher['picture']) : null;
            ?>
            <div class="col-md-4 col-sm-6">
                <div class="card h-100 shadow-sm border-0 faculty-card transition-hover">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <?php if ($pic): ?>
                                <img src="<?= $pic ?>" alt="Picture" class="rounded-circle shadow-sm" style="width: 100px; height: 100px; object-fit: cover;">
                            <?php else: ?>
                                <i class="bi bi-person-circle text-primary" style="font-size: 4rem;"></i>
                            <?php endif; ?>
                        </div>
                        <h4 class="card-title fw-bold text-dark mb-1"><?= htmlspecialchars($name) ?></h4>
                        <p class="text-primary small fw-bold mb-1"><?= htmlspecialchars($teacher['post'] ?? 'Faculty Member') ?></p>
                        
                        <?php if(!empty($teacher['email'])): ?>
                            <p class="text-muted small mb-2"><i class="bi bi-envelope"></i> <?= htmlspecialchars($teacher['email']) ?></p>
                        <?php endif; ?>

                        <?php if(!empty($teacher['qualification'])): ?>
                            <div class="mt-2 pt-2 border-top">
                                <span class="d-block text-secondary small text-uppercase fw-bold" style="font-size: 0.75rem;">Qualification</span>
                                <p class="small mb-1 text-muted"><?= nl2br(htmlspecialchars($teacher['qualification'])) ?></p>
                            </div>
                        <?php endif; ?>

                        <?php if(!empty($teacher['research_interest'])): ?>
                            <div class="mt-2">
                                <span class="d-block text-secondary small text-uppercase fw-bold" style="font-size: 0.75rem;">Research Interest</span>
                                <p class="small mb-0 text-muted"><?= nl2br(htmlspecialchars($teacher['research_interest'])) ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<style>
.faculty-card {
    border-radius: 12px;
    background: linear-gradient(145deg, #ffffff, #f8f9fa);
}
.transition-hover {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.transition-hover:hover {
    transform: translateY(-5px);
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
}
</style>

<?php require '../src/Views/layouts/footer.php'; ?>
