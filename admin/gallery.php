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

// FIXED: Use relative path from current script location (hosting-specific fix)
$current_dir = dirname(dirname(__FILE__)); // Goes up one level from admin/
$upload_base_dir = $current_dir . '/uploads/gallery/';
$upload_url_path = '/uploads/AR-Rhma-final/uploads/gallery/';

// Debug info (you can remove this after it works)
error_log("Current directory: " . $current_dir);
error_log("Upload directory: " . $upload_base_dir);

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
        // Get image path before deleting
        $stmt = $pdo->prepare("SELECT image_url FROM gallery WHERE id = ?");
        $stmt->execute([$id]);
        $image = $stmt->fetch();
        
        // Delete from database
        $stmt = $pdo->prepare("DELETE FROM gallery WHERE id = ?");
        if ($stmt->execute([$id])) {
            // Delete image file if exists
            if ($image && $image['image_url']) {
                $full_path = $_SERVER['DOCUMENT_ROOT'] . $image['image_url'];
                if (file_exists($full_path)) {
                    unlink($full_path);
                }
            }
            
            logAdminActivity(getCurrentAdminId(), 'delete', 'gallery_image', $id, 'Deleted gallery image');
            setFlashMessage('success', 'Image deleted successfully');
        }
    } catch (Exception $e) {
        setFlashMessage('danger', 'Error deleting image: ' . $e->getMessage());
    }
    redirect($admin_base . 'gallery.php');
}

// Handle upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['images'])) {
    $title = cleanInput($_POST['title'] ?? '');
    $description = cleanInput($_POST['description'] ?? '');
    $category = $_POST['category'] ?? 'general';
    
    $upload_count = 0;
    $error_count = 0;
    $errors = [];
    
    // Handle multiple file uploads
    $files = $_FILES['images'];
    $file_count = count($files['name']);
    
    for ($i = 0; $i < $file_count; $i++) {
        // Skip if no file uploaded for this slot
        if ($files['error'][$i] === UPLOAD_ERR_NO_FILE) {
            continue;
        }
        
        // Check for upload errors
        if ($files['error'][$i] !== UPLOAD_ERR_OK) {
            $upload_errors_map = [
                UPLOAD_ERR_INI_SIZE => 'exceeds upload_max_filesize',
                UPLOAD_ERR_FORM_SIZE => 'exceeds MAX_FILE_SIZE',
                UPLOAD_ERR_PARTIAL => 'only partially uploaded',
                UPLOAD_ERR_NO_TMP_DIR => 'missing temporary folder',
                UPLOAD_ERR_CANT_WRITE => 'failed to write to disk',
                UPLOAD_ERR_EXTENSION => 'PHP extension stopped upload'
            ];
            $errors[] = $files['name'][$i] . ': ' . ($upload_errors_map[$files['error'][$i]] ?? 'unknown error');
            $error_count++;
            continue;
        }
        
        // Validate file
        $file_info = [
            'name' => $files['name'][$i],
            'type' => $files['type'][$i],
            'tmp_name' => $files['tmp_name'][$i],
            'size' => $files['size'][$i]
        ];
        
        // Get actual mime type
        if (file_exists($file_info['tmp_name'])) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $actual_mime = finfo_file($finfo, $file_info['tmp_name']);
            finfo_close($finfo);
        } else {
            $errors[] = $file_info['name'] . ': Temporary file not found';
            $error_count++;
            continue;
        }
        
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        $max_size = 5 * 1024 * 1024; // 5MB
        
        if (!in_array($actual_mime, $allowed_types)) {
            $errors[] = $file_info['name'] . ': Invalid type (' . $actual_mime . ')';
            $error_count++;
            continue;
        }
        
        if ($file_info['size'] > $max_size) {
            $errors[] = $file_info['name'] . ': Too large (' . round($file_info['size'] / 1024 / 1024, 2) . 'MB)';
            $error_count++;
            continue;
        }
        
        if ($file_info['size'] == 0) {
            $errors[] = $file_info['name'] . ': Empty file';
            $error_count++;
            continue;
        }
        
        // Create unique filename
        $extension = strtolower(pathinfo($file_info['name'], PATHINFO_EXTENSION));
        $filename = 'gallery_' . time() . '_' . $i . '_' . uniqid() . '.' . $extension;
        $filepath = $upload_base_dir . $filename;
        
        // Attempt upload
        if (move_uploaded_file($file_info['tmp_name'], $filepath)) {
            chmod($filepath, 0644);
            $image_url = '/uploads/AR-Rhma-final/uploads/gallery/' . $filename;
            
            // Save to database
            $image_title = $title ?: pathinfo($file_info['name'], PATHINFO_FILENAME);
            try {
                $stmt = $pdo->prepare("INSERT INTO gallery (title, description, image_url, category, uploaded_by) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$image_title, $description, $image_url, $category, getCurrentAdminId()]);
                $upload_count++;
            } catch (Exception $e) {
                $errors[] = $file_info['name'] . ': Database error - ' . $e->getMessage();
                $error_count++;
                // Delete the uploaded file since database insert failed
                if (file_exists($filepath)) {
                    unlink($filepath);
                }
            }
        } else {
            $errors[] = $file_info['name'] . ': Failed to move file';
            $error_count++;
            error_log("Upload failed for: " . $file_info['name']);
            error_log("  Destination: " . $filepath);
            error_log("  Directory writable: " . (is_writable($upload_base_dir) ? 'Yes' : 'No'));
        }
    }
    
    if ($upload_count > 0) {
        logAdminActivity(getCurrentAdminId(), 'upload', 'gallery_images', 0, "Uploaded $upload_count image(s)");
        setFlashMessage('success', "$upload_count image(s) uploaded successfully");
    }
    
    if ($error_count > 0) {
        $error_msg = count($errors) <= 3 ? implode('; ', $errors) : implode('; ', array_slice($errors, 0, 3)) . ' and ' . (count($errors) - 3) . ' more...';
        setFlashMessage('warning', "Upload errors: " . $error_msg);
    }
    
    if ($upload_count == 0 && $error_count == 0) {
        setFlashMessage('warning', 'No files were uploaded. Please select at least one image.');
    }
    
    redirect($admin_base . 'gallery.php');
}

// Get gallery images
$category_filter = $_GET['category'] ?? 'all';
$sql = "SELECT * FROM gallery" . ($category_filter !== 'all' ? " WHERE category = ?" : "") . " ORDER BY created_at DESC";
$stmt = $category_filter !== 'all' ? $pdo->prepare($sql) : $pdo->query($sql);
if ($category_filter !== 'all') {
    $stmt->execute([$category_filter]);
}
$images = $stmt->fetchAll();

include 'includes/admin_header.php';
?>

<!-- Display upload directory error if creation failed -->
<?php if (isset($dir_error)): ?>
<div class="alert alert-danger">
    <strong>⚠️ Upload Directory Error!</strong><br>
    The uploads directory cannot be created automatically. Please create it manually:<br>
    <code><?php echo $upload_base_dir; ?></code><br><br>
    <strong>Steps:</strong><br>
    1. Use your hosting File Manager<br>
    2. Navigate to: <code><?php echo $current_dir; ?></code><br>
    3. Create folder: <code>uploads</code><br>
    4. Inside uploads, create folder: <code>gallery</code><br>
    5. Set permissions to 755
</div>
<?php endif; ?>

<!-- Display upload directory info for debugging -->
<?php if (isset($_GET['debug'])): ?>
<div class="alert alert-info">
    <strong>Debug Info:</strong><br>
    Upload Directory: <?php echo $upload_base_dir; ?><br>
    Directory Exists: <?php echo file_exists($upload_base_dir) ? 'Yes' : 'No'; ?><br>
    Directory Writable: <?php echo is_writable($upload_base_dir) ? 'Yes' : 'No'; ?><br>
    PHP upload_max_filesize: <?php echo ini_get('upload_max_filesize'); ?><br>
    PHP post_max_size: <?php echo ini_get('post_max_size'); ?><br>
    PHP max_file_uploads: <?php echo ini_get('max_file_uploads'); ?>
</div>
<?php endif; ?>

<div class="admin-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-images"></i> Manage Gallery</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
            <i class="bi bi-cloud-upload"></i> Upload Images
        </button>
    </div>

    <?php displayFlashMessage(); ?>

    <!-- Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Filter by Category</label>
                    <select name="category" class="form-control" onchange="this.form.submit()">
                        <option value="all" <?php echo $category_filter === 'all' ? 'selected' : ''; ?>>All Categories</option>
                        <option value="activities" <?php echo $category_filter === 'activities' ? 'selected' : ''; ?>>Activities</option>
                        <option value="events" <?php echo $category_filter === 'events' ? 'selected' : ''; ?>>Events</option>
                        <option value="team" <?php echo $category_filter === 'team' ? 'selected' : ''; ?>>Team</option>
                        <option value="beneficiaries" <?php echo $category_filter === 'beneficiaries' ? 'selected' : ''; ?>>Beneficiaries</option>
                        <option value="general" <?php echo $category_filter === 'general' ? 'selected' : ''; ?>>General</option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <!-- Gallery Grid -->
    <?php if (empty($images)): ?>
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="bi bi-images" style="font-size: 4rem; color: #ccc;"></i>
            <h4 class="mt-3 text-muted">No images in gallery</h4>
            <p class="text-muted">Upload your first images to get started</p>
            <button class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#uploadModal">
                <i class="bi bi-cloud-upload"></i> Upload Images
            </button>
        </div>
    </div>
    <?php else: ?>
    <div class="row gallery-grid">
        <?php foreach ($images as $image): ?>
        <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
            <div class="card gallery-item h-100">
                <div class="gallery-image-wrapper">
                    <img src="<?php echo htmlspecialchars($image['image_url']); ?>" 
                         class="card-img-top gallery-image" 
                         alt="<?php echo htmlspecialchars($image['title']); ?>">
                    <div class="gallery-overlay">
                        <button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#viewModal<?php echo $image['id']; ?>">
                            <i class="bi bi-eye"></i> View
                        </button>
                        <a href="?delete=<?php echo $image['id']; ?>" 
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Are you sure you want to delete this image?')">
                            <i class="bi bi-trash"></i> Delete
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <h6 class="card-title"><?php echo htmlspecialchars($image['title']); ?></h6>
                    <?php if ($image['description']): ?>
                    <p class="card-text small text-muted"><?php echo truncateText(htmlspecialchars($image['description']), 80); ?></p>
                    <?php endif; ?>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge bg-info"><?php echo ucfirst($image['category']); ?></span>
                        <small class="text-muted"><?php echo timeAgo($image['created_at']); ?></small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- View Modal for each image -->
        <div class="modal fade" id="viewModal<?php echo $image['id']; ?>" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><?php echo htmlspecialchars($image['title']); ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="<?php echo htmlspecialchars($image['image_url']); ?>" 
                             class="img-fluid" 
                             alt="<?php echo htmlspecialchars($image['title']); ?>">
                        <?php if ($image['description']): ?>
                        <p class="mt-3"><?php echo htmlspecialchars($image['description']); ?></p>
                        <?php endif; ?>
                        <div class="mt-3">
                            <span class="badge bg-info"><?php echo ucfirst($image['category']); ?></span>
                            <span class="badge bg-secondary">Uploaded <?php echo timeAgo($image['created_at']); ?></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="<?php echo htmlspecialchars($image['image_url']); ?>" 
                           download 
                           class="btn btn-primary">
                            <i class="bi bi-download"></i> Download
                        </a>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <div class="text-center mt-3">
        <p class="text-muted">Total: <?php echo count($images); ?> image(s)</p>
    </div>
    <?php endif; ?>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-cloud-upload"></i> Upload Images to Gallery</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" enctype="multipart/form-data" id="uploadForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Images *</label>
                        <input type="file" name="images[]" class="form-control" accept="image/*" multiple required id="imageInput">
                        <small class="text-muted">You can select multiple images. Accepted: JPG, PNG, GIF (Max 5MB each)</small>
                    </div>
                    
                    <div id="imagePreviewContainer" class="mb-3" style="display: none;">
                        <label class="form-label">Preview (<span id="fileCount">0</span> file(s) selected):</label>
                        <div id="imagePreviews" class="d-flex flex-wrap gap-2"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Title (Optional)</label>
                        <input type="text" name="title" class="form-control" placeholder="Leave empty to use filename">
                        <small class="text-muted">Applied to all uploaded images if provided</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Description (Optional)</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Brief description"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Category *</label>
                        <select name="category" class="form-control" required>
                            <option value="general">General</option>
                            <option value="activities">Activities</option>
                            <option value="events">Events</option>
                            <option value="team">Team</option>
                            <option value="beneficiaries">Beneficiaries</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="bi bi-cloud-upload"></i> Upload Images
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
    width: 100%;
}

.gallery-item {
    transition: all 0.3s ease;
    overflow: hidden;
}

.gallery-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.gallery-image-wrapper {
    position: relative;
    overflow: hidden;
    height: 250px;
}

.gallery-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.gallery-item:hover .gallery-image {
    transform: scale(1.1);
}

.gallery-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.gallery-item:hover .gallery-overlay {
    opacity: 1;
}

#imagePreviews img {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 8px;
    border: 2px solid #dee2e6;
}
</style>

<script>
// Enhanced multiple image preview
document.getElementById('imageInput')?.addEventListener('change', function(e) {
    const files = e.target.files;
    const container = document.getElementById('imagePreviewContainer');
    const previews = document.getElementById('imagePreviews');
    const fileCount = document.getElementById('fileCount');
    
    previews.innerHTML = '';
    
    if (files.length > 0) {
        container.style.display = 'block';
        fileCount.textContent = files.length;
        
        let validCount = 0;
        let tooLarge = 0;
        
        Array.from(files).forEach(file => {
            if (file.type.startsWith('image/')) {
                // Check size
                if (file.size > 5 * 1024 * 1024) {
                    tooLarge++;
                    return;
                }
                
                validCount++;
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.title = file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';
                    previews.appendChild(img);
                }
                reader.readAsDataURL(file);
            }
        });
        
        if (tooLarge > 0) {
            const warning = document.createElement('div');
            warning.className = 'alert alert-warning mt-2';
            warning.textContent = `⚠️ ${tooLarge} file(s) are too large (over 5MB) and will be skipped`;
            previews.appendChild(warning);
        }
        
        fileCount.textContent = validCount;
    } else {
        container.style.display = 'none';
    }
});

// Prevent double submission
document.getElementById('uploadForm')?.addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Uploading...';
});
</script>

<?php include 'includes/admin_footer.php'; ?>