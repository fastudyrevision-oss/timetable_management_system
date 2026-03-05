<?php
// src/Views/admin/teachers/edit.php
require '../src/Views/layouts/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Edit Teacher</h4>
            </div>
            <div class="card-body">
                <form action="/admin/teachers/update" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $teacher['id'] ?>">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Name</label>
                        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($teacher['name']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($teacher['email'] ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Post/Title</label>
                        <input type="text" name="post" class="form-control" value="<?= htmlspecialchars($teacher['post'] ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Current Profile Picture</label>
                        <?php if (!empty($teacher['picture'])): ?>
                            <div class="mb-2">
                                <img src="<?= htmlspecialchars($teacher['picture']) ?>" alt="Picture" class="img-thumbnail" style="max-height: 100px;">
                            </div>
                        <?php endif; ?>
                        <input type="file" name="picture" class="form-control" accept="image/*">
                        <small class="text-muted">Leave blank to keep current picture.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Qualification</label>
                        <textarea name="qualification" class="form-control" rows="3"><?= htmlspecialchars($teacher['qualification'] ?? '') ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Research Interest</label>
                        <textarea name="research_interest" class="form-control" rows="3"><?= htmlspecialchars($teacher['research_interest'] ?? '') ?></textarea>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="/admin/teachers" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Teacher</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require '../src/Views/layouts/footer.php'; ?>
