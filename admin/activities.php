<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

requireAdmin();

$message = '';
$error = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // CREATE NEW ACTIVITY
    if (isset($_POST['create_activity'])) {
        $title = cleanInput($_POST['title']);
        $description = cleanInput($_POST['description']);
        $category = cleanInput($_POST['category']);
        $is_featured = isset($_POST['is_featured']) ? 1 : 0;
        $status = cleanInput($_POST['status']);
        
        // Handle image upload
        $image_path = null;
        $upload_error = '';
        
        if (isset($_FILES['activity_image']) && $_FILES['activity_image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $file = $_FILES['activity_image'];
            
            // Debug information
            error_log("Upload attempt - Name: {$file['name']}, Size: {$file['size']}, Type: {$file['type']}, Error: {$file['error']}");
            
            if ($file['error'] === UPLOAD_ERR_OK) {
                $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                $file_type = mime_content_type($file['tmp_name']);
                $file_size = $file['size'];
                $max_size = 5 * 1024 * 1024; // 5MB
                
                if (!in_array($file_type, $allowed_types)) {
                    $upload_error = "Invalid file type: $file_type. Allowed: JPG, PNG, GIF";
                } elseif ($file_size > $max_size) {
                    $upload_error = "File too large: " . round($file_size/1024/1024, 2) . "MB. Max: 5MB";
                } else {
                    // Create upload directory if it doesn't exist
                    $upload_dir = '../uploads/activities/';
                    if (!file_exists($upload_dir)) {
                        if (!mkdir($upload_dir, 0755, true)) {
                            $upload_error = "Failed to create upload directory. Please create 'uploads/activities/' folder and set permissions to 755";
                            error_log("Failed to create directory: $upload_dir");
                        }
                    }
                    
                    if (empty($upload_error)) {
                        // Check if directory is writable
                        if (!is_writable($upload_dir)) {
                            $upload_error = "Upload directory is not writable. Please set permissions to 755 or 777";
                            error_log("Directory not writable: $upload_dir");
                        } else {
                            // Generate unique filename
                            $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                            $filename = 'activity_' . time() . '_' . uniqid() . '.' . $extension;
                            $target_path = $upload_dir . $filename;
                            
                            // Move uploaded file
                            if (move_uploaded_file($file['tmp_name'], $target_path)) {
                                $image_path = 'uploads/activities/' . $filename;
                                error_log("File uploaded successfully: $image_path");
                            } else {
                                $upload_error = "Failed to move uploaded file. Check folder permissions.";
                                error_log("move_uploaded_file failed: {$file['tmp_name']} to $target_path");
                            }
                        }
                    }
                }
            } else {
                $upload_errors = [
                    UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize directive in php.ini',
                    UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE directive in HTML form',
                    UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
                    UPLOAD_ERR_NO_FILE => 'No file was uploaded',
                    UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
                    UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
                    UPLOAD_ERR_EXTENSION => 'PHP extension stopped the upload'
                ];
                $upload_error = $upload_errors[$file['error']] ?? "Unknown upload error (code: {$file['error']})";
                error_log("Upload error code {$file['error']}: $upload_error");
            }
        }
        
        if (!empty($upload_error)) {
            $error = $upload_error;
        } else {
            // Insert into database
            $stmt = $pdo->prepare("INSERT INTO activities (title, description, category, image_path, is_featured, status, created_by) VALUES (?, ?, ?, ?, ?, ?, ?)");
            
            if ($stmt->execute([$title, $description, $category, $image_path, $is_featured, $status, $_SESSION['user_id']])) {
                logAdminActivity($_SESSION['user_id'], 'create', 'activities', "Created activity: $title");
                $message = "Activity created successfully!";
            } else {
                $error = "Database error: " . implode(", ", $stmt->errorInfo());
                error_log("Database insert failed: " . print_r($stmt->errorInfo(), true));
            }
        }
    }
    
    // UPDATE ACTIVITY
    if (isset($_POST['update_activity'])) {
        $id = (int)$_POST['activity_id'];
        $title = cleanInput($_POST['title']);
        $description = cleanInput($_POST['description']);
        $category = cleanInput($_POST['category']);
        $is_featured = isset($_POST['is_featured']) ? 1 : 0;
        $status = cleanInput($_POST['status']);
        
        // Get current image
        $stmt = $pdo->prepare("SELECT image_path FROM activities WHERE id = ?");
        $stmt->execute([$id]);
        $current_activity = $stmt->fetch();
        $image_path = $current_activity['image_path'];
        
        // Handle new image upload
        $upload_error = '';
        if (isset($_FILES['activity_image']) && $_FILES['activity_image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $file = $_FILES['activity_image'];
            
            if ($file['error'] === UPLOAD_ERR_OK) {
                $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                $file_type = mime_content_type($file['tmp_name']);
                $file_size = $file['size'];
                $max_size = 5 * 1024 * 1024;
                
                if (!in_array($file_type, $allowed_types)) {
                    $upload_error = "Invalid file type. Allowed: JPG, PNG, GIF";
                } elseif ($file_size > $max_size) {
                    $upload_error = "File too large. Max: 5MB";
                } else {
                    $upload_dir = '../uploads/activities/';
                    if (!file_exists($upload_dir)) {
                        mkdir($upload_dir, 0755, true);
                    }
                    
                    if (is_writable($upload_dir)) {
                        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                        $filename = 'activity_' . time() . '_' . uniqid() . '.' . $extension;
                        $target_path = $upload_dir . $filename;
                        
                        if (move_uploaded_file($file['tmp_name'], $target_path)) {
                            // Delete old image
                            if ($image_path && file_exists('../' . $image_path)) {
                                unlink('../' . $image_path);
                            }
                            $image_path = 'uploads/activities/' . $filename;
                        } else {
                            $upload_error = "Failed to upload new image";
                        }
                    } else {
                        $upload_error = "Upload directory not writable";
                    }
                }
            }
        }
        
        if (!empty($upload_error)) {
            $error = $upload_error;
        } else {
            $stmt = $pdo->prepare("UPDATE activities SET title = ?, description = ?, category = ?, image_path = ?, is_featured = ?, status = ? WHERE id = ?");
            
            if ($stmt->execute([$title, $description, $category, $image_path, $is_featured, $status, $id])) {
                logAdminActivity($_SESSION['user_id'], 'update', 'activities', "Updated activity: $title");
                $message = "Activity updated successfully!";
            } else {
                $error = "Failed to update activity";
            }
        }
    }
    
    // DELETE ACTIVITY
    if (isset($_POST['delete_activity'])) {
        $id = (int)$_POST['activity_id'];
        
        // Get image path
        $stmt = $pdo->prepare("SELECT title, image_path FROM activities WHERE id = ?");
        $stmt->execute([$id]);
        $activity = $stmt->fetch();
        
        if ($activity) {
            // Delete image file
            if ($activity['image_path'] && file_exists('../' . $activity['image_path'])) {
                unlink('../' . $activity['image_path']);
            }
            
            // Delete from database
            $stmt = $pdo->prepare("DELETE FROM activities WHERE id = ?");
            if ($stmt->execute([$id])) {
                logAdminActivity($_SESSION['user_id'], 'delete', 'activities', "Deleted activity: {$activity['title']}");
                $message = "Activity deleted successfully!";
            }
        }
    }
}

// Fetch all activities
$stmt = $pdo->query("SELECT a.*, u.full_name as creator FROM activities a LEFT JOIN users u ON a.created_by = u.id ORDER BY a.created_at DESC");
$activities = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Activities - AR-Rahma Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .activity-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }
        .image-preview {
            max-width: 200px;
            max-height: 200px;
            margin-top: 10px;
            border-radius: 8px;
        }
        .card-header {
            background: linear-gradient(135deg, #2C5F2D 0%, #1a3a1b 100%);
            color: white;
        }
        .btn-primary {
            background: #2C5F2D;
            border-color: #2C5F2D;
        }
        .btn-primary:hover {
            background: #1a3a1b;
            border-color: #1a3a1b;
        }
        .upload-info {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <?php include 'includes/admin_header.php'; ?>
    
    <div class="container-fluid">
        <div class="row">
            <?php include 'includes/admin_sidebar.php'; ?>
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1><i class="fas fa-tasks"></i> Manage Activities</h1>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="fas fa-plus"></i> Add New Activity
                    </button>
                </div>
                
                <?php if ($message): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?= htmlspecialchars($message) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <strong>Error:</strong> <?= htmlspecialchars($error) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <!-- Upload System Status -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5><i class="fas fa-info-circle"></i> Upload System Status</h5>
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <strong>Upload Directory:</strong><br>
                                <?php
                                $upload_dir = '../uploads/activities/';
                                if (file_exists($upload_dir)) {
                                    if (is_writable($upload_dir)) {
                                        echo '<span class="text-success"><i class="fas fa-check-circle"></i> Exists and writable</span>';
                                    } else {
                                        echo '<span class="text-danger"><i class="fas fa-times-circle"></i> Exists but NOT writable</span><br>';
                                        echo '<small>Run: chmod 755 uploads/activities/</small>';
                                    }
                                } else {
                                    echo '<span class="text-warning"><i class="fas fa-exclamation-triangle"></i> Does not exist</span><br>';
                                    echo '<small>Will be created on first upload</small>';
                                }
                                ?>
                            </div>
                            <div class="col-md-4">
                                <strong>Max Upload Size:</strong><br>
                                <?= ini_get('upload_max_filesize') ?>
                            </div>
                            <div class="col-md-4">
                                <strong>Post Max Size:</strong><br>
                                <?= ini_get('post_max_size') ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-list"></i> All Activities</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($activities)): ?>
                            <div class="text-center py-5">
                                <i class="fas fa-tasks fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No activities yet. Create your first activity!</p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Title</th>
                                            <th>Category</th>
                                            <th>Status</th>
                                            <th>Featured</th>
                                            <th>Created By</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($activities as $activity): ?>
                                            <tr>
                                                <td>
                                                    <?php if ($activity['image_path']): ?>
                                                        <img src="../<?= htmlspecialchars($activity['image_path']) ?>" 
                                                             alt="Activity" class="activity-image"
                                                             onerror="this.src='https://via.placeholder.com/60x60?text=No+Image'">
                                                    <?php else: ?>
                                                        <div class="activity-image bg-secondary d-flex align-items-center justify-content-center">
                                                            <i class="fas fa-image text-white"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= htmlspecialchars($activity['title']) ?></td>
                                                <td>
                                                    <span class="badge bg-info">
                                                        <?= htmlspecialchars($activity['category']) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-<?= $activity['status'] === 'active' ? 'success' : 'secondary' ?>">
                                                        <?= htmlspecialchars($activity['status']) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?= $activity['is_featured'] ? '<i class="fas fa-star text-warning"></i>' : '' ?>
                                                </td>
                                                <td><?= htmlspecialchars($activity['creator'] ?? 'Unknown') ?></td>
                                                <td><?= date('M d, Y', strtotime($activity['created_at'])) ?></td>
                                                <td>
                                                    <button class="btn btn-sm btn-warning" onclick="editActivity(<?= $activity['id'] ?>)">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger" onclick="deleteActivity(<?= $activity['id'] ?>, '<?= htmlspecialchars($activity['title']) ?>')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <!-- Create Activity Modal -->
    <div class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-plus"></i> Create New Activity</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="upload-info">
                            <i class="fas fa-info-circle"></i> 
                            <strong>Image Upload:</strong> JPG, PNG, or GIF • Max size: 5MB • Recommended: 800x600px
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Title *</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Description *</label>
                            <textarea name="description" class="form-control" rows="4" required></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Category *</label>
                            <select name="category" class="form-select" required>
                                <option value="">Select Category</option>
                                <option value="education">Education</option>
                                <option value="health">Health</option>
                                <option value="feeding">Feeding Programs</option>
                                <option value="shelter">Shelter & Housing</option>
                                <option value="community">Community Development</option>
                                <option value="emergency">Emergency Relief</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Activity Image</label>
                            <input type="file" name="activity_image" class="form-control" 
                                   accept="image/jpeg,image/jpg,image/png,image/gif"
                                   onchange="previewImage(this, 'createPreview')">
                            <img id="createPreview" class="image-preview" style="display:none;">
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Status *</label>
                                    <select name="status" class="form-select" required>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check mt-4">
                                        <input type="checkbox" name="is_featured" class="form-check-input" id="featured">
                                        <label class="form-check-label" for="featured">
                                            <i class="fas fa-star text-warning"></i> Featured Activity
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="create_activity" class="btn btn-primary">
                            <i class="fas fa-save"></i> Create Activity
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Edit Activity Modal -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit"></i> Edit Activity</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="activity_id" id="edit_id">
                    <div class="modal-body">
                        <div class="upload-info">
                            <i class="fas fa-info-circle"></i> 
                            Upload a new image to replace the current one (optional)
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Title *</label>
                            <input type="text" name="title" id="edit_title" class="form-control" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Description *</label>
                            <textarea name="description" id="edit_description" class="form-control" rows="4" required></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Category *</label>
                            <select name="category" id="edit_category" class="form-select" required>
                                <option value="education">Education</option>
                                <option value="health">Health</option>
                                <option value="feeding">Feeding Programs</option>
                                <option value="shelter">Shelter & Housing</option>
                                <option value="community">Community Development</option>
                                <option value="emergency">Emergency Relief</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Current Image</label>
                            <div id="current_image_display"></div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Upload New Image (optional)</label>
                            <input type="file" name="activity_image" class="form-control" 
                                   accept="image/jpeg,image/jpg,image/png,image/gif"
                                   onchange="previewImage(this, 'editPreview')">
                            <img id="editPreview" class="image-preview" style="display:none;">
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Status *</label>
                                    <select name="status" id="edit_status" class="form-select" required>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check mt-4">
                                        <input type="checkbox" name="is_featured" class="form-check-input" id="edit_featured">
                                        <label class="form-check-label" for="edit_featured">
                                            <i class="fas fa-star text-warning"></i> Featured Activity
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="update_activity" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Activity
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Delete Form (hidden) -->
    <form id="deleteForm" method="POST" style="display:none;">
        <input type="hidden" name="activity_id" id="delete_id">
        <input type="hidden" name="delete_activity" value="1">
    </form>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Activities data for JavaScript
        const activities = <?= json_encode($activities) ?>;
        
        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.style.display = 'none';
            }
        }
        
        function editActivity(id) {
            const activity = activities.find(a => a.id == id);
            if (!activity) return;
            
            document.getElementById('edit_id').value = activity.id;
            document.getElementById('edit_title').value = activity.title;
            document.getElementById('edit_description').value = activity.description;
            document.getElementById('edit_category').value = activity.category;
            document.getElementById('edit_status').value = activity.status;
            document.getElementById('edit_featured').checked = activity.is_featured == 1;
            
            // Show current image
            const currentImageDiv = document.getElementById('current_image_display');
            if (activity.image_path) {
                currentImageDiv.innerHTML = `<img src="../${activity.image_path}" class="image-preview" alt="Current image">`;
            } else {
                currentImageDiv.innerHTML = '<p class="text-muted">No image uploaded</p>';
            }
            
            // Reset new image preview
            document.getElementById('editPreview').style.display = 'none';
            
            new bootstrap.Modal(document.getElementById('editModal')).show();
        }
        
        function deleteActivity(id, title) {
            if (confirm(`Are you sure you want to delete "${title}"?\n\nThis will also delete the activity image.`)) {
                document.getElementById('delete_id').value = id;
                document.getElementById('deleteForm').submit();
            }
        }
    </script>
</body>
</html>