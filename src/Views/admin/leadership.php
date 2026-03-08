<?php
// src/Views/admin/leadership.php
require '../src/Views/layouts/header.php';

$head_cr = null;
$gr = null;
foreach ($leaders as $l) {
    if ($l['role'] === 'head_cr') $head_cr = $l;
    if ($l['role'] === 'gr') $gr = $l;
}
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Student Leadership Management</h2>
        <a href="/admin/dashboard" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>Back to Dashboard</a>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>Leadership details updated successfully!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Head CR Management -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-primary text-white p-4 border-0">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-person-badge-fill me-2"></i>Update Head CR Info</h5>
                </div>
                <div class="card-body p-4">
                    <form action="/admin/leadership/update" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="role" value="head_cr">
                        
                        <div class="mb-3 text-center">
                            <img src="<?= $head_cr['picture'] ?? '/assets/images/default-avatar.png' ?>" class="rounded-circle mb-3 border shadow-sm" style="width: 120px; height: 120px; object-fit: cover;">
                            <div class="input-group input-group-sm mb-3" style="max-width: 300px; margin: 0 auto;">
                                <input type="file" class="form-control" name="picture" id="head_cr_pic">
                                <label class="input-group-text" for="head_cr_pic">Change Picture</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small">Full Name</label>
                            <input type="text" class="form-control rounded-pill" name="name" value="<?= htmlspecialchars($head_cr['name'] ?? '') ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Official Email</label>
                            <input type="email" class="form-control rounded-pill" name="email" value="<?= htmlspecialchars($head_cr['email'] ?? '') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small">LinkedIn URL</label>
                            <input type="url" class="form-control rounded-pill" name="linkedin_url" value="<?= htmlspecialchars($head_cr['linkedin_url'] ?? '') ?>" placeholder="https://linkedin.com/in/...">
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small">Brief Description/Bios</label>
                            <textarea class="form-control" name="description" rows="4" required><?= htmlspecialchars($head_cr['description'] ?? '') ?></textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary rounded-pill py-2">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- GR Management -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-danger text-white p-4 border-0">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-person-badge-fill me-2"></i>Update GR Info</h5>
                </div>
                <div class="card-body p-4">
                    <form action="/admin/leadership/update" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="role" value="gr">
                        
                        <div class="mb-3 text-center">
                            <img src="<?= $gr['picture'] ?? '/assets/images/default-avatar.png' ?>" class="rounded-circle mb-3 border shadow-sm" style="width: 120px; height: 120px; object-fit: cover;">
                            <div class="input-group input-group-sm mb-3" style="max-width: 300px; margin: 0 auto;">
                                <input type="file" class="form-control" name="picture" id="gr_pic">
                                <label class="input-group-text" for="gr_pic">Change Picture</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small">Full Name</label>
                            <input type="text" class="form-control rounded-pill" name="name" value="<?= htmlspecialchars($gr['name'] ?? '') ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Official Email</label>
                            <input type="email" class="form-control rounded-pill" name="email" value="<?= htmlspecialchars($gr['email'] ?? '') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small">LinkedIn URL</label>
                            <input type="url" class="form-control rounded-pill" name="linkedin_url" value="<?= htmlspecialchars($gr['linkedin_url'] ?? '') ?>" placeholder="https://linkedin.com/in/...">
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small">Brief Description/Bios</label>
                            <textarea class="form-control" name="description" rows="4" required><?= htmlspecialchars($gr['description'] ?? '') ?></textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-danger rounded-pill py-2">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require '../src/Views/layouts/footer.php'; ?>
