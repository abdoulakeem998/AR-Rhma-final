<?php
require_once '../config/database.php';
include 'includes/admin_header.php';

// Handle image deletion
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    
    // Get image path before deleting
    $stmt = $pdo->prepare("SELECT image_url FROM gallery WHERE id = ?");
    $stmt->execute([$id]);
    $image = $stmt->fetch();
    
    if ($image) {
        // Delete file from server
        $file_path = '../' . $image['image_url'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
        
        // Delete from database
        $stmt = $pdo->prepare("DELETE FROM gallery WHERE id = ?");
        if ($stmt->execute([$id])) {
            $_SESSION['success'] = "Image deleted successfully!";
        } else {
            $_SESSION['error'] = "Failed to delete image.";
        }
    }
    
    header("Location: gallery.php");
    exit();
}

// Display success/error messages
if (isset($_SESSION['success'])) {
    echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
    unset($_SESSION['success']);
}
if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
    unset($_SESSION['error']);
}

// Get all gallery images
$stmt = $pdo->query("SELECT * FROM gallery ORDER BY created_at DESC");
$images = $stmt->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Gallery Management</h2>
    <div>
        <a href="gallery_upload.php" class="btn btn-primary">
            <i class="fas fa-upload"></i> Upload New Image
        </a>
    </div>
</div>

<!-- Gallery Stats -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h5 class="card-title"><?php echo count($images); ?></h5>
                <p class="card-text">Total Images</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h5 class="card-title">5 MB</h5>
                <p class="card-text">Max File Size</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h5 class="card-title">4</h5>
                <p class="card-text">Supported Formats</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <h5 class="card-title"><?php echo count($images); ?> MB</h5>
                <p class="card-text">Total Gallery Size</p>
            </div>
        </div>
    </div>
</div>

<!-- Gallery Images -->
<div class="row">
    <?php if (empty($images)): ?>
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> No images in gallery yet. 
                <a href="gallery_upload.php" class="alert-link">Upload your first image</a>
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($images as $image): ?>
        <div class="col-md-3 mb-4">
            <div class="card gallery-card">
                <div class="card-img-container">
                    <img src="../<?php echo htmlspecialchars($image['image_url']); ?>" 
                         class="card-img-top gallery-image" 
                         alt="<?php echo htmlspecialchars($image['title']); ?>"
                         loading="lazy">
                    <div class="image-actions">
                        <a href="gallery_upload.php?edit=<?php echo $image['id']; ?>" 
                           class="btn btn-sm btn-warning" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="gallery.php?delete=<?php echo $image['id']; ?>" 
                           class="btn btn-sm btn-danger" 
                           title="Delete"
                           onclick="return confirm('Are you sure you want to delete this image?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <p class="card-text"><?php echo htmlspecialchars($image['title']); ?></p>
                    <small class="text-muted">
                        <i class="fas fa-calendar"></i> 
                        <?php echo date('M d, Y', strtotime($image['created_at'])); ?>
                    </small>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Add some CSS for better gallery display -->
<style>
.gallery-card {
    transition: transform 0.3s;
    overflow: hidden;
}
.gallery-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}
.card-img-container {
    position: relative;
    overflow: hidden;
    height: 200px;
}
.gallery-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}
.gallery-card:hover .gallery-image {
    transform: scale(1.05);
}
.image-actions {
    position: absolute;
    top: 10px;
    right: 10px;
    opacity: 0;
    transition: opacity 0.3s;
}
.gallery-card:hover .image-actions {
    opacity: 1;
}
.image-actions .btn {
    margin-left: 5px;
}
</style>

<?php include 'includes/admin_footer.php'; ?>