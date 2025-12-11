<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$stmt = $pdo->query("SELECT * FROM gallery WHERE status = 'active' ORDER BY created_at DESC LIMIT 50");
$images = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Gallery - AR-Rahma</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <section class="hero-section" style="padding: 3rem 0;">
        <div class="container text-center">
            <h1>Photo Gallery</h1>
            <p class="lead">Our work in pictures</p>
        </div>
    </section>
    <section class="section">
        <div class="container">
            <div class="row">
                <?php if (empty($images)): ?>
                <div class="col-12 text-center py-5">
                    <p>No images to display yet.</p>
                </div>
                <?php else: ?>
                <?php foreach ($images as $image): ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <img src="<?php echo htmlspecialchars($image['image_url']); ?>" 
                         alt="<?php echo htmlspecialchars($image['title']); ?>"
                         class="img-fluid rounded"
                         style="width: 100%; height: 200px; object-fit: cover; cursor: pointer;">
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php include 'includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
