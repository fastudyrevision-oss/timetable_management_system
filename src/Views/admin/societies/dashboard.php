<?php
// src/Views/admin/societies/dashboard.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>President Dashboard - <?= htmlspecialchars($society['name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4f46e5;
            --secondary-color: #818cf8;
            --bg-light: #f9fafb;
        }
        body { background-color: var(--bg-light); font-family: 'Inter', sans-serif; }
        .sidebar { min-height: 100vh; background: #1e1b4b; color: white; }
        .nav-link { color: rgba(255,255,255,0.7); transition: all 0.3s; }
        .nav-link:hover, .nav-link.active { color: white; background: rgba(255,255,255,0.1); }
        .stat-card { border: none; border-radius: 16px; transition: transform 0.3s; }
        .stat-card:hover { transform: translateY(-5px); }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 d-md-block sidebar collapse p-0">
                <div class="p-4 text-center">
                    <img src="<?= $society['president_picture'] ?? '/assets/images/default-avatar.png' ?>" class="rounded-circle border border-white border-opacity-25 p-1 mb-3" style="width: 80px; height: 80px; object-fit: cover;">
                    <h5 class="fw-bold mb-0 text-white"><?= htmlspecialchars($society['name']) ?></h5>
                    <p class="small text-white-50 mb-0">President: <?= htmlspecialchars($society['president_name'] ?? 'TBA') ?></p>
                    <hr class="text-white-50">
                    <ul class="nav flex-column gap-2 mt-4">
                        <li class="nav-item"><a href="#" class="nav-link active rounded-3 p-3"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a></li>
                        <li class="nav-item"><a href="/" class="nav-link rounded-3 p-3"><i class="bi bi-house me-2"></i> View Site</a></li>
                        <li class="nav-item"><a href="/logout" class="nav-link rounded-3 p-3 text-danger"><i class="bi bi-box-arrow-left me-2"></i> Logout</a></li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 p-0">
                <div class="dashboard-banner py-5 px-4 mb-5 text-white position-relative overflow-hidden" 
                     style="background: linear-gradient(rgba(79, 70, 229, 0.9), rgba(129, 140, 248, 0.95)), url('<?= htmlspecialchars($society['president_picture'] ?? '/assets/images/default-avatar.png') ?>'); background-size: cover; background-position: center; min-height: 200px; display: flex; align-items: center; border-radius: 0 0 24px 24px;">
                    <div class="position-relative" style="z-index: 2;">
                        <h1 class="display-5 fw-bold mb-2">Welcome Back, <?= htmlspecialchars($society['president_name'] ?? 'President') ?></h1>
                        <p class="lead opacity-75 mb-0">Manage your society's vision, team, and events from this command center.</p>
                    </div>
                </div>

                <div class="px-md-4">

                <!-- Stats -->
                <div class="row g-4 mb-5">
                    <div class="col-md-4">
                        <div class="stat-card card bg-primary text-white p-4 shadow-sm">
                            <h3 class="fw-bold mb-1"><?= count($members) ?></h3>
                            <p class="mb-0 opacity-75">Total Members</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card card bg-success text-white p-4 shadow-sm">
                            <h3 class="fw-bold mb-1"><?= count($events) ?></h3>
                            <p class="mb-0 opacity-75">Events Scheduled</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card card bg-info text-white p-4 shadow-sm">
                            <h3 class="fw-bold mb-1"><?= count($news) ?></h3>
                            <p class="mb-0 opacity-75">News Updates</p>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <!-- Members Section -->
                    <div class="col-xl-8">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                                <h5 class="fw-bold mb-0">Manage Members</h5>
                                <button class="btn btn-primary btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#addMemberModal">Add Member</button>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="ps-4">Member</th>
                                                <th>Designation</th>
                                                <th class="text-end pe-4">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($members as $member): ?>
                                            <tr>
                                                <td class="ps-4">
                                                    <div class="d-flex align-items-center">
                                                        <img src="<?= $member['picture_path'] ?? '/assets/images/default-avatar.png' ?>" class="rounded-circle me-3" style="width: 40px; height: 40px; object-fit: cover;">
                                                        <span class="fw-medium font-bold"><?= htmlspecialchars($member['name']) ?></span>
                                                    </div>
                                                </td>
                                                <td><span class="badge bg-light text-primary"><?= htmlspecialchars($member['designation']) ?></span></td>
                                                <td class="text-end pe-4">
                                                    <!-- Edit/Delete placeholders -->
                                                    <button class="btn btn-sm btn-outline-primary rounded-circle edit-member-btn" data-id="<?= $member['id'] ?>"><i class="bi bi-pencil"></i></button>
                                                    <button class="btn btn-sm btn-outline-danger rounded-circle"><i class="bi bi-trash"></i></button>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar Section: News & Events -->
                    <div class="col-xl-4">
                        <div class="card border-0 shadow-sm rounded-4 mb-4">
                            <div class="card-header bg-white py-3">
                                <h5 class="fw-bold mb-0">Post Update</h5>
                            </div>
                            <div class="card-body">
                                <button class="btn btn-outline-primary w-100 mb-2" data-bs-toggle="modal" data-bs-target="#addEventModal">Schedule Event</button>
                                <button class="btn btn-outline-secondary w-100" data-bs-toggle="modal" data-bs-target="#addNewsModal">Post News</button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Modals -->
    <div class="modal fade" id="addMemberModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="/society/member/add" method="POST" enctype="multipart/form-data" class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Add Society Member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Designation</label>
                        <input type="text" name="designation" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Profile Picture</label>
                        <input type="file" name="member_pic" class="form-control" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary px-4 rounded-pill">Save Member</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Member Modal -->
    <div class="modal fade" id="editMemberModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="/society/member/update" method="POST" enctype="multipart/form-data" class="modal-content border-0 shadow">
                <input type="hidden" name="id" id="edit_member_id">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-primary">Edit Society Member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4 text-center">
                        <img id="edit_member_preview" src="" class="rounded-circle border p-1" style="width: 100px; height: 100px; object-fit: cover; display: none;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Full Name</label>
                        <input type="text" name="name" id="edit_member_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Designation</label>
                        <input type="text" name="designation" id="edit_member_designation" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Update Picture (Leave empty to keep current)</label>
                        <input type="file" name="member_pic" class="form-control" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary px-5 rounded-pill shadow-sm">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="addEventModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="/society/event/add" method="POST" enctype="multipart/form-data" class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Schedule Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Event Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date & Time</label>
                        <input type="datetime-local" name="event_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Event Poster</label>
                        <input type="file" name="poster" class="form-control" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary px-4 rounded-pill">Publish Event</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="addNewsModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="/society/news/add" method="POST" class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Post News</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Content</label>
                        <textarea name="content" class="form-control" rows="5" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary px-4 rounded-pill">Post News</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.edit-member-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                fetch(`/society/member/edit/${id}`)
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById('edit_member_id').value = data.id;
                        document.getElementById('edit_member_name').value = data.name;
                        document.getElementById('edit_member_designation').value = data.designation;
                        
                        const preview = document.getElementById('edit_member_preview');
                        if (data.picture_path) {
                            preview.src = data.picture_path;
                            preview.style.display = 'inline-block';
                        } else {
                            preview.style.display = 'none';
                        }
                        
                        new bootstrap.Modal(document.getElementById('editMemberModal')).show();
                    });
            });
        });

        document.querySelectorAll('.btn-outline-danger').forEach(btn => {
            const id = btn.closest('td').querySelector('.edit-member-btn').getAttribute('data-id');
            btn.onclick = function() {
                if (confirm('Are you sure you want to remove this member?')) {
                    window.location.href = `/society/member/delete/${id}`;
                }
            };
        });
    </script>
</body>
</html>
