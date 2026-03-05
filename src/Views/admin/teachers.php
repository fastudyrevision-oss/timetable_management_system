<?php
// src/Views/admin/teachers.php
require '../src/Views/layouts/header.php';
?>

<div class="row">
    <div class="col-md-12">
        <h2>Manage Teachers</h2>

        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['flash_message'];
                unset($_SESSION['flash_message']); ?>
            </div>
        <?php endif; ?>

        <div class="card mb-4">
            <div class="card-header">Add New Teacher</div>
            <div class="card-body">
                <form action="/admin/teachers/create" method="POST" enctype="multipart/form-data">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Name *</label>
                            <input type="text" name="name" id="teacherName" class="form-control" placeholder="Teacher Name" required>
                            <div id="nameFeedback" class="form-text"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Email (Optional)">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Post/Title</label>
                            <input type="text" name="post" class="form-control" placeholder="e.g., Assistant Professor">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Profile Picture</label>
                            <input type="file" name="picture" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Qualification</label>
                            <textarea name="qualification" class="form-control" rows="2" placeholder="Degrees, Certifications"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Research Interest</label>
                            <textarea name="research_interest" class="form-control" rows="2" placeholder="Fields of study"></textarea>
                        </div>
                        <div class="col-12 mt-3 text-end">
                            <button type="submit" class="btn btn-success px-4">Add Teacher</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" id="facultySearch" class="form-control border-start-0" placeholder="Search by name or email...">
                </div>
            </div>
        </div>

        <table class="table table-bordered" id="facultyTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($teachers as $teacher): ?>
                    <tr>
                        <td>
                            <?= $teacher['id'] ?>
                        </td>
                        <td>
                            <?= htmlspecialchars($teacher['name']) ?>
                        </td>
                        <td>
                            <?= htmlspecialchars($teacher['email']) ?>
                        </td>
                        <td>
                            <a href="/admin/teachers/edit/<?= $teacher['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                            <a href="/admin/teachers/delete/<?= $teacher['id'] ?>" class="btn btn-sm btn-danger ms-1" onclick="return confirm('Are you sure you want to remove this teacher from the Faculty page? (This will not delete them from the timetable).');">Remove</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('teacherName');
    const feedback = document.getElementById('nameFeedback');

    let timeout = null;
    nameInput.addEventListener('input', function() {
        clearTimeout(timeout);
        const name = this.value.trim();
        if (name.length < 2) {
            feedback.innerHTML = '';
            return;
        }

        timeout = setTimeout(() => {
            fetch('/admin/teachers/check?name=' + encodeURIComponent(name))
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        let statusText = data.is_faculty ? 'already a Faculty Member' : 'exists in the Timetable database';
                        feedback.innerHTML = `
                            <div class="alert alert-warning py-2 px-3 mt-2 mb-0 d-flex align-items-center justify-content-between">
                                <span>
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i> 
                                    <strong>${name}</strong> ${statusText}.
                                </span>
                                <div>
                                    <a href="/admin/teachers/edit/${data.id}" class="btn btn-sm btn-primary py-0">Edit</a>
                                    <a href="/admin/teachers/delete/${data.id}" class="btn btn-sm btn-danger py-0 ms-1" onclick="return confirm('Are you sure you want to remove this teacher?');">Remove</a>
                                </div>
                            </div>`;
                    } else {
                        feedback.innerHTML = '<div class="text-success mt-1 small"><i class="bi bi-check-circle-fill me-1"></i> This name is available to add.</div>';
                    }
                })
                .catch(error => console.error('Error checking name:', error));
        }, 500);
    });

    // Faculty Search Filter
    const searchInput = document.getElementById('facultySearch');
    const tableRows = document.querySelectorAll('#facultyTable tbody tr');

    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();

        tableRows.forEach(row => {
            const name = row.cells[1].textContent.toLowerCase();
            const email = row.cells[2].textContent.toLowerCase();
            
            if (name.includes(searchTerm) || email.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
});
</script>

<?php require '../src/Views/layouts/footer.php'; ?>