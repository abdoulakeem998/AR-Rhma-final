<?php
require_once '../config/database.php';
require_once '../includes/functions.php';
requireAdmin();

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    // Get image path before deleting
    $stmt = $pdo->prepare("SELECT image_url FROM gallery WHERE id = ?");
    $stmt->execute([$id]);
    $image = $stmt->fetch();
    
    // Delete from database
    $stmt = $pdo->prepare("DELETE FROM gallery WHERE id = ?");
    if ($stmt->execute([$id])) {
        // Delete image file if exists
        if ($image && $image['image_url'] && file_exists('../' . $image['image_url'])) {
            unlink('../' . $image['image_url']);
        }
        
        logAdminActivity(getCurrentAdminId(), 'delete', 'gallery_image', $id, 'Deleted gallery image');
        setFlashMessage('success', 'Image deleted successfully');
        header('Location: gallery.php');
        exit;
    }
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
        if ($files['error'][$i] === UPLOAD_ERR_OK) {
            $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            $max_size = 5 * 1024 * 1024; // 5MB
            
            $file_type = $files['type'][$i];
            $file_size = $files['size'][$i];
            $file_tmp = $files['tmp_name'][$i];
            $file_name = $files['name'][$i];
            
            if (!in_array($file_type, $allowed_types)) {
                $errors[] = "$file_name: Invalid file type";
                $error_count++;
                continue;
            }
            
            if ($file_size > $max_size) {
                $errors[] = "$file_name: File too large (max 5MB)";
                $error_count++;
                continue;
            }
            
            $upload_dir = '../uploads/gallery/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            $extension = pathinfo($file_name, PATHINFO_EXTENSION);
            $filename = 'gallery_' . time() . '_' . $i . '_' . uniqid() . '.' . $extension;
            $filepath = $upload_dir . $filename;
            
            if (move_uploaded_file($file_tmp, $filepath)) {
                $image_url = 'uploads/gallery/' . $filename;
                
                // Save to database
                $image_title = $title ?: pathinfo($file_name, PATHINFO_FILENAME);
                $stmt = $pdo->prepare("INSERT INTO gallery (title, description, image_url, category, uploaded_by) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$image_title, $description, $image_url, $category, getCurrentAdminId()]);
                
                $upload_count++;
            } else {
                $errors[] = "$file_name: Upload failed";
                $error_count++;
            }
        }
    }
    
    if ($upload_count > 0) {
        logAdminActivity(getCurrentAdminId(), 'upload', 'gallery_images', 0, "Uploaded $upload_count image(s)");
        setFlashMessage('success', "$upload_count image(s) uploaded successfully");
    }
    
    if ($error_count > 0) {
        setFlashMessage('warning', "Upload errors: " . implode(', ', $errors));
    }
    
    header('Location: gallery.php');
    exit;
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
                    <img src="../<?php echo htmlspecialchars($image['image_url']); ?>" 
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
                        <img src="../<?php echo htmlspecialchars($image['image_url']); ?>" 
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
                        <a href="../<?php echo htmlspecialchars($image['image_url']); ?>" 
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
                        <label class="form-label">Preview:</label>
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
                    <button type="submit" class="btn btn-primary">
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
    width: 400%;
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
// Multiple image preview
document.getElementById('imageInput')?.addEventListener('change', function(e) {
    const files = e.target.files;
    const container = document.getElementById('imagePreviewContainer');
    const previews = document.getElementById('imagePreviews');
    
    previews.innerHTML = '';
    
    if (files.length > 0) {
        container.style.display = 'block';
        
        Array.from(files).forEach(file => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    previews.appendChild(img);
                }
                reader.readAsDataURL(file);
            }
        });
    } else {
        container.style.display = 'none';
    }
});
</script>

<?php include 'includes/admin_footer.php'; ?>