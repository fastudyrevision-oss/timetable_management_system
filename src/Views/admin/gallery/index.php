<?php
// src/Views/admin/gallery/index.php
require __DIR__ . '/../../layouts/header.php';
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Manage Gallery</h2>
            <p class="text-muted small">Update numbering or edit item details.</p>
        </div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addItemModal">
            <i class="bi bi-plus-circle me-2"></i>Add Media
        </button>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Media added successfully!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['updated'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Media updated successfully!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['ordered'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Display order updated!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['deleted'])): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            Media deleted successfully.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <form action="/admin/gallery/update-order" method="POST">
        <div class="row g-4 mb-4">
            <?php if (empty($items)): ?>
                <div class="col-12 text-center py-5">
                    <p class="text-muted">No gallery items found.</p>
                </div>
            <?php else: ?>
                <?php foreach ($items as $item): ?>
                    <div class="col-md-3 col-sm-6">
                        <div class="card h-100 shadow-sm border-0 position-relative">
                            <div class="ratio ratio-16x9 bg-light rounded-top overflow-hidden">
                                <?php if ($item['media_type'] === 'image'): ?>
                                    <img src="<?= htmlspecialchars($item['media_path']) ?>" class="card-img-top object-fit-cover" alt="<?= htmlspecialchars($item['title']) ?>">
                                <?php else: ?>
                                    <div class="d-flex align-items-center justify-content-center bg-dark">
                                        <i class="bi bi-play-circle-fill display-6 text-white"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="card-body p-2">
                                <div class="mb-2">
                                    <label class="small text-muted mb-0">Order #</label>
                                    <input type="number" name="orders[<?= $item['id'] ?>]" value="<?= $item['display_order'] ?>" class="form-control form-control-sm" style="width: 70px;">
                                </div>
                                <h6 class="card-title text-truncate small mb-1" title="<?= htmlspecialchars($item['title']) ?>"><?= htmlspecialchars($item['title']) ?></h6>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-secondary" style="font-size: 0.65rem;"><?= ucfirst($item['category']) ?></span>
                                    <div>
                                        <button type="button" class="btn btn-sm text-primary p-0 me-2" 
                                                onclick="editItem(<?= htmlspecialchars(json_encode($item)) ?>)">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <a href="/admin/gallery/delete/<?= $item['id'] ?>" class="btn btn-sm text-danger p-0" onclick="return confirm('Delete this item?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <?php if (!empty($items)): ?>
            <div class="sticky-bottom bg-white py-3 border-top text-end">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-save me-2"></i>Save Numbering
                </button>
            </div>
        <?php endif; ?>
    </form>
</div>

<!-- Add Item Modal -->
<div class="modal fade" id="addItemModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/admin/gallery/add" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Add Gallery Media</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Event/Department Title</label>
                        <input type="text" name="title" class="form-control" required placeholder="Name applied to all selected files">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-select" required>
                            <option value="event">Event</option>
                            <option value="department">Department</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Media Type</label>
                        <select name="media_type" class="form-select" required>
                            <option value="image">Images</option>
                            <option value="video">Videos</option>
                        </select>
                        <small class="text-muted">Select matching type for all files in this upload.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Start Numbering From</label>
                        <input type="number" name="display_order" class="form-control" value="0">
                        <small class="text-muted">Used as base order for these items.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Select Files (Multiple Allowed)</label>
                        <input type="file" name="media_files[]" class="form-control" required multiple accept="image/*,video/mp4">
                        <small class="text-muted">You can select multiple images or videos at once.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Upload Group</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Item Modal -->
<div class="modal fade" id="editItemModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/admin/gallery/update" method="POST">
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Gallery Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" id="edit_title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select name="category" id="edit_category" class="form-select" required>
                            <option value="event">Event</option>
                            <option value="department">Department</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Media Type</label>
                        <select name="media_type" id="edit_media_type" class="form-select" required>
                            <option value="image">Image</option>
                            <option value="video">Video</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Display Order</label>
                        <input type="number" name="display_order" id="edit_display_order" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editItem(item) {
    document.getElementById('edit_id').value = item.id;
    document.getElementById('edit_title').value = item.title;
    document.getElementById('edit_category').value = item.category;
    document.getElementById('edit_media_type').value = item.media_type;
    document.getElementById('edit_display_order').value = item.display_order;
    
    var modal = new bootstrap.Modal(document.getElementById('editItemModal'));
    modal.show();
}
</script>

<?php require __DIR__ . '/../../layouts/footer.php'; ?>
