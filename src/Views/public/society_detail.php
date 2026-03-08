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
.event-card .img-wrapper {
    overflow: hidden;
}
.event-poster {
    transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
}
.event-card:hover .event-poster {
    transform: scale(1.08);
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
                    <div class="card mb-4 border-0 shadow-sm overflow-hidden event-card">
                        <div class="row g-0">
                            <?php if ($event['poster_path']): ?>
                                <div class="col-md-4 img-wrapper">
                                    <img src="<?= htmlspecialchars($event['poster_path']) ?>" class="img-fluid event-poster" alt="Event Poster">
                                </div>
                            <?php endif; ?>
                            <div class="col-md-<?= $event['poster_path'] ? '8' : '12' ?>">
                                <div class="card-body">
                                    <h5 class="card-title fw-bold"><?= htmlspecialchars($event['title']) ?></h5>
                                    <p class="card-text text-muted"><?= nl2br(htmlspecialchars($event['description'])) ?></p>
                                    <p class="card-text mb-2">
                                        <small class="text-primary fw-bold">
                                            <i class="bi bi-calendar-event me-2"></i>
                                            <?= $event['event_date'] ? date('F j, Y - g:i A', strtotime($event['event_date'])) : '<span class="badge bg-soft-primary text-primary border border-primary border-opacity-25 px-3">Coming Soon</span>' ?>
                                        </small>
                                    </p>
                                    <?php if (!empty($event['action_link'])): ?>
                                        <a href="<?= htmlspecialchars($event['action_link']) ?>" target="_blank" class="btn btn-sm btn-primary rounded-pill px-3">
                                            <i class="bi bi-link-45deg me-1"></i>
                                            <?= htmlspecialchars($event['action_label'] ?: 'Register Now') ?>
                                        </a>
                                    <?php endif; ?>
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
                            <?php if (!empty($item['image_path'])): ?>
                                <img src="<?= htmlspecialchars($item['image_path']) ?>" class="img-fluid rounded mb-2 w-100" alt="News Image">
                            <?php endif; ?>
                            <p class="small text-muted mb-2"><?= nl2br(htmlspecialchars($item['content'])) ?></p>
                            <?php if (!empty($item['action_link'])): ?>
                                <a href="<?= htmlspecialchars($item['action_link']) ?>" target="_blank" class="btn btn-sm btn-primary rounded-pill px-3 mb-2">
                                    <i class="bi bi-link-45deg me-1"></i>
                                    <?= htmlspecialchars($item['action_label'] ?: 'More Info') ?>
                                </a>
                            <?php endif; ?>
                            <div class="mt-1">
                                <span class="text-muted" style="font-size: 0.75rem;"><i class="bi bi-clock me-1"></i><?= date('M d, Y', strtotime($item['created_at'])) ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Members Section -->
    <div class="row mb-5">
        <div class="col-12 text-center mb-5">
            <h2 class="fw-bold display-6">Our Team</h2>
            <div class="header-line mx-auto" style="width: 60px; height: 4px; background: #4f46e5; border-radius: 2px;"></div>
        </div>

        <?php
        $leadership = [];
        $coreTeam = [];
        $executiveCouncil = [];
        $generalTeam = [];

        foreach ($members as $member) {
            $desc = strtolower($member['designation']);
            if (strpos($desc, 'president') !== false) {
                $leadership[] = $member;
            } elseif (strpos($desc, 'secretary') !== false || strpos($desc, 'treasurer') !== false) {
                $coreTeam[] = $member;
            } elseif (strpos($desc, 'director') !== false || strpos($desc, 'executive') !== false || strpos($desc, 'head') !== false || strpos($desc, 'coordinator') !== false) {
                $executiveCouncil[] = $member;
            } else {
                $generalTeam[] = $member;
            }
        }

        $sections = [
            ['title' => 'Leadership', 'members' => $leadership, 'col' => 'col-lg-4 col-md-6'],
            ['title' => 'Core Office Bearers', 'members' => $coreTeam, 'col' => 'col-lg-3 col-md-4'],
            ['title' => 'Executive Council', 'members' => $executiveCouncil, 'col' => 'col-lg-3 col-md-4'],
            ['title' => 'Team Members', 'members' => $generalTeam, 'col' => 'col-lg-3 col-md-4']
        ];

        foreach ($sections as $section):
            if (empty($section['members'])) continue;
        ?>
            <div class="col-12 mb-4 mt-2">
                <h4 class="fw-bold text-uppercase letter-spacing-1 text-muted small border-bottom pb-2 mb-4"><?= $section['title'] ?></h4>
            </div>
            
            <div class="row g-4 justify-content-center mb-5">
                <?php foreach ($section['members'] as $member): ?>
                    <div class="<?= $section['col'] ?>">
                        <div class="card h-100 border-0 shadow-sm transition-hover text-center p-3 rounded-4">
                            <div class="member-img-wrapper mb-3 mx-auto">
                                <img src="<?= $member['picture_path'] ?? '/assets/images/default-avatar.png' ?>" class="rounded-circle shadow-sm" alt="Member" style="width: 120px; height: 120px; object-fit: cover; border: 4px solid #f8f9fa;">
                            </div>
                            <h5 class="fw-bold mb-1" style="font-size: 1.1rem;"><?= htmlspecialchars($member['name']) ?></h5>
                            <p class="text-primary small mb-0 fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;"><?= htmlspecialchars($member['designation']) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
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
