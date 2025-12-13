<?php
require_once '../config/database.php';
require_once '../includes/functions.php';
requireAdmin();

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Get base path
$base = defined('BASE_PATH') ? BASE_PATH : '/';
$admin_base = $base . 'admin/';

// FIXED: Correct upload paths
$current_dir = dirname(dirname(__FILE__)); // Goes up one level from admin/
$upload_base_dir = $current_dir . '/uploads/members/';
$upload_url_path = '/uploads/members/'; // FIXED: Removed duplicate folder

// Debug info (you can remove this after it works)
error_log("Current directory: " . $current_dir);
error_log("Upload directory: " . $upload_base_dir);
error_log("Upload URL path: " . $upload_url_path);

// Create directory if it doesn't exist
if (!file_exists($upload_base_dir)) {
    // Try to create it
    if (@mkdir($upload_base_dir, 0755, true)) {
        @chmod($upload_base_dir, 0755);
        error_log("Successfully created directory: " . $upload_base_dir);
    } else {
        error_log("Failed to create directory: " . $upload_base_dir);
        // Set a flag to show error to user
        $dir_error = true;
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    try {
        // Get photo path before deleting
        $stmt = $pdo->prepare("SELECT photo_url FROM team_members WHERE id = ?");
        $stmt->execute([$id]);
        $member = $stmt->fetch();
        
        // Delete from database
        $stmt = $pdo->prepare("DELETE FROM team_members WHERE id = ?");
        if ($stmt->execute([$id])) {
            // Delete photo file if exists
            if ($member && $member['photo_url']) {
                $full_path = $_SERVER['DOCUMENT_ROOT'] . $member['photo_url'];
                if (file_exists($full_path)) {
                    unlink($full_path);
                }
            }
            
            logAdminActivity(getCurrentAdminId(), 'delete', 'team_member', $id, 'Deleted team member');
            setFlashMessage('success', 'Team member deleted successfully');
        }
    } catch (Exception $e) {
        setFlashMessage('danger', 'Error deleting member: ' . $e->getMessage());
    }
    redirect($admin_base . 'team.php');
}

// Handle create/update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? 0;
    $full_name = cleanInput($_POST['full_name']);
    $position = cleanInput($_POST['position']);
    $bio = cleanInput($_POST['bio']);
    $email = cleanInput($_POST['email']);
    $phone = cleanInput($_POST['phone']);
    $facebook = cleanInput($_POST['facebook']);
    $twitter = cleanInput($_POST['twitter']);
    $linkedin = cleanInput($_POST['linkedin']);
    $display_order = (int)$_POST['display_order'];
    $status = $_POST['status'];
    
    $photo_url = '';
    $upload_error = '';
    
    // Check if upload directory exists and is writable
    if (!file_exists($upload_base_dir)) {
        $upload_error = 'Upload directory does not exist. Please create it manually: ' . $upload_base_dir;
    } elseif (!is_writable($upload_base_dir)) {
        $upload_error = 'Upload directory is not writable. Please set permissions to 755 or 777: ' . $upload_base_dir;
    }
    
    // IMPROVED: Better photo upload handling
    if (empty($upload_error) && isset($_FILES['photo']) && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
        
        // Check for upload errors first
        if ($_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
            $upload_errors = [
                UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize in php.ini',
                UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE in form',
                UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
                UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
                UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
                UPLOAD_ERR_EXTENSION => 'PHP extension stopped the upload'
            ];
            $upload_error = $upload_errors[$_FILES['photo']['error']] ?? 'Unknown upload error';
        } else {
            // Validate file
            $file_info = [
                'name' => $_FILES['photo']['name'],
                'type' => $_FILES['photo']['type'],
                'tmp_name' => $_FILES['photo']['tmp_name'],
                'size' => $_FILES['photo']['size']
            ];
            
            // Get actual mime type
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $actual_mime = finfo_file($finfo, $file_info['tmp_name']);
            finfo_close($finfo);
            
            $allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];
            $max_size = 3 * 1024 * 1024; // 3MB
            
            if (!in_array($actual_mime, $allowed_types)) {
                $upload_error = 'Invalid file type. Only JPG and PNG are allowed. Detected type: ' . $actual_mime;
            } elseif ($file_info['size'] > $max_size) {
                $upload_error = 'File too large. Maximum size is 3MB. Your file: ' . round($file_info['size'] / 1024 / 1024, 2) . 'MB';
            } elseif ($file_info['size'] == 0) {
                $upload_error = 'File is empty (0 bytes)';
            } else {
                // Create unique filename
                $extension = strtolower(pathinfo($file_info['name'], PATHINFO_EXTENSION));
                $filename = 'member_' . time() . '_' . uniqid() . '.' . $extension;
                $filepath = $upload_base_dir . $filename;
                
                // Debug info
                error_log("Attempting upload:");
                error_log("  Source: " . $file_info['tmp_name']);
                error_log("  Destination: " . $filepath);
                error_log("  Directory exists: " . (file_exists($upload_base_dir) ? 'Yes' : 'No'));
                error_log("  Directory writable: " . (is_writable($upload_base_dir) ? 'Yes' : 'No'));
                
                // Attempt upload
                if (move_uploaded_file($file_info['tmp_name'], $filepath)) {
                    @chmod($filepath, 0644);
                    $photo_url = $upload_url_path . $filename; // FIXED: Use correct path
                    
                    // Delete old photo if updating
                    if ($id > 0) {
                        $stmt = $pdo->prepare("SELECT photo_url FROM team_members WHERE id = ?");
                        $stmt->execute([$id]);
                        $old_member = $stmt->fetch();
                        if ($old_member && $old_member['photo_url']) {
                            $old_full_path = $_SERVER['DOCUMENT_ROOT'] . $old_member['photo_url'];
                            if (file_exists($old_full_path)) {
                                unlink($old_full_path);
                            }
                        }
                    }
                } else {
                    $upload_error = 'Failed to move uploaded file. Check folder permissions. Path: ' . $filepath;
                    error_log("Upload failed: " . $upload_error);
                }
            }
        }
    }
    
    if (empty($upload_error)) {
        try {
            if ($id > 0) {
                // Update
                $sql = "UPDATE team_members SET full_name=?, position=?, bio=?, email=?, phone=?, facebook=?, twitter=?, linkedin=?, display_order=?, status=?" . ($photo_url ? ", photo_url=?" : "") . " WHERE id=?";
                $params = $photo_url ? [$full_name, $position, $bio, $email, $phone, $facebook, $twitter, $linkedin, $display_order, $status, $photo_url, $id] : [$full_name, $position, $bio, $email, $phone, $facebook, $twitter, $linkedin, $display_order, $status, $id];
                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);
                logAdminActivity(getCurrentAdminId(), 'update', 'team_member', $id, 'Updated team member');
                setFlashMessage('success', 'Team member updated successfully');
            } else {
                // Create
                $stmt = $pdo->prepare("INSERT INTO team_members (full_name, position, bio, email, phone, photo_url, facebook, twitter, linkedin, display_order, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$full_name, $position, $bio, $email, $phone, $photo_url, $facebook, $twitter, $linkedin, $display_order, $status]);
                logAdminActivity(getCurrentAdminId(), 'create', 'team_member', $pdo->lastInsertId(), 'Created team member');
                setFlashMessage('success', 'Team member added successfully' . ($photo_url ? ' with photo' : ''));
            }
            redirect($admin_base . 'team.php');
        } catch (Exception $e) {
            setFlashMessage('danger', 'Database error: ' . $e->getMessage());
        }
    } else {
        setFlashMessage('danger', $upload_error);
    }
}

// Get member for editing
$edit_member = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM team_members WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $edit_member = $stmt->fetch();
}

// Get all team members
$stmt = $pdo->query("SELECT * FROM team_members ORDER BY display_order ASC, full_name ASC");
$members = $stmt->fetchAll();

include 'includes/admin_header.php';
?>

<!-- Display upload directory info for debugging -->
<?php if (isset($dir_error)): ?>
<div class="alert alert-danger">
    <strong>⚠️ Upload Directory Error!</strong><br>
    The uploads directory cannot be created automatically. Please create it manually:<br>
    <code><?php echo $upload_base_dir; ?></code><br><br>
    <strong>Steps:</strong><br>
    1. Use your hosting File Manager<br>
    2. Navigate to: <code><?php echo $current_dir; ?></code><br>
    3. Create folder: <code>uploads</code><br>
    4. Inside uploads, create folder: <code>members</code><br>
    5. Set permissions to 755 or 777
</div>
<?php endif; ?>

<?php if (isset($_GET['debug'])): ?>
<div class="alert alert-info">
    <strong>Debug Info:</strong><br>
    Current Script Directory: <?php echo $current_dir; ?><br>
    Upload Directory: <?php echo $upload_base_dir; ?><br>
    Upload URL Path: <?php echo $upload_url_path; ?><br>
    Directory Exists: <?php echo file_exists($upload_base_dir) ? '✅ Yes' : '❌ No'; ?><br>
    Directory Writable: <?php echo is_writable($upload_base_dir) ? '✅ Yes' : '❌ No'; ?><br>
    PHP upload_max_filesize: <?php echo ini_get('upload_max_filesize'); ?><br>
    PHP post_max_size: <?php echo ini_get('post_max_size'); ?>
</div>
<?php endif; ?>

<div class="admin-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-people"></i> Manage Team Members</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#teamModal">
            <i class="bi bi-person-plus"></i> Add Team Member
        </button>
    </div>

    <?php displayFlashMessage(); ?>

    <div class="card">
        <div class="card-body">
            <?php if (empty($members)): ?>
            <div class="text-center py-5">
                <i class="bi bi-people" style="font-size: 4rem; color: #ccc;"></i>
                <h4 class="mt-3 text-muted">No team members yet</h4>
                <p class="text-muted">Add your first team member to get started</p>
                <button class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#teamModal">
                    <i class="bi bi-person-plus"></i> Add Team Member
                </button>
            </div>
            <?php else: ?>
            <div class="row">
                <?php foreach ($members as $member): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 team-member-card">
                        <div class="card-body text-center">
                            <div class="member-photo-wrapper mb-3">
                                <?php if ($member['photo_url']): ?>
                                <img src="<?php echo htmlspecialchars($member['photo_url']); ?>" 
                                     alt="<?php echo htmlspecialchars($member['full_name']); ?>"
                                     class="member-photo">
                                <?php else: ?>
                                <div class="member-photo-placeholder">
                                    <i class="bi bi-person-circle"></i>
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <h5 class="card-title mb-1"><?php echo htmlspecialchars($member['full_name']); ?></h5>
                            <p class="text-primary mb-2"><strong><?php echo htmlspecialchars($member['position']); ?></strong></p>
                            
                            <?php if ($member['bio']): ?>
                            <p class="card-text small text-muted mb-3">
                                <?php echo truncateText(htmlspecialchars($member['bio']), 100); ?>
                            </p>
                            <?php endif; ?>
                            
                            <div class="member-contact mb-3">
                                <?php if ($member['email']): ?>
                                <div class="small text-muted"><i class="bi bi-envelope"></i> <?php echo htmlspecialchars($member['email']); ?></div>
                                <?php endif; ?>
                                <?php if ($member['phone']): ?>
                                <div class="small text-muted"><i class="bi bi-phone"></i> <?php echo htmlspecialchars($member['phone']); ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="member-social mb-3">
                                <?php if (!empty($member['facebook'])): ?>
                                <a href="<?php echo htmlspecialchars($member['facebook']); ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-facebook"></i>
                                </a>
                                <?php endif; ?>
                                <?php if (!empty($member['twitter'])): ?>
                                <a href="<?php echo htmlspecialchars($member['twitter']); ?>" target="_blank" class="btn btn-sm btn-outline-info">
                                    <i class="bi bi-twitter"></i>
                                </a>
                                <?php endif; ?>
                                <?php if (!empty($member['linkedin'])): ?>
                                <a href="<?php echo htmlspecialchars($member['linkedin']); ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-linkedin"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mb-2">
                                <span class="badge bg-<?php echo $member['status'] === 'active' ? 'success' : 'secondary'; ?>">
                                    <?php echo ucfirst($member['status']); ?>
                                </span>
                                <span class="badge bg-info">Order: <?php echo $member['display_order']; ?></span>
                            </div>
                            
                            <div class="btn-group mt-2" role="group">
                                <a href="?edit=<?php echo $member['id']; ?>" class="btn btn-sm btn-info">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <a href="?delete=<?php echo $member['id']; ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Are you sure you want to delete this team member?')">
                                    <i class="bi bi-trash"></i> Delete
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div class="modal fade" id="teamModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-<?php echo $edit_member ? 'pencil' : 'person-plus'; ?>"></i>
                    <?php echo $edit_member ? 'Edit' : 'Add New'; ?> Team Member
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" enctype="multipart/form-data" id="teamForm">
                <div class="modal-body">
                    <input type="hidden" name="id" value="<?php echo $edit_member['id'] ?? 0; ?>">
                    
                    <div class="mb-3 text-center">
                        <label class="form-label d-block">Member Photo</label>
                        <?php if ($edit_member && $edit_member['photo_url']): ?>
                        <div class="mb-2">
                            <img src="<?php echo htmlspecialchars($edit_member['photo_url']); ?>" 
                                 alt="Current photo" 
                                 style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%; border: 3px solid #2C5F2D;">
                            <p class="text-muted small mt-1">Current photo</p>
                        </div>
                        <?php endif; ?>
                        <input type="file" name="photo" class="form-control" accept="image/*" id="photoInput">
                        <small class="text-muted">Accepted: JPG, PNG (Max 3MB) - Recommended: Square image</small>
                        <div id="photoPreview" class="mt-2" style="display: none;">
                            <img src="" alt="Preview" style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%; border: 3px solid #2C5F2D;">
                        </div>
                        <div id="uploadInfo" class="mt-2 small text-muted"></div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="full_name" class="form-control" required 
                                   value="<?php echo htmlspecialchars($edit_member['full_name'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Position/Title *</label>
                            <input type="text" name="position" class="form-control" required 
                                   value="<?php echo htmlspecialchars($edit_member['position'] ?? ''); ?>">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Bio</label>
                        <textarea name="bio" class="form-control" rows="3"><?php echo htmlspecialchars($edit_member['bio'] ?? ''); ?></textarea>
                        <small class="text-muted">Brief description about the team member</small>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" 
                                   value="<?php echo htmlspecialchars($edit_member['email'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone</label>
                            <input type="tel" name="phone" class="form-control" 
                                   value="<?php echo htmlspecialchars($edit_member['phone'] ?? ''); ?>">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label"><i class="bi bi-share"></i> Social Media Links (Optional)</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="bi bi-facebook"></i></span>
                            <input type="url" name="facebook" class="form-control" placeholder="Facebook profile URL"
                                   value="<?php echo htmlspecialchars($edit_member['facebook'] ?? ''); ?>">
                        </div>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="bi bi-twitter"></i></span>
                            <input type="url" name="twitter" class="form-control" placeholder="Twitter profile URL"
                                   value="<?php echo htmlspecialchars($edit_member['twitter'] ?? ''); ?>">
                        </div>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-linkedin"></i></span>
                            <input type="url" name="linkedin" class="form-control" placeholder="LinkedIn profile URL"
                                   value="<?php echo htmlspecialchars($edit_member['linkedin'] ?? ''); ?>">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Display Order</label>
                            <input type="number" name="display_order" class="form-control" min="0"
                                   value="<?php echo $edit_member['display_order'] ?? 0; ?>">
                            <small class="text-muted">Lower numbers appear first</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status *</label>
                            <select name="status" class="form-control" required>
                                <option value="active" <?php echo ($edit_member['status'] ?? 'active') === 'active' ? 'selected' : ''; ?>>Active</option>
                                <option value="inactive" <?php echo ($edit_member['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="bi bi-check-circle"></i> Save Member
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.team-member-card {
    transition: all 0.3s ease;
    border: 1px solid #e0e0e0;
}

.team-member-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.15);
}

.member-photo-wrapper {
    display: flex;
    justify-content: center;
}

.member-photo {
    width: 120px;
    height: 120px;
    object-fit: cover;
    border-radius: 50%;
    border: 4px solid #D4AF37;
}

.member-photo-placeholder {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 4px solid #e0e0e0;
}

.member-photo-placeholder i {
    font-size: 4rem;
    color: #adb5bd;
}

.member-contact {
    font-size: 0.9rem;
}

.member-social a {
    margin: 0 0.25rem;
}
</style>

<script>
// Enhanced photo preview with file info
document.getElementById('photoInput')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('photoPreview');
    const info = document.getElementById('uploadInfo');
    
    if (file) {
        // Display file info
        const sizeInMB = (file.size / 1024 / 1024).toFixed(2);
        info.innerHTML = `<strong>File:</strong> ${file.name}<br><strong>Size:</strong> ${sizeInMB} MB<br><strong>Type:</strong> ${file.type}`;
        
        // Check file size
        if (file.size > 3 * 1024 * 1024) {
            info.innerHTML += '<br><span class="text-danger">⚠️ File is too large! Max 3MB</span>';
            e.target.value = '';
            preview.style.display = 'none';
            return;
        }
        
        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.querySelector('img').src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
        info.innerHTML = '';
    }
});

// Prevent double submission
document.getElementById('teamForm')?.addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Saving...';
});

// Auto-open modal if editing
<?php if ($edit_member): ?>
document.addEventListener('DOMContentLoaded', function() {
    new bootstrap.Modal(document.getElementById('teamModal')).show();
});
<?php endif; ?>
</script>

<?php include 'includes/admin_footer.php'; ?>