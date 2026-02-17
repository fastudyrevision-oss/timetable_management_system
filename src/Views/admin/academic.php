<?php
// src/Views/admin/academic.php
require '../src/Views/layouts/header.php';
?>

<div class="row">
    <div class="col-md-12">
        <h2>Academic Management</h2>
        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['flash_message'];
                unset($_SESSION['flash_message']); ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="row mt-4">
    <!-- Batches -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">Batches</div>
            <div class="card-body">
                <form action="/admin/academic/create-batch" method="POST" class="mb-3">
                    <div class="input-group">
                        <input type="text" name="name" class="form-control" placeholder="Batch Name (e.g. IT-2022)"
                            required>
                        <button class="btn btn-primary" type="submit">Add</button>
                    </div>
                </form>
                <ul class="list-group">
                    <?php foreach ($batches as $batch): ?>
                        <li class="list-group-item">
                            <?= htmlspecialchars($batch['name']) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>

    <!-- Sections -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">Sections</div>
            <div class="card-body">
                <form action="/admin/academic/create-section" method="POST" class="mb-3">
                    <div class="row g-2">
                        <div class="col-4">
                            <select name="batch_id" class="form-select" required>
                                <option value="">Batch</option>
                                <?php foreach ($batches as $batch): ?>
                                    <option value="<?= $batch['id'] ?>">
                                        <?= htmlspecialchars($batch['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-3">
                            <input type="text" name="name" class="form-control" placeholder="Name (A)" required>
                        </div>
                        <div class="col-3">
                            <select name="type" class="form-select">
                                <option value="regular">Reg</option>
                                <option value="self_support">Self</option>
                            </select>
                        </div>
                        <div class="col-2">
                            <button type="submit" class="btn btn-primary w-100">Add</button>
                        </div>
                    </div>
                </form>
                <ul class="list-group">
                    <?php foreach ($sections as $section): ?>
                        <li class="list-group-item">
                            <?= htmlspecialchars($section['batch_name']) ?> -
                            <?= htmlspecialchars($section['name']) ?> (
                            <?= ucfirst($section['type']) ?>)
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Semesters -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">Semesters</div>
            <div class="card-body">
                <form action="/admin/academic/create-semester" method="POST" class="mb-3">
                    <div class="row g-2">
                        <div class="col-6">
                            <select name="batch_id" class="form-select" required>
                                <option value="">Batch</option>
                                <?php foreach ($batches as $batch): ?>
                                    <option value="<?= $batch['id'] ?>">
                                        <?= htmlspecialchars($batch['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-4">
                            <input type="number" name="number" class="form-control" placeholder="Sem No." min="1"
                                max="8" required>
                        </div>
                        <div class="col-2">
                            <button type="submit" class="btn btn-primary w-100">Add</button>
                        </div>
                    </div>
                </form>
                <ul class="list-group list-group-flush">
                    <?php foreach ($semesters as $sem): ?>
                        <li class="list-group-item">
                            <?= htmlspecialchars($sem['batch_name']) ?> - Semester
                            <?= $sem['number'] ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>

    <!-- Subjects -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">Subjects</div>
            <div class="card-body">
                <form action="/admin/academic/create-subject" method="POST" class="mb-3">
                    <div class="row g-2">
                        <div class="col-5">
                            <select name="semester_id" class="form-select" required>
                                <option value="">Semester</option>
                                <?php foreach ($semesters as $sem): ?>
                                    <option value="<?= $sem['id'] ?>">
                                        <?= htmlspecialchars($sem['batch_name']) ?> - Sem
                                        <?= $sem['number'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-3">
                            <input type="text" name="name" class="form-control" placeholder="Subject" required>
                        </div>
                        <div class="col-2">
                            <input type="text" name="code" class="form-control" placeholder="Code">
                        </div>
                        <div class="col-2">
                            <button type="submit" class="btn btn-primary w-100">Add</button>
                        </div>
                    </div>
                </form>
                <ul class="list-group">
                    <?php foreach ($subjects as $sub): ?>
                        <li class="list-group-item">
                            [
                            <?= htmlspecialchars($sub['batch_name']) ?> S
                            <?= $sub['sem_number'] ?>]
                            <?= htmlspecialchars($sub['code']) ?> -
                            <?= htmlspecialchars($sub['name']) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php require '../src/Views/layouts/footer.php'; ?>