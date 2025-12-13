<?php
require_once '../config/database.php';
include 'includes/admin_header.php';

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['upload'])) {
    $title = $_POST['title'] ?? '';
    
    // File upload handling
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $file = $_FILES['image'];
        
        // Check if it's an image
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        $file_type = mime_content_type($file['tmp_name']);
        
        if (!in_array($file_type, $allowed_types)) {
            $error = "Only JPG, PNG, GIF, and WebP images are allowed.";
        } else {
            // Generate unique filename
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '_' . time() . '.' . $extension;
            $upload_path = '../uploads/gallery/' . $filename;
            
            // Create uploads/gallery directory if it doesn't exist
            if (!is_dir('../uploads/gallery')) {
                mkdir('../uploads/gallery', 0777, true);
            }
            
            // Move uploaded file
            if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                // Insert into database
                $sql = "INSERT INTO gallery (title, image_url) VALUES (?, ?)";
                $stmt = $pdo->prepare($sql);
                if ($stmt->execute([$title, 'uploads/gallery/' . $filename])) {
                    $success = "Image uploaded successfully!";
                } else {
                    $error = "Failed to save image to database.";
                }
            } else {
                $error = "Failed to upload image.";
            }
        }
    } else {
        $error = "Please select an image to upload.";
    }
}
?>

<h2>Upload Gallery Image</h2>

<?php if (isset($success)): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data" class="mb-4">
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="title" class="form-label">Image Title/Caption</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            
            <div class="mb-3">
                <label for="image" class="form-label">Select Image</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                <div class="form-text">Allowed: JPG, PNG, GIF, WebP (Max: 5MB)</div>
            </div>
            
            <button type="submit" name="upload" class="btn btn-primary">Upload Image</button>
            <a href="gallery.php" class="btn btn-secondary">Back to Gallery</a>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Upload Guidelines</h6>
                    <ul class="small">
                        <li>Maximum file size: 5MB</li>
                        <li>Supported formats: JPG, PNG, GIF, WebP</li>
                        <li>Recommended dimensions: 1200x800px</li>
                        <li>Images will be automatically optimized</li>
                        <li>Add descriptive titles for better SEO</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</form>

<?php include 'includes/admin_footer.php'; ?>