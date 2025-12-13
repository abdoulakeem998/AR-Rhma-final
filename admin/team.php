<?php
require_once '../config/database.php';
require_once '../includes/functions.php';
requireAdmin();

// Temporary: enable full error reporting and log to a file for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('log_errors', 1);
$debugLogDir = __DIR__ . '/../logs';
if (!file_exists($debugLogDir)) {
    @mkdir($debugLogDir, 0755, true);
}
ini_set('error_log', $debugLogDir . '/php_errors.log');
// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    // Get photo path before deleting
    $stmt = $pdo->prepare("SELECT photo_url FROM team_members WHERE id = ?");
    $stmt->execute([$id]);
    $member = $stmt->fetch();
    
    // Delete from database
    $stmt = $pdo->prepare("DELETE FROM team_members WHERE id = ?");
    if ($stmt->execute([$id])) {
        // Delete photo file if exists
        if ($member && $member['photo_url'] && file_exists('../' . $member['photo_url'])) {
            unlink('../' . $member['photo_url']);
        }
        
        logAdminActivity(getCurrentAdminId(), 'delete', 'team_member', $id, 'Deleted team member');
        setFlashMessage('success', 'Team member deleted successfully');
        header('Location: team.php');
        exit;
    }
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
    
    // Handle photo upload
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];
        $max_size = 3 * 1024 * 1024; // 3MB
        
        if (!in_array($_FILES['photo']['type'], $allowed_types)) {
            $upload_error = 'Invalid file type. Only JPG and PNG are allowed.';
        } elseif ($_FILES['photo']['size'] > $max_size) {
            $upload_error = 'File too large. Maximum size is 3MB.';
        } else {
            $upload_dir = '../uploads/members/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            $extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $filename = 'member_' . time() . '_' . uniqid() . '.' . $extension;
            $filepath = $upload_dir . $filename;
            
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $filepath)) {
                $photo_url = 'uploads/members/' . $filename;
                
                // Delete old photo if updating
                if ($id > 0) {
                    $stmt = $pdo->prepare("SELECT photo_url FROM team_members WHERE id = ?");
                    $stmt->execute([$id]);
                    $old_member = $stmt->fetch();
                    if ($old_member && $old_member['photo_url'] && file_exists('../' . $old_member['photo_url'])) {
                        unlink('../' . $old_member['photo_url']);
                    }
                }
            } else {
                $upload_error = 'Failed to upload photo.';
            }
        }
    }
    
    if (empty($upload_error)) {
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
            setFlashMessage('success', 'Team member added successfully');
        }
        header('Location: team.php');
        exit;
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
                                <img src="../<?php echo htmlspecialchars($member['photo_url']); ?>" 
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
                            <img src="../<?php echo htmlspecialchars($edit_member['photo_url']); ?>" 
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
                    <button type="submit" class="btn btn-primary">
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
// Photo preview
document.getElementById('photoInput')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('photoPreview');
            preview.querySelector('img').src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
});

// Auto-open modal if editing
<?php if ($edit_member): ?>
document.addEventListener('DOMContentLoaded', function() {
    new bootstrap.Modal(document.getElementById('teamModal')).show();
});
<?php endif; ?>
</script>

<?php include 'includes/admin_footer.php'; ?>