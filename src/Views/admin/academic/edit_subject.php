<?php
// src/Views/admin/academic/edit_subject.php
require '../src/Views/layouts/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Edit Subject</h4>
            </div>
            <div class="card-body">
                <form action="/admin/academic/subject/update" method="POST">
                    <input type="hidden" name="id" value="<?= $subject['id'] ?>">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Subject Name</label>
                        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($subject['name']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Subject Code (Optional)</label>
                        <input type="text" name="code" class="form-control" value="<?= htmlspecialchars($subject['code'] ?? '') ?>">
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="/admin/academic" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Subject</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require '../src/Views/layouts/footer.php'; ?>
