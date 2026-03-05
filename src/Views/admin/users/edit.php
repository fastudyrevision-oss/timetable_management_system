<?php
// src/Views/admin/users/edit.php
require '../src/Views/layouts/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white py-3 border-bottom">
                <h4 class="mb-0 fw-bold">Edit User: <?= htmlspecialchars($user['username']) ?></h4>
            </div>
            <div class="card-body p-4 text-center border-bottom bg-light">
                <img src="<?= $user['profile_picture'] ?? '/assets/images/default-avatar.png' ?>" class="rounded-circle border p-1 mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                <h5 class="mb-0 fw-bold"><?= htmlspecialchars($user['username']) ?></h5>
                <p class="text-muted small mb-0"><?= strtoupper($user['role']) ?></p>
            </div>
            <div class="card-body p-4">
                <form action="/admin/users/update" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $user['id'] ?>">
                    
                    <div class="row g-3">
                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold">Update Profile Picture</label>
                            <input type="file" name="profile_picture" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Username</label>
                            <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Roll Number</label>
                            <input type="text" name="roll_number" class="form-control" value="<?= htmlspecialchars($user['roll_number'] ?? '') ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Email Address</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Phone Number</label>
                            <input type="text" name="phone_number" class="form-control" value="<?= htmlspecialchars($user['phone_number'] ?? '') ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">New Password (leave blank to keep current)</label>
                            <input type="password" name="password" class="form-control" placeholder="Enter new password">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-medium">Current Password Hash (Read Only)</label>
                            <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($user['password']) ?>" readonly>
                        </div>
                        
                        <div class="col-md-12">
                            <label class="form-label fw-medium">Role</label>
                            <select name="role" class="form-select" id="roleSelector" required>
                                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                <option value="cr" <?= $user['role'] === 'cr' ? 'selected' : '' ?>>CR</option>
                                <option value="gr" <?= $user['role'] === 'gr' ? 'selected' : '' ?>>GR</option>
                                <option value="president" <?= $user['role'] === 'president' ? 'selected' : '' ?>>President</option>
                                <option value="student" <?= $user['role'] === 'student' ? 'selected' : '' ?>>Student</option>
                            </select>
                        </div>

                        <!-- Society Selection (for Presidents) -->
                        <div class="col-md-6" id="societyContainer" style="<?= $user['role'] !== 'president' ? 'display:none;' : '' ?>">
                            <label class="form-label fw-medium">Society</label>
                            <select name="society_id" class="form-select">
                                <option value="">Select Society...</option>
                                <?php foreach ($societies as $s): ?>
                                    <option value="<?= $s['id'] ?>" <?= $user['society_id'] == $s['id'] ? 'selected' : '' ?>><?= htmlspecialchars($s['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Batch Selection (for CR/GR) -->
                        <div class="col-md-6" id="batchContainer" style="<?= !in_array($user['role'], ['cr', 'gr']) ? 'display:none;' : '' ?>">
                            <label class="form-label fw-medium">Batch</label>
                            <select name="batch_id" class="form-select">
                                <option value="">Select Batch...</option>
                                <?php foreach ($batches as $b): ?>
                                    <option value="<?= $b['id'] ?>" <?= $user['batch_id'] == $b['id'] ? 'selected' : '' ?>><?= htmlspecialchars($b['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">Update User</button>
                        <a href="/admin/users" class="btn btn-outline-secondary px-4">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('roleSelector').addEventListener('change', function() {
    const role = this.value;
    document.getElementById('societyContainer').style.display = (role === 'president') ? 'block' : 'none';
    document.getElementById('batchContainer').style.display = (role === 'cr' || role === 'gr') ? 'block' : 'none';
});
</script>

<?php require '../src/Views/layouts/footer.php'; ?>
