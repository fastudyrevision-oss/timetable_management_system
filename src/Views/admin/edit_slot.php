<?php
// src/Views/admin/edit_slot.php
require '../src/Views/layouts/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Edit Slot #<?= $slot['id'] ?></div>
            <div class="card-body">
                <form action="/admin/arrangement/update" method="POST">
                    <input type="hidden" name="id" value="<?= $slot['id'] ?>">
                    <input type="hidden" name="batch_id" value="<?= $slot['batch_id'] ?>">

                    <div class="mb-3">
                        <label class="form-label">Day</label>
                        <select name="day" class="form-select">
                            <?php foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $d): ?>
                                <option value="<?= $d ?>" <?= $d == $slot['day'] ? 'selected' : '' ?>><?= $d ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Time Slot</label>
                        <select name="time_slot" class="form-select">
                            <?php foreach ([
                                '08:00 - 09:30',
                                '09:30 - 11:00',
                                '11:00 - 12:30',
                                '12:30 - 14:00',
                                '14:00 - 15:30',
                                '15:30 - 17:00'
                            ] as $t): ?>
                                <option value="<?= $t ?>" <?= $t == $slot['time_slot'] ? 'selected' : '' ?>><?= $t ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Room</label>
                        <select name="room_id" class="form-select">
                            <option value="">Select Room</option>
                            <?php foreach ($availableRooms as $r): ?>
                                <option value="<?= $r['id'] ?>" <?= $r['id'] == $slot['room_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($r['name']) ?> (<?= $r['capacity'] ?>)
                                </option>
                            <?php endforeach; ?>
                            <!-- Add current room if not in available list (because it is used by this slot) -->
                        </select>
                        <small class="text-muted">Only showing available rooms for the current slot time. Change time to
                            see others.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Teacher</label>
                        <select name="teacher_id" class="form-select">
                            <option value="">Select Teacher</option>
                            <?php foreach ($availableTeachers as $t): ?>
                                <option value="<?= $t['id'] ?>" <?= $t['id'] == $slot['teacher_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($t['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Arrangement</button>
                    <a href="/admin/timetable" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require '../src/Views/layouts/footer.php'; ?>