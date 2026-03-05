<?php
// src/Views/public/society_detail.php
require '../src/Views/layouts/header.php';
?>

<div class="society-header py-5 mb-5 text-white text-center position-relative" 
     style="background: linear-gradient(rgba(30, 27, 75, 0.8), rgba(49, 46, 129, 0.9)), url('<?= htmlspecialchars($society['president_picture'] ?? '/assets/images/default-avatar.png') ?>'); background-size: cover; background-position: center; min-height: 300px; display: flex; align-items: center;">
    <div class="container position-relative" style="z-index: 2;">
        <h1 class="display-3 fw-bold mb-2 shadow-sm"><?= htmlspecialchars($society['name']) ?></h1>
        <div class="d-inline-block px-4 py-2 rounded-pill bg-white bg-opacity-10 backdrop-blur mb-4 border border-white border-opacity-20 shadow-sm">
            <i class="bi bi-person-badge me-2"></i>
            President: <span class="fw-bold"><?= htmlspecialchars($society['president_name'] ?? 'TBA') ?></span>
        </div>
        <p class="lead opacity-90 mx-auto" style="max-width: 800px; font-weight: 500;"><?= htmlspecialchars($society['description']) ?></p>
    </div>
</div>

<style>
.backdrop-blur {
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
}
</style>

<div class="container">
    <!-- Events Section -->
    <div class="row mb-5">
        <div class="col-12">
            <h3 class="fw-bold mb-4 border-bottom pb-2">Upcoming Events & News</h3>
        </div>
        <?php if (empty($events) && empty($news)): ?>
            <div class="col-12 text-center py-5">
                <p class="text-muted">No events or news posted yet.</p>
            </div>
        <?php else: ?>
            <div class="col-md-8">
                <?php foreach ($events as $event): ?>
                    <div class="card mb-4 border-0 shadow-sm overflow-hidden">
                        <div class="row g-0">
                            <?php if ($event['poster_path']): ?>
                                <div class="col-md-4">
                                    <img src="<?= htmlspecialchars($event['poster_path']) ?>" class="img-fluid h-100 object-fit-cover" alt="Event Poster">
                                </div>
                            <?php endif; ?>
                            <div class="col-md-<?= $event['poster_path'] ? '8' : '12' ?>">
                                <div class="card-body">
                                    <h5 class="card-title fw-bold"><?= htmlspecialchars($event['title']) ?></h5>
                                    <p class="card-text text-muted"><?= nl2br(htmlspecialchars($event['description'])) ?></p>
                                    <p class="card-text"><small class="text-primary fw-bold"><i class="bi bi-calendar-event me-2"></i><?= date('F j, Y - g:i A', strtotime($event['event_date'])) ?></small></p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="col-md-4">
                <h5 class="fw-bold mb-3">Latest News</h5>
                <?php foreach ($news as $item): ?>
                    <div class="card mb-3 border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="fw-bold mb-1"><?= htmlspecialchars($item['title']) ?></h6>
                            <p class="small text-muted mb-2"><?= nl2br(htmlspecialchars($item['content'])) ?></p>
                            <span class="text-muted" style="font-size: 0.75rem;"><i class="bi bi-clock me-1"></i><?= date('M d, Y', strtotime($item['created_at'])) ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Members Section -->
    <div class="row mb-5">
        <div class="col-12">
            <h3 class="fw-bold mb-4 border-bottom pb-2">Our Team</h3>
        </div>
        <?php foreach ($members as $member): ?>
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card h-100 border-0 shadow-sm transition-hover text-center p-3">
                    <div class="member-img-wrapper mb-3 mx-auto">
                        <img src="<?= $member['picture_path'] ?? '/assets/images/default-avatar.png' ?>" class="rounded-circle shadow-sm" alt="Member" style="width: 120px; height: 120px; object-fit: cover; border: 4px solid #f8f9fa;">
                    </div>
                    <h5 class="fw-bold mb-1"><?= htmlspecialchars($member['name']) ?></h5>
                    <p class="text-primary small mb-0"><?= htmlspecialchars($member['designation']) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
.transition-hover {
    transition: all 0.3s ease;
}
.transition-hover:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
}
.object-fit-cover {
    object-fit: cover;
}
</style>

<?php require '../src/Views/layouts/footer.php'; ?>
