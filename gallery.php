<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$stmt = $pdo->query("SELECT * FROM gallery WHERE status = 'active' ORDER BY created_at DESC LIMIT 50");
$images = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery - AR-Rahma</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Gallery Hero Background */
        .hero-gallery {
            background: linear-gradient(rgba(44, 95, 45, 0.85), rgba(26, 58, 27, 0.9)), 
                        url('https://images.unsplash.com/photo-1452421822248-d4c2b47f0c81?w=1920&h=600&fit=crop&q=80') center/cover !important;
            background-size: cover !important;
            background-position: center !important;
            min-height: 500px;
            position: relative;
        }
        
        /* Gallery Image Hover Effect */
        .gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: 12px;
            cursor: pointer;
            transition: transform 0.3s ease;
            height: 250px;
        }
        
        .gallery-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .gallery-item:hover img {
            transform: scale(1.1);
        }
        
        .gallery-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, transparent 0%, rgba(0,0,0,0.7) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
            display: flex;
            align-items: flex-end;
            padding: 1rem;
        }
        
        .gallery-item:hover .gallery-overlay {
            opacity: 1;
        }
        
        .gallery-title {
            color: white;
            font-size: 0.9rem;
            font-weight: 600;
            margin: 0;
        }
        
        .gallery-icon {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 2rem;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .gallery-item:hover .gallery-icon {
            opacity: 1;
        }
        
        /* Modal Image */
        .modal-content {
            background: transparent;
            border: none;
        }
        
        .modal-body {
            padding: 0;
        }
        
        .modal-body img {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="hero-section hero-gallery">
        <div class="hero-overlay"></div>
        <div class="container text-center hero-content">
            <div class="hero-icon">
                <i class="bi bi-images"></i>
            </div>
            <h1 class="hero-title">Photo Gallery</h1>
            <p class="hero-subtitle">Capturing moments of hope and compassion</p>
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="section">
        <div class="container">
            <?php if (empty($images)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-images" style="font-size: 5rem; color: var(--border-color);"></i>
                    <h3 class="mt-3">No Photos Yet</h3>
                    <p class="text-muted">Check back soon to see our work in action.</p>
                </div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($images as $image): ?>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="gallery-item" 
                             data-bs-toggle="modal" 
                             data-bs-target="#imageModal"
                             data-image="<?php echo htmlspecialchars($image['image_url']); ?>"
                             data-title="<?php echo htmlspecialchars($image['title']); ?>">
                            <img src="<?php echo htmlspecialchars($image['image_url']); ?>" 
                                 alt="<?php echo htmlspecialchars($image['title']); ?>">
                            <div class="gallery-overlay">
                                <p class="gallery-title"><?php echo htmlspecialchars($image['title']); ?></p>
                            </div>
                            <i class="bi bi-zoom-in gallery-icon"></i>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Image Count -->
                <div class="text-center mt-4">
                    <p class="text-muted">
                        <i class="bi bi-images"></i> 
                        Showing <?php echo count($images); ?> photos
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title text-white" id="modalImageTitle"></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img id="modalImage" src="" alt="" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Handle image modal
        document.addEventListener('DOMContentLoaded', function() {
            const imageModal = document.getElementById('imageModal');
            
            if (imageModal) {
                imageModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    const imageUrl = button.getAttribute('data-image');
                    const imageTitle = button.getAttribute('data-title');
                    
                    const modalImage = document.getElementById('modalImage');
                    const modalTitle = document.getElementById('modalImageTitle');
                    
                    modalImage.src = imageUrl;
                    modalImage.alt = imageTitle;
                    modalTitle.textContent = imageTitle;
                });
            }
        });
    </script>
</body>
</html>