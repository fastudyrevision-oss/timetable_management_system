<?php
// src/Views/admin/rooms.php
require '../src/Views/layouts/header.php';
?>

<div class="row">
    <div class="col-md-12">
        <h2>Manage Rooms</h2>

        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['flash_message'];
                unset($_SESSION['flash_message']); ?>
            </div>
        <?php endif; ?>

        <div class="card mb-4">
            <div class="card-header">Add New Room</div>
            <div class="card-body">
                <form action="/admin/rooms/create" method="POST">
                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" name="name" class="form-control" placeholder="Room Name (e.g., Lab 1)"
                                required>
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="capacity" class="form-control" placeholder="Capacity" required>
                        </div>
                        <div class="col-md-3">
                            <select name="type" class="form-select">
                                <option value="classroom">Classroom</option>
                                <option value="lab">Lab</option>
                            </select>
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
                    <th>Capacity</th>
                    <th>Type</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rooms as $room): ?>
                    <tr>
                        <td>
                            <?= $room['id'] ?>
                        </td>
                        <td>
                            <?= htmlspecialchars($room['name']) ?>
                        </td>
                        <td>
                            <?= $room['capacity'] ?>
                        </td>
                        <td>
                            <?= ucfirst($room['type']) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require '../src/Views/layouts/footer.php'; ?>