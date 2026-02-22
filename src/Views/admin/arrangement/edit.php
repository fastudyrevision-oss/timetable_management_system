<?php
// src/Views/admin/arrangement/edit.php
require '../src/Views/layouts/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Edit Timetable Slot</h4>
            </div>
            <div class="card-body">
                <form action="/admin/arrangement/update" method="POST">
                    <input type="hidden" name="id" value="<?= $slot['id'] ?>">
                    <input type="hidden" name="batch_id" value="<?= $slot['batch_id'] ?>">
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Batch / Section</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($slot['batch_name'] ?? '') ?> - <?= htmlspecialchars($slot['section_name'] ?? '') ?>" readonly disabled>
                        </div>
                        <div class="col-md-6">
                             <label class="form-label fw-bold">Day</label>
                             <select name="day" class="form-select select2" required>
                                 <?php 
                                 $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                                 foreach($days as $d): 
                                 ?>
                                    <option value="<?= $d ?>" <?= ($slot['day'] == $d) ? 'selected' : '' ?>><?= $d ?></option>
                                 <?php endforeach; ?>
                             </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Start Time</label>
                            <input type="time" name="start_time" class="form-control" value="<?= substr($s_start, 0, 5) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">End Time</label>
                            <input type="time" name="end_time" class="form-control" value="<?= substr($s_end, 0, 5) ?>" required>
                        </div>
                        <div class="form-text">Changing time will impact the time slot display.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Subject</label>
                        <select name="subject_id" class="form-select select2" required>
                            <option value="">-- Select Subject --</option>
                            <?php foreach($subjects as $subj): ?>
                                <option value="<?= $subj['id'] ?>" <?= ($slot['subject_id'] == $subj['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($subj['name']) ?> (<?= htmlspecialchars($subj['code']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Room</label>
                        <select name="room_id" class="form-select select2">
                            <option value="">-- No Room --</option>
                            <?php foreach($availableRooms as $room): ?>
                                <option value="<?= $room['id'] ?>" <?= ($slot['room_id'] == $room['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($room['name']) ?> (Cap: <?= $room['capacity'] ?>)
                                </option>
                            <?php endforeach; ?>
                            
                            <!-- If current room is not in available list (e.g. because it's assigned to THIS slot), add it manually if needed, 
                                 but usually backend logic should handle "available includes self" -->
                             <?php 
                                // Simple check if current room is missing from available list
                                $found = false;
                                foreach($availableRooms as $r) { if($r['id'] == $slot['room_id']) $found = true; }
                                if(!$found && $slot['room_id']):
                             ?>
                                <option value="<?= $slot['room_id'] ?>" selected>Current Room (ID: <?= $slot['room_id'] ?>)</option>
                             <?php endif; ?>
                        </select>
                        <div class="form-text text-muted">Only showing rooms available at the *original* time. If you change time, save and edit again to see new availability, or check manual availability first.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Teacher</label>
                        <select name="teacher_id" class="form-select select2">
                            <option value="">-- No Teacher --</option>
                            <?php foreach($availableTeachers as $teacher): ?>
                                <option value="<?= $teacher['id'] ?>" <?= ($slot['teacher_id'] == $teacher['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($teacher['name']) ?>
                                </option>
                            <?php endforeach; ?>
                             <?php 
                                $foundT = false;
                                foreach($availableTeachers as $t) { if($t['id'] == $slot['teacher_id']) $foundT = true; }
                                if(!$foundT && $slot['teacher_id']):
                             ?>
                                <option value="<?= $slot['teacher_id'] ?>" selected>Current Teacher (ID: <?= $slot['teacher_id'] ?>)</option>
                             <?php endif; ?>
                        </select>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="/admin/timetable" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Slot</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require '../src/Views/layouts/footer.php'; ?>
