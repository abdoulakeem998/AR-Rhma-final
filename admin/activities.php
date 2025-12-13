<?php
require_once '../config/database.php';
include 'includes/admin_header.php';

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM activities WHERE id = ?");
    if ($stmt->execute([$id])) {
        logAdminActivity(getCurrentAdminId(), 'delete', 'activity', $id, 'Deleted activity');
        setFlashMessage('success', 'Activity deleted');
        header('Location: activities.php');
        exit;
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
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload = uploadFile($_FILES['image'], '../uploads/activities', ['jpg', 'jpeg', 'png', 'gif']);
        if ($upload['success']) {
            $image_url = 'uploads/activities/' . $upload['filename'];
        }
    }
    
    if ($id > 0) {
        $sql = "UPDATE activities SET title=?, description=?, activity_date=?, location=?, beneficiaries=?, category=?, status=?, featured=?" . ($image_url ? ", image_url=?" : "") . " WHERE id=?";
        $params = $image_url ? [$title, $description, $date, $location, $beneficiaries, $category, $status, $featured, $image_url, $id] : [$title, $description, $date, $location, $beneficiaries, $category, $status, $featured, $id];
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        logAdminActivity(getCurrentAdminId(), 'update', 'activity', $id, 'Updated activity');
        setFlashMessage('success', 'Activity updated');
    } else {
        $stmt = $pdo->prepare("INSERT INTO activities (title, description, activity_date, location, beneficiaries, image_url, category, status, featured, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $description, $date, $location, $beneficiaries, $image_url, $category, $status, $featured, getCurrentAdminId()]);
        logAdminActivity(getCurrentAdminId(), 'create', 'activity', $pdo->lastInsertId(), 'Created activity');
        setFlashMessage('success', 'Activity created');
    }
    header('Location: activities.php');
    exit;
}

// Get activity for editing
$edit_activity = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM activities WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $edit_activity = $stmt->fetch();
}

$stmt = $pdo->query("SELECT * FROM activities ORDER BY activity_date DESC");
$activities = $stmt->fetchAll();
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Manage Activities</h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#activityModal">
        <i class="bi bi-plus"></i> Add Activity
    </button>
</div>

<?php displayFlashMessage(); ?>

<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Title</th>
                <th>Date</th>
                <th>Location</th>
                <th>Beneficiaries</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($activities as $activity): ?>
            <tr>
                <td><?php echo htmlspecialchars($activity['title']); ?></td>
                <td><?php echo formatDate($activity['activity_date']); ?></td>
                <td><?php echo htmlspecialchars($activity['location']); ?></td>
                <td><?php echo $activity['beneficiaries']; ?></td>
                <td><span class="badge badge-primary"><?php echo $activity['status']; ?></span></td>
                <td>
                    <a href="?edit=<?php echo $activity['id']; ?>" class="btn btn-sm btn-info">Edit</a>
                    <a href="?delete=<?php echo $activity['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirmDelete()">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Add/Edit Modal -->
<div class="modal fade" id="activityModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo $edit_activity ? 'Edit' : 'Add'; ?> Activity</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="id" value="<?php echo $edit_activity['id'] ?? 0; ?>">
                    <div class="mb-3">
                        <label class="form-label">Title *</label>
                        <input type="text" name="title" class="form-control" required value="<?php echo $edit_activity['title'] ?? ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description *</label>
                        <textarea name="description" class="form-control" rows="4" required><?php echo $edit_activity['description'] ?? ''; ?></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date *</label>
                            <input type="date" name="activity_date" class="form-control" required value="<?php echo $edit_activity['activity_date'] ?? ''; ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Location *</label>
                            <input type="text" name="location" class="form-control" required value="<?php echo $edit_activity['location'] ?? ''; ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Beneficiaries</label>
                            <input type="number" name="beneficiaries" class="form-control" value="<?php echo $edit_activity['beneficiaries'] ?? 0; ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Category</label>
                            <select name="category" class="form-control">
                                <option value="orphan_support">Orphan Support</option>
                                <option value="disability_care">Disability Care</option>
                                <option value="poverty_relief">Poverty Relief</option>
                                <option value="education">Education</option>
                                <option value="healthcare">Healthcare</option>
                                <option value="emergency_relief">Emergency Relief</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-control">
                                <option value="active">Active</option>
                                <option value="draft">Draft</option>
                                <option value="archived">Archived</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="featured" class="form-check-input" <?php echo ($edit_activity['featured'] ?? false) ? 'checked' : ''; ?>>
                        <label class="form-check-label">Feature on homepage</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if ($edit_activity): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    new bootstrap.Modal(document.getElementById('activityModal')).show();
});
</script>
<?php endif; ?>

<?php include 'includes/admin_footer.php'; ?>
