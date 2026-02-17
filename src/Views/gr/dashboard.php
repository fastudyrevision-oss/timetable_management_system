<?php
// src/Views/gr/dashboard.php
require '../src/Views/layouts/header.php';
?>

<div class="row">
    <div class="col-md-12">
        <h2>Batch Timetable (GR View)</h2>

        <div class="card mt-4">
            <div class="card-body overflow-auto">
                <div class="timetable-grid">
                    <div class="timetable-header">Time</div>
                    <?php foreach ($days as $day): ?>
                        <div class="timetable-header">
                            <?= $day ?>
                        </div>
                    <?php endforeach; ?>

                    <?php foreach ($timeSlots as $slot): ?>
                        <div class="timetable-slot text-center fw-bold bg-light">
                            <?= $slot ?>
                        </div>
                        <?php foreach ($days as $day): ?>
                            <div class="timetable-slot">
                                <?php if (isset($grid[$day][$slot])): ?>
                                    <?php foreach ($grid[$day][$slot] as $class): ?>
                                        <div class="class-card <?= $class['status'] ?? '' ?>">
                                            <strong>
                                                <?= htmlspecialchars($class['subject_name'] ?? 'SUB') ?>
                                            </strong>
                                            <div class="badge bg-info text-dark">
                                                <?= htmlspecialchars($class['section_name']) ?>
                                            </div><br>
                                            <span class="text-muted">
                                                <?= htmlspecialchars($class['teacher_name'] ?? 'TBA') ?>
                                            </span><br>
                                            <small>
                                                <?= htmlspecialchars($class['room_name'] ?? 'TBA') ?>
                                            </small>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require '../src/Views/layouts/footer.php'; ?>