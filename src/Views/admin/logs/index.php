<?php
// src/Views/admin/logs/index.php
require '../src/Views/layouts/header.php';
?>

<div class="mb-4">
    <h2 class="fw-bold">Audit Logs</h2>
    <p class="text-muted">Tracking all system activities for security and auditing purposes.</p>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">Timestamp</th>
                        <th>Action</th>
                        <th>User</th>
                        <th>Details</th>
                        <th class="pe-4">IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $log): ?>
                    <tr>
                        <td class="ps-4 small"><?= date('M d, H:i:s', strtotime($log['created_at'])) ?></td>
                        <td>
                            <?php 
                                $color = 'text-dark';
                                if(stripos($log['action'], 'SUCCESS') !== false) $color = 'text-success';
                                if(stripos($log['action'], 'FAILED') !== false) $color = 'text-danger';
                                if(stripos($log['action'], 'DELETE') !== false) $color = 'text-danger fw-bold';
                            ?>
                            <span class="<?= $color ?> small fw-bold"><?= $log['action'] ?></span>
                        </td>
                        <td><?= htmlspecialchars($log['username'] ?? 'System/Guest') ?></td>
                        <td class="small text-muted"><?= htmlspecialchars($log['details']) ?></td>
                        <td class="pe-4 small font-monospace"><?= htmlspecialchars($log['ip_address']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require '../src/Views/layouts/footer.php'; ?>
