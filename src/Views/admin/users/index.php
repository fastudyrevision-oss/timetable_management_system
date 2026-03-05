<?php
// src/Views/admin/users/index.php
require '../src/Views/layouts/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">User Management</h2>
    <div class="d-flex gap-2">
        <a href="/admin/logs" class="btn btn-outline-secondary"><i class="bi bi-journal-text me-2"></i>Audit Logs</a>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Roll Number</th>
                        <th>User Info</th>
                        <th>Contact</th>
                        <th>Password (Hash)</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <img src="<?= $u['profile_picture'] ?? '/assets/images/default-avatar.png' ?>" class="rounded-circle me-3" style="width: 35px; height: 35px; object-fit: cover; border: 1px solid #dee2e6;">
                                <span class="badge bg-light text-dark fw-bold"><?= htmlspecialchars($u['roll_number'] ?? 'N/A') ?></span>
                            </div>
                        </td>
                        <td>
                            <div class="fw-bold"><?= htmlspecialchars($u['username']) ?></div>
                        </td>
                        <td class="small">
                            <div><i class="bi bi-envelope me-1"></i><?= htmlspecialchars($u['email'] ?? 'N/A') ?></div>
                            <div><i class="bi bi-telephone me-1"></i><?= htmlspecialchars($u['phone_number'] ?? 'N/A') ?></div>
                        </td>
                        <td>
                            <code class="small text-truncate d-inline-block" style="max-width: 100px;" title="<?= $u['password'] ?>"><?= substr($u['password'], 0, 15) ?>...</code>
                        </td>
                        <td>
                            <?php 
                                $badgeClass = 'bg-secondary';
                                if($u['role'] === 'admin') $badgeClass = 'bg-danger';
                                if($u['role'] === 'president') $badgeClass = 'bg-primary';
                                if($u['role'] === 'cr' || $u['role'] === 'gr') $badgeClass = 'bg-success';
                            ?>
                            <span class="badge <?= $badgeClass ?>"><?= strtoupper($u['role']) ?></span>
                        </td>
                        <td>
                            <?php if ($u['is_approved']): ?>
                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Approved</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark"><i class="bi bi-clock me-1"></i>Pending</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group gap-1">
                                <?php if (!$u['is_approved']): ?>
                                    <a href="/admin/user/approve?id=<?= $u['id'] ?>" class="btn btn-sm btn-success" title="Approve Request"><i class="bi bi-person-check"></i></a>
                                    <a href="/admin/user/reject?id=<?= $u['id'] ?>" class="btn btn-sm btn-warning" title="Reject Request" onclick="return confirm('Are you sure you want to reject this signup request?')"><i class="bi bi-person-x"></i></a>
                                <?php endif; ?>
                                <a href="/admin/users/edit/<?= $u['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                <a href="/admin/users/delete/<?= $u['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure? This will permanently delete the user.')"><i class="bi bi-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require '../src/Views/layouts/footer.php'; ?>
