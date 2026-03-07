<?php
// src/Views/public/timetable.php
require '../src/Views/layouts/header.php';
?>
</div> <!-- Close header.php's .container to start fluid layout -->

<style>
    body { background-color: #f4f6f9; }
    .grid-container { overflow-x: auto; }
    .grid-table th, .grid-table td { vertical-align: top; min-width: 200px; }
    .class-card {
        border-left: 4px solid #007bff;
        background: #fff;
        padding: 8px;
        margin-bottom: 5px;
        border-radius: 4px;
        font-size: 0.85rem;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    .class-card.conflict { border-left-color: #dc3545; background: #fff5f5; }
    .class-card.cancelled { border-left-color: #6c757d; background: #e9ecef; opacity: 0.7; }
    .time-badge { font-weight: bold; font-size: 0.8em; color: #555; background: #e2e6ea; padding: 2px 4px; border-radius: 3px; }
    .meta-line { display: block; color: #666; font-size: 0.8em; margin-top: 2px; }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            
            <!-- Filters Section -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="GET" action="/timetable">
                        <!-- Row 1: Search and Semester -->
                        <div class="row g-3 align-items-end mb-4">
                            <div class="col-md-3">
                                <label class="form-label fw-bold small text-muted text-uppercase mb-2">Subject Search</label>
                                <input type="text" name="subject_search" class="form-control" placeholder="Search subject..." value="<?= htmlspecialchars($_GET['subject_search'] ?? '') ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold small text-muted text-uppercase mb-2">Teacher Search</label>
                                <input type="text" name="teacher_search" class="form-control" placeholder="Search teacher..." value="<?= htmlspecialchars($_GET['teacher_search'] ?? '') ?>">
                            </div>
                            <div class="col-md-6">
                                 <label class="form-label fw-bold small text-muted text-uppercase mb-2">Select Semester</label>
                                 <select name="semester_id" class="form-select select2" data-placeholder="All Semesters">
                                     <option value=""></option>
                                     <?php foreach($semesters as $sem): ?>
                                        <option value="<?= $sem['id'] ?>" <?= (isset($_GET['semester_id']) && $_GET['semester_id'] == $sem['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($sem['batch_name']) ?> - Sem <?= $sem['number'] ?>
                                        </option>
                                     <?php endforeach; ?>
                                 </select>
                            </div>
                        </div>

                        <!-- Row 2: Batch, Section and Buttons -->
                        <div class="row g-3 align-items-end">
                            <div class="col-md-5">
                                 <label class="form-label fw-bold small text-muted text-uppercase mb-2">Select Batch</label>
                                 <select name="batch_id" class="form-select select2" data-placeholder="All Batches" onchange="this.form.submit()">
                                     <option value=""></option>
                                     <?php foreach($batches as $b): ?>
                                        <option value="<?= $b['id'] ?>" <?= (isset($_GET['batch_id']) && $_GET['batch_id'] == $b['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($b['name']) ?>
                                        </option>
                                     <?php endforeach; ?>
                                 </select>
                            </div>
                            <div class="col-md-5">
                                 <label class="form-label fw-bold small text-muted text-uppercase mb-2">Select Section</label>
                                 <select name="section_id" class="form-select select2" data-placeholder="All Sections">
                                     <option value=""></option>
                                     <?php foreach($sections as $s): ?>
                                        <?php if(empty($_GET['batch_id']) || $_GET['batch_id'] == $s['batch_id']): ?>
                                            <option value="<?= $s['id'] ?>" <?= (isset($_GET['section_id']) && $_GET['section_id'] == $s['id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($s['batch_name']) ?> - <?= htmlspecialchars($s['name']) ?>
                                            </option>
                                        <?php endif; ?>
                                     <?php endforeach; ?>
                                 </select>
                            </div>
                            <div class="col-md-2 d-flex gap-2">
                                <button type="submit" class="btn btn-primary flex-grow-1 fw-bold">Apply</button>
                                <a href="/timetable" class="btn btn-outline-secondary px-3" title="Reset Filters"><i class="bi bi-arrow-counterclockwise"></i></a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Timetable Matrix -->
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <span>Full Timetable</span>
                </div>
                <div class="card-body grid-container p-0">
                    <table class="table table-bordered table-striped mb-0 grid-table">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 100px;">Day</th>
                                <?php foreach ($allRooms as $room): ?>
                                    <th><?= htmlspecialchars($room['name']) ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($days as $day): ?>
                                <tr>
                                    <td class="fw-bold bg-light align-middle"><?= $day ?></td>
                                    
                                    <?php foreach ($allRooms as $room): ?>
                                        <td>
                                            <?php 
                                                $rid = $room['id'];
                                                if (isset($matrix[$day][$rid])) {
                                                    // Sort by time
                                                    usort($matrix[$day][$rid], fn($a, $b) => strcmp($a['time_slot'], $b['time_slot']));
                                                    
                                                    foreach ($matrix[$day][$rid] as $cls) {
                                                        ?>
                                                        <div class="class-card <?= $cls['status'] ?>">
                                                            <span class="time-badge"><?= htmlspecialchars($cls['time_slot']) ?></span>
                                                            <div class="fw-bold mb-1"><?= htmlspecialchars($cls['subject_name']) ?></div>
                                                            <div class="meta-line">
                                                                <i class="bi bi-person"></i> <?= htmlspecialchars($cls['teacher_name']) ?>
                                                            </div>
                                                            <div class="meta-line text-muted small">
                                                                <?= htmlspecialchars($cls['batch_name']) ?> | Sem <?= htmlspecialchars($cls['semester_num']) ?> | 
                                                                <?= htmlspecialchars($cls['section_name']) ?>
                                                            </div>
                                                            <?php if($cls['status'] == 'cancelled'): ?>
                                                                <div class="text-danger fw-bold small text-center">CANCELLED</div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <?php
                                                    }
                                                }
                                            ?>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.select2').each(function() {
            $(this).select2({
                placeholder: $(this).data('placeholder'),
                allowClear: true,
                width: '100%'
            });
        });
    });
</script>
</body>
</html>
