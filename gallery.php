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
        body {
            background-image: url('https://images.unsplash.com/photo-1559027615-cd4628902d4a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2074&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
            position: relative;
            min-height: 100vh;
            background-color: #f8f9fa;
        }
        /* Floating decorative elements */
        .floating-elements {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }
        
        /* Smooth scroll behavior */
        html {
            scroll-behavior: smooth;
        }
        
        /* Prevent overflow on modal open */
        body.modal-open {
            overflow: hidden;
            padding-right: 0 !important;
        }
        
        /* Gallery Section Background Enhancement */
        .gallery-section-bg {
            position: relative;
            z-index: 1;
        }
        
        /* Ensure content is above background elements */
        .container, .hero-content, .section {
            position: relative;
            z-index: 2;
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
            <div class="scroll-indicator">
                <i class="bi bi-chevron-down"></i>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="section gallery-section-bg" id="gallery-section">
        <div class="container">
            <?php if (empty($images)): ?>
                <div class="text-center py-5 gallery-empty-state">
                    <i class="bi bi-images"></i>
                    <h3 class="mt-3">No Photos Yet</h3>
                    <p class="text-muted">Check back soon to see our work in action.</p>
                </div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($images as $index => $image): ?>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="gallery-item" 
                             data-bs-toggle="modal" 
                             data-bs-target="#imageModal"
                             data-image="<?php echo htmlspecialchars($image['image_url']); ?>"
                             data-title="<?php echo htmlspecialchars($image['title']); ?>"
                             data-index="<?php echo $index; ?>">
                            <img src="<?php echo htmlspecialchars($image['image_url']); ?>" 
                                 alt="<?php echo htmlspecialchars($image['title']); ?>"
                                 loading="lazy">
                            <div class="gallery-overlay">
                                <p class="gallery-title"><?php echo htmlspecialchars($image['title']); ?></p>
                            </div>
                            <i class="bi bi-zoom-in gallery-icon"></i>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Image Count -->
                <div class="text-center mt-4 gallery-count">
                    <p class="text-muted">
                        <i class="bi bi-images"></i> 
                        Showing <span><?php echo count($images); ?></span> photos
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
            
            // Create floating decorative elements
            createFloatingElements();
            
            // Create background particles
            createParticles();
            
            // Animate gallery items on scroll
            animateGalleryItemsOnScroll();
            
            // Add smooth scroll to gallery section
            document.querySelector('.scroll-indicator').addEventListener('click', function() {
                document.getElementById('gallery-section').scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            });
        });

        function createFloatingElements() {
            const icons = ['✿', '❁', '★', '✦', '❖', '✵', '✷', '✸'];
            const container = document.createElement('div');
            container.className = 'floating-elements';
            
            // Create 3 floating elements
            for (let i = 0; i < 3; i++) {
                const element = document.createElement('div');
                element.className = `floating-element-${i + 1}`;
                element.innerHTML = icons[i] || icons[0];
                element.style.cssText = `
                    position: fixed;
                    z-index: 1;
                    opacity: 0.08;
                    pointer-events: none;
                    font-size: ${6 + i * 2}rem;
                    color: ${i === 0 ? '#D4AF37' : i === 1 ? '#2C5F2D' : '#E8491D'};
                    animation: floatingElements ${20 + i * 5}s ease-in-out infinite ${i % 2 ? 'reverse' : 'normal'};
                `;
                container.appendChild(element);
            }
            
            document.body.appendChild(container);
        }

        function createParticles() {
            const particlesContainer = document.createElement('div');
            particlesContainer.className = 'particles';
            
            for (let i = 0; i < 15; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.cssText = `
                    width: ${Math.random() * 20 + 5}px;
                    height: ${Math.random() * 20 + 5}px;
                    left: ${Math.random() * 100}%;
                    background: rgba(${i % 3 === 0 ? '212, 175, 55' : i % 3 === 1 ? '44, 95, 45' : '232, 73, 29'}, ${Math.random() * 0.05 + 0.03});
                    animation-delay: ${Math.random() * 20}s;
                    animation-duration: ${Math.random() * 10 + 15}s;
                `;
                particlesContainer.appendChild(particle);
            }
            
            document.body.appendChild(particlesContainer);
        }

        function animateGalleryItemsOnScroll() {
            const galleryItems = document.querySelectorAll('.gallery-item');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        entry.target.style.animationDelay = `${entry.target.dataset.index * 0.05}s`;
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            });
            
            galleryItems.forEach((item) => {
                observer.observe(item);
            });
        }

        // Enhanced modal opening effect
        const imageModal = document.getElementById('imageModal');
        if (imageModal) {
            imageModal.addEventListener('show.bs.modal', function() {
                document.body.classList.add('modal-open');
            });
            
            imageModal.addEventListener('hidden.bs.modal', function() {
                document.body.classList.remove('modal-open');
            });
        }

        // Add CSS for floating elements animation if not already present
        if (!document.querySelector('style[data-floating-animation]')) {
            const style = document.createElement('style');
            style.setAttribute('data-floating-animation', '');
            style.textContent = `
                @keyframes floatingElements {
                    0%, 100% {
                        transform: translateY(0) translateX(0) rotate(0deg);
                    }
                    25% {
                        transform: translateY(-40px) translateX(20px) rotate(90deg);
                    }
                    50% {
                        transform: translateY(0) translateX(-20px) rotate(180deg);
                    }
                    75% {
                        transform: translateY(40px) translateX(10px) rotate(270deg);
                    }
                }
            `;
            document.head.appendChild(style);
        }
    </script>
</body>
</html>