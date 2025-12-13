<?php
require_once '../config/database.php';
include 'includes/admin_header.php';

$stmt = $pdo->query("SELECT * FROM gallery ORDER BY created_at DESC");
$images = $stmt->fetchAll();
?>
<h2>Gallery</h2>
<p>Manage gallery images (Add upload and delete functionality)</p>
<div class="row">
    <?php foreach ($images as $image): ?>
    <div class="col-md-3 mb-3">
        <div class="card">
            <img src="../<?php echo htmlspecialchars($image['image_url']); ?>" class="card-img-top">
            <div class="card-body">
                <p class="card-text small"><?php echo htmlspecialchars($image['title']); ?></p>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php include 'includes/admin_footer.php'; ?>
