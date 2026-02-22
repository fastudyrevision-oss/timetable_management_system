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
                <form action="/admin/teachers/create" method="POST">
                    <div class="row">
                        <div class="col-md-5">
                            <input type="text" name="name" class="form-control" placeholder="Teacher Name" required>
                        </div>
                        <div class="col-md-5">
                            <input type="email" name="email" class="form-control" placeholder="Email (Optional)">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-success w-100">Add</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <table class="table table-bordered">
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
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require '../src/Views/layouts/footer.php'; ?>