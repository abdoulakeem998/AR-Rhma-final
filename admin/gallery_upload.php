<?php
require_once '../config/database.php';
include 'includes/admin_header.php';

// Ensure uploads directory exists
$upload_dir = '../uploads/gallery/';
if (!is_dir($upload_dir)) {
    if (!mkdir($upload_dir, 0777, true)) {
        die("<div class='alert alert-danger'>Failed to create upload directory. Please create 'uploads/gallery/' folder manually.</div>");
    }
}

// Check if directory is writable
if (!is_writable($upload_dir)) {
    die("<div class='alert alert-danger'>Upload directory is not writable. Please set permissions to 755 or 777.</div>");
}

// Handle file upload
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['upload'])) {
    $title = trim($_POST['title']);
    
    if (empty($_FILES['image']['name'])) {
        $error = "Please select an image file.";
    } elseif ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        $error = "Upload error: " . $_FILES['image']['error'];
    } else {
        // Validate file type by extension
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $file_ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        
        if (!in_array($file_ext, $allowed_ext)) {
            $error = "Only JPG, PNG, GIF, and WebP images are allowed.";
        } elseif ($_FILES['image']['size'] > 5000000) { // 5MB limit
            $error = "File size must be less than 5MB.";
        } else {
            // Generate unique filename
            $filename = uniqid() . '_' . time() . '.' . $file_ext;
            $target_path = $upload_dir . $filename;
            
            // Simple file move (try multiple methods)
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
                // Alternative if move_uploaded_file fails
                $upload_success = true;
            } else {
                // Try copy as alternative
                if (copy($_FILES['image']['tmp_name'], $target_path)) {
                    $upload_success = true;
                } else {
                    $error = "Failed to save uploaded file. Check directory permissions.";
                }
            }
            
            if (isset($upload_success) && $upload_success) {
                // Save to database
                $db_path = 'uploads/gallery/' . $filename;
                try {
                    $sql = "INSERT INTO gallery (title, image_url) VALUES (?, ?)";
                    $stmt = $pdo->prepare($sql);
                    if ($stmt->execute([$title, $db_path])) {
                        $success = "Image uploaded successfully!";
                        // Redirect after 2 seconds
                        echo '<script>
                            setTimeout(function() {
                                window.location.href = "gallery.php";
                            }, 2000);
                        </script>';
                    } else {
                        $error = "Failed to save to database.";
                        // Delete uploaded file if DB insert failed
                        if (file_exists($target_path)) {
                            unlink($target_path);
                        }
                    }
                } catch (PDOException $e) {
                    $error = "Database error: " . $e->getMessage();
                    if (file_exists($target_path)) {
                        unlink($target_path);
                    }
                }
            }
        }
    }
}
?>

<h2>Upload Gallery Image</h2>

<?php if ($success): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-danger">
        <strong>Error:</strong> <?php echo $error; ?>
        <br><small>Upload Directory: <?php echo $upload_dir; ?></small>
        <br><small>Writable: <?php echo is_writable($upload_dir) ? 'Yes' : 'No'; ?></small>
    </div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data" class="mb-4" onsubmit="return validateForm()">
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="title" class="form-label">Image Title/Caption *</label>
                <input type="text" class="form-control" id="title" name="title" required 
                       placeholder="e.g., Community Outreach Event">
            </div>
            
            <div class="mb-3">
                <label for="image" class="form-label">Select Image *</label>
                <input type="file" class="form-control" id="image" name="image" 
                       accept=".jpg,.jpeg,.png,.gif,.webp" required>
                <div class="form-text">
                    Max size: 5MB | Allowed: JPG, PNG, GIF, WebP
                </div>
                <div id="filePreview" class="mt-2" style="display:none;">
                    <img id="previewImage" src="#" alt="Preview" style="max-width:200px; max-height:150px;">
                </div>
            </div>
            
            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="confirm" required>
                    <label class="form-check-label" for="confirm">
                        I confirm this image is appropriate for our organization
                    </label>
                </div>
            </div>
            
            <button type="submit" name="upload" class="btn btn-primary">
                <i class="fas fa-upload"></i> Upload Image
            </button>
            <a href="gallery.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Gallery
            </a>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-info-circle"></i> Upload Tips</h5>
                    <ul>
                        <li>Use descriptive titles for better organization</li>
                        <li>Optimal image size: 1200x800 pixels</li>
                        <li>Max file size: 5MB</li>
                        <li>Formats: JPG, PNG, GIF, WebP</li>
                        <li>Images will be displayed in the public gallery</li>
                    </ul>
                    
                    <div class="alert alert-info mt-3">
                        <strong>Directory Status:</strong><br>
                        Path: <code><?php echo realpath($upload_dir); ?></code><br>
                        Exists: <?php echo is_dir($upload_dir) ? '✓ Yes' : '✗ No'; ?><br>
                        Writable: <?php echo is_writable($upload_dir) ? '✓ Yes' : '✗ No'; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
// Image preview
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('previewImage');
    const previewDiv = document.getElementById('filePreview');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewDiv.style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        previewDiv.style.display = 'none';
    }
});

// Form validation
function validateForm() {
    const fileInput = document.getElementById('image');
    const file = fileInput.files[0];
    
    if (file) {
        // Check file size (5MB = 5 * 1024 * 1024 bytes)
        if (file.size > 5242880) {
            alert('File size must be less than 5MB');
            return false;
        }
        
        // Check file type
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        if (!allowedTypes.includes(file.type)) {
            alert('Only image files are allowed (JPG, PNG, GIF, WebP)');
            return false;
        }
    }
    
    return true;
}
</script>

<style>
.card-img-top {
    max-height: 200px;
    object-fit: cover;
}
</style>

<?php include 'includes/admin_footer.php'; ?>