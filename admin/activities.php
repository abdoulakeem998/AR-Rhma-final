<?php
require_once '../config/database.php';
require_once '../includes/functions.php';
requireAdmin();

// Get base path
$base = defined('BASE_PATH') ? BASE_PATH : '/';
$admin_base = $base . 'admin/';

// Define upload directory - use absolute path
$upload_base_dir = $_SERVER['DOCUMENT_ROOT'] . $base . 'uploads/activities/';
$upload_url_path = 'uploads/activities/';

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    // Get image path before deleting
    $stmt = $pdo->prepare("SELECT image_url FROM activities WHERE id = ?");
    $stmt->execute([$id]);
    $activity = $stmt->fetch();
    
    // Delete from database
    $stmt = $pdo->prepare("DELETE FROM activities WHERE id = ?");
    if ($stmt->execute([$id])) {
        // Delete image file if exists
        if ($activity && $activity['image_url']) {
            $full_path = $_SERVER['DOCUMENT_ROOT'] . $base . $activity['image_url'];
            if (file_exists($full_path)) {
                unlink($full_path);
            }
        }
        
        logAdminActivity(getCurrentAdminId(), 'delete', 'activity', $id, 'Deleted activity');
        setFlashMessage('success', 'Activity deleted successfully');
        redirect($admin_base . 'activities.php');
    }
}

// Handle create/update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? 0;
    $title = cleanInput($_POST['title']);
    $description = cleanInput($_POST['description']);
    $date = $_POST['activity_date'];
    $location = cleanInput($_POST['location']);
    $beneficiaries = (int)$_POST['beneficiaries'];
    $category = $_POST['category'];
    $status = $_POST['status'];
    $featured = isset($_POST['featured']) ? 1 : 0;
    
    $image_url = '';
    $upload_error = '';
    
    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        $max_size = 5 * 1024 * 1024; // 5MB
        
        if (!in_array($_FILES['image']['type'], $allowed_types)) {
            $upload_error = 'Invalid file type. Only JPG, PNG, and GIF are allowed.';
        } elseif ($_FILES['image']['size'] > $max_size) {
            $upload_error = 'File too large. Maximum size is 5MB.';
        } else {
            // Create directory if it doesn't exist
            if (!file_exists($upload_base_dir)) {
                mkdir($upload_base_dir, 0777, true);
                chmod($upload_base_dir, 0777);
            }
            
            $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = 'activity_' . time() . '_' . uniqid() . '.' . $extension;
            $filepath = $upload_base_dir . $filename;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $filepath)) {
                chmod($filepath, 0644);
                $image_url = $upload_url_path . $filename;
                
                // Delete old image if updating
                if ($id > 0) {
                    $stmt = $pdo->prepare("SELECT image_url FROM activities WHERE id = ?");
                    $stmt->execute([$id]);
                    $old_activity = $stmt->fetch();
                    if ($old_activity && $old_activity['image_url']) {
                        $old_full_path = $_SERVER['DOCUMENT_ROOT'] . $base . $old_activity['image_url'];
                        if (file_exists($old_full_path)) {
                            unlink($old_full_path);
                        }
                    }
                }
            } else {
                $upload_error = 'Failed to upload image. Check folder permissions.';
            }
        }
    }
    
    if (empty($upload_error)) {
        if ($id > 0) {
            // Update
            $sql = "UPDATE activities SET title=?, description=?, activity_date=?, location=?, beneficiaries=?, category=?, status=?, featured=?" . ($image_url ? ", image_url=?" : "") . " WHERE id=?";
            $params = $image_url ? [$title, $description, $date, $location, $beneficiaries, $category, $status, $featured, $image_url, $id] : [$title, $description, $date, $location, $beneficiaries, $category, $status, $featured, $id];
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            logAdminActivity(getCurrentAdminId(), 'update', 'activity', $id, 'Updated activity');
            setFlashMessage('success', 'Activity updated successfully');
        } else {
            // Create
            $stmt = $pdo->prepare("INSERT INTO activities (title, description, activity_date, location, beneficiaries, image_url, category, status, featured, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$title, $description, $date, $location, $beneficiaries, $image_url, $category, $status, $featured, getCurrentAdminId()]);
            logAdminActivity(getCurrentAdminId(), 'create', 'activity', $pdo->lastInsertId(), 'Created activity');
            setFlashMessage('success', 'Activity created successfully');
        }
        redirect($admin_base . 'activities.php');
    } else {
        setFlashMessage('danger', $upload_error);
    }
}

// Get activity for editing
$edit_activity = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM activities WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $edit_activity = $stmt->fetch();
}

// Get all activities
$stmt = $pdo->query("SELECT * FROM activities ORDER BY activity_date DESC");
$activities = $stmt->fetchAll();

include 'includes/admin_header.php';
?>

<div class="admin-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-calendar-event"></i> Manage Activities</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#activityModal">
            <i class="bi bi-plus-circle"></i> Add New Activity
        </button>
    </div>

    <?php displayFlashMessage(); ?>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Date</th>
                            <th>Location</th>
                            <th>Beneficiaries</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Featured</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($activities)): ?>
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                <p class="text-muted mt-2">No activities yet. Create your first activity!</p>
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($activities as $activity): ?>
                        <tr>
                            <td>
                                <?php if ($activity['image_url']): ?>
                                <img src="<?php echo $base . htmlspecialchars($activity['image_url']); ?>" 
                                     alt="Activity" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                <?php else: ?>
                                <div style="width: 60px; height: 60px; background: #e9ecef; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-image" style="font-size: 1.5rem; color: #adb5bd;"></i>
                                </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($activity['title']); ?></strong>
                            </td>
                            <td><?php echo formatDate($activity['activity_date']); ?></td>
                            <td><i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($activity['location']); ?></td>
                            <td><i class="bi bi-people"></i> <?php echo $activity['beneficiaries']; ?></td>
                            <td><span class="badge bg-info"><?php echo ucwords(str_replace('_', ' ', $activity['category'])); ?></span></td>
                            <td>
                                <?php 
                                $status_class = $activity['status'] === 'active' ? 'success' : ($activity['status'] === 'draft' ? 'warning' : 'secondary');
                                ?>
                                <span class="badge bg-<?php echo $status_class; ?>"><?php echo ucfirst($activity['status']); ?></span>
                            </td>
                            <td>
                                <?php if ($activity['featured']): ?>
                                <i class="bi bi-star-fill text-warning" title="Featured"></i>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="?edit=<?php echo $activity['id']; ?>" class="btn btn-sm btn-info" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="?delete=<?php echo $activity['id']; ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Are you sure you want to delete this activity?')"
                                   title="Delete">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div class="modal fade" id="activityModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-<?php echo $edit_activity ? 'pencil' : 'plus-circle'; ?>"></i>
                    <?php echo $edit_activity ? 'Edit' : 'Add New'; ?> Activity
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" enctype="multipart/form-data" id="activityForm">
                <div class="modal-body">
                    <input type="hidden" name="id" value="<?php echo $edit_activity['id'] ?? 0; ?>">
                    
                    <div class="mb-3">
                        <label class="form-label">Title *</label>
                        <input type="text" name="title" class="form-control" required 
                               value="<?php echo htmlspecialchars($edit_activity['title'] ?? ''); ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Description *</label>
                        <textarea name="description" class="form-control" rows="4" required><?php echo htmlspecialchars($edit_activity['description'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Activity Date *</label>
                            <input type="date" name="activity_date" class="form-control" required 
                                   value="<?php echo $edit_activity['activity_date'] ?? ''; ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Location *</label>
                            <input type="text" name="location" class="form-control" required 
                                   value="<?php echo htmlspecialchars($edit_activity['location'] ?? ''); ?>">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Beneficiaries</label>
                            <input type="number" name="beneficiaries" class="form-control" min="0"
                                   value="<?php echo $edit_activity['beneficiaries'] ?? 0; ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Category *</label>
                            <select name="category" class="form-control" required>
                                <option value="orphan_support" <?php echo ($edit_activity['category'] ?? '') === 'orphan_support' ? 'selected' : ''; ?>>Orphan Support</option>
                                <option value="disability_care" <?php echo ($edit_activity['category'] ?? '') === 'disability_care' ? 'selected' : ''; ?>>Disability Care</option>
                                <option value="poverty_relief" <?php echo ($edit_activity['category'] ?? '') === 'poverty_relief' ? 'selected' : ''; ?>>Poverty Relief</option>
                                <option value="education" <?php echo ($edit_activity['category'] ?? '') === 'education' ? 'selected' : ''; ?>>Education</option>
                                <option value="healthcare" <?php echo ($edit_activity['category'] ?? '') === 'healthcare' ? 'selected' : ''; ?>>Healthcare</option>
                                <option value="emergency_relief" <?php echo ($edit_activity['category'] ?? '') === 'emergency_relief' ? 'selected' : ''; ?>>Emergency Relief</option>
                                <option value="other" <?php echo ($edit_activity['category'] ?? '') === 'other' ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Status *</label>
                            <select name="status" class="form-control" required>
                                <option value="active" <?php echo ($edit_activity['status'] ?? 'active') === 'active' ? 'selected' : ''; ?>>Active</option>
                                <option value="draft" <?php echo ($edit_activity['status'] ?? '') === 'draft' ? 'selected' : ''; ?>>Draft</option>
                                <option value="archived" <?php echo ($edit_activity['status'] ?? '') === 'archived' ? 'selected' : ''; ?>>Archived</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Activity Image</label>
                        <?php if ($edit_activity && $edit_activity['image_url']): ?>
                        <div class="mb-2">
                            <img src="<?php echo $base . htmlspecialchars($edit_activity['image_url']); ?>" 
                                 alt="Current image" style="max-width: 200px; border-radius: 8px;">
                            <p class="text-muted small mt-1">Current image (upload new to replace)</p>
                        </div>
                        <?php endif; ?>
                        <input type="file" name="image" class="form-control" accept="image/*" id="imageInput">
                        <small class="text-muted">Accepted: JPG, PNG, GIF (Max 5MB)</small>
                        <div id="imagePreview" class="mt-2" style="display: none;">
                            <img src="" alt="Preview" style="max-width: 200px; border-radius: 8px;">
                        </div>
                    </div>
                    
                    <div class="form-check">
                        <input type="checkbox" name="featured" class="form-check-input" id="featured"
                               <?php echo ($edit_activity['featured'] ?? false) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="featured">
                            <i class="bi bi-star"></i> Feature this activity on homepage
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Save Activity
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Image preview
document.getElementById('imageInput')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('imagePreview');
            preview.querySelector('img').src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
});

// Auto-open modal if editing
<?php if ($edit_activity): ?>
document.addEventListener('DOMContentLoaded', function() {
    new bootstrap.Modal(document.getElementById('activityModal')).show();
});
<?php endif; ?>
</script>

<?php include 'includes/admin_footer.php'; ?>