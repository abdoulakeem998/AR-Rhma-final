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
        /* Floating decorative elements */
        .floating-elements {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            pointer-events: none;
            z-index: 1;
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
            z-index: 2;
        }
        
        /* Ensure content is above background elements */
        .container, .hero-content, .section {
            position: relative;
            z-index: 20;
        }
        
        /* Ripple effect styles */
        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.4);
            transform: scale(0);
            animation: ripple 0.6s linear;
            pointer-events: none;
        }
        
        /* Loading animation */
        .loading-wave {
            display: flex;
            justify-content: center;
            align-items: flex-end;
            height: 100px;
            gap: 8px;
        }
        
        .loading-bar {
            width: 10px;
            height: 40px;
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            border-radius: 5px;
            animation: wave 1s ease-in-out infinite;
        }
        
        .loading-bar:nth-child(2) { animation-delay: 0.1s; }
        .loading-bar:nth-child(3) { animation-delay: 0.2s; }
        .loading-bar:nth-child(4) { animation-delay: 0.3s; }
        .loading-bar:nth-child(5) { animation-delay: 0.4s; }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <!-- Background Elements -->
    <div class="background-elements">
        <div class="star"></div>
        <div class="star"></div>
        <div class="star"></div>
        <div class="star"></div>
        <div class="star"></div>
        <div class="star"></div>
        <div class="star"></div>
        <div class="star"></div>
    </div>
    
    <!-- Mosque Silhouette -->
    <div class="mosque-silhouette">
        <i class="bi bi-building"></i>
    </div>
    
    <!-- Floating Shapes -->
    <div class="floating-shapes">
        <div class="floating-shape circle" style="top: 20%; left: 15%;"></div>
        <div class="floating-shape triangle" style="top: 40%; right: 20%;"></div>
        <div class="floating-shape square" style="bottom: 30%; left: 10%;"></div>
    </div>

    <!-- Hero Section -->
    <section class="hero-section hero-gallery">
        <div class="hero-overlay"></div>
        
        <!-- Floating Icons -->
        <div class="floating-icon-1 animate-star-twinkle">
            <i class="bi bi-star-fill"></i>
        </div>
        <div class="floating-icon-2 animate-star-twinkle">
            <i class="bi bi-heart-fill"></i>
        </div>
        <div class="floating-icon-3 animate-star-twinkle">
            <i class="bi bi-moon-stars-fill"></i>
        </div>
        
        <div class="container text-center hero-content">
            <div class="hero-icon">
                <i class="bi bi-images"></i>
            </div>
            <h1 class="hero-title animate-gradient-flow">Photo Gallery</h1>
            <p class="hero-subtitle">Capturing moments of hope, compassion, and faith</p>
            
            <!-- CTA Button -->
            <a href="#gallery-section" class="gallery-cta-button animate-breathing">
                <i class="bi bi-arrow-down"></i>
                Explore Our Gallery
            </a>
            
            <!-- Scroll Indicator -->
            <div class="scroll-indicator" onclick="scrollToGallery()">
                <i class="bi bi-chevron-down"></i>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="section gallery-section-bg" id="gallery-section">
        <div class="container">
            <div class="section-title text-center mb-5">
                <h2 class="animate-text-glow">Memories of Mercy</h2>
                <p class="section-subtitle animate-fade-up">Every picture tells a story of hope and compassion</p>
            </div>
            
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
                             data-index="<?php echo $index; ?>"
                             onclick="createRipple(event)">
                            <img src="<?php echo htmlspecialchars($image['image_url']); ?>" 
                                 alt="<?php echo htmlspecialchars($image['title']); ?>"
                                 loading="lazy"
                                 class="animate-flip-in">
                            <div class="gallery-overlay">
                                <p class="gallery-title"><?php echo htmlspecialchars($image['title']); ?></p>
                            </div>
                            <i class="bi bi-zoom-in gallery-icon"></i>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Image Count -->
                <div class="text-center mt-5 gallery-count">
                    <p class="text-muted">
                        <i class="bi bi-images"></i> 
                        Showing <span><?php echo count($images); ?></span> beautiful moments
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
                    
                    // Add loading animation
                    modalImage.style.opacity = '0';
                    setTimeout(() => {
                        modalImage.style.opacity = '1';
                        modalImage.style.transition = 'opacity 0.3s ease';
                    }, 100);
                });
            }
            
            // Create animated background elements
            createBackgroundElements();
            
            // Create particles
            createParticles();
            
            // Animate gallery items on scroll
            animateGalleryItemsOnScroll();
            
            // Add hover effects
            addHoverEffects();
            
            // Initialize animations
            initAnimations();
        });

        function createBackgroundElements() {
            // Create stars
            const starsContainer = document.querySelector('.background-elements');
            for (let i = 0; i < 15; i++) {
                const star = document.createElement('div');
                star.className = 'star';
                star.style.cssText = `
                    position: absolute;
                    background: rgba(255, 255, 255, ${0.5 + Math.random() * 0.5});
                    border-radius: 50%;
                    width: ${1 + Math.random() * 3}px;
                    height: ${1 + Math.random() * 3}px;
                    top: ${Math.random() * 100}%;
                    left: ${Math.random() * 100}%;
                    animation: starTwinkle ${2 + Math.random() * 3}s ease-in-out infinite ${Math.random() * 2}s;
                `;
                starsContainer.appendChild(star);
            }
        }

        function createParticles() {
            const particlesContainer = document.createElement('div');
            particlesContainer.className = 'particles';
            
            for (let i = 0; i < 25; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.cssText = `
                    width: ${Math.random() * 25 + 5}px;
                    height: ${Math.random() * 25 + 5}px;
                    left: ${Math.random() * 100}%;
                    top: ${Math.random() * 100}%;
                    background: rgba(${i % 3 === 0 ? '212, 175, 55' : i % 3 === 1 ? '44, 95, 45' : '232, 73, 29'}, ${Math.random() * 0.08 + 0.02});
                    animation-delay: ${Math.random() * 20}s;
                    animation-duration: ${Math.random() * 15 + 20}s;
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
                        
                        // Add jelly animation when visible
                        setTimeout(() => {
                            entry.target.style.animation = 'jelly 0.8s ease-in-out';
                        }, entry.target.dataset.index * 100 + 500);
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

        function addHoverEffects() {
            const galleryItems = document.querySelectorAll('.gallery-item');
            
            galleryItems.forEach(item => {
                item.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-15px) scale(1.05) rotate(1deg)';
                    this.style.boxShadow = '0 20px 50px rgba(44, 95, 45, 0.4), 0 0 30px rgba(212, 175, 55, 0.3)';
                    
                    // Add glow effect
                    const glow = document.createElement('div');
                    glow.style.cssText = `
                        position: absolute;
                        top: 0;
                        left: 0;
                        right: 0;
                        bottom: 0;
                        border-radius: 15px;
                        background: radial-gradient(circle at center, rgba(212, 175, 55, 0.1), transparent 70%);
                        z-index: -1;
                        animation: glow 2s ease-in-out infinite;
                    `;
                    this.appendChild(glow);
                });
                
                item.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1) rotate(0deg)';
                    this.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.1), 0 0 0 1px rgba(255, 255, 255, 0.1)';
                    
                    // Remove glow effect
                    const glow = this.querySelector('div[style*="radial-gradient"]');
                    if (glow) glow.remove();
                });
            });
        }

        function createRipple(event) {
            const item = event.currentTarget;
            const rect = item.getBoundingClientRect();
            const x = event.clientX - rect.left;
            const y = event.clientY - rect.top;
            
            const ripple = document.createElement('div');
            ripple.className = 'ripple';
            ripple.style.cssText = `
                left: ${x}px;
                top: ${y}px;
                width: 5px;
                height: 5px;
                background: radial-gradient(circle, rgba(255, 255, 255, 0.6), transparent);
            `;
            
            item.appendChild(ripple);
            
            setTimeout(() => {
                ripple.style.width = '300px';
                ripple.style.height = '300px';
                ripple.style.opacity = '0';
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            }, 10);
        }

        function scrollToGallery() {
            const gallerySection = document.getElementById('gallery-section');
            gallerySection.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
            
            // Add bounce animation to gallery items
            setTimeout(() => {
                const items = document.querySelectorAll('.gallery-item');
                items.forEach((item, index) => {
                    setTimeout(() => {
                        item.style.animation = 'bounce 0.6s ease-in-out';
                        setTimeout(() => {
                            item.style.animation = '';
                        }, 600);
                    }, index * 100);
                });
            }, 500);
        }

        function initAnimations() {
            // Animate section title
            const title = document.querySelector('.section-title h2');
            if (title) {
                setTimeout(() => {
                    title.style.animation = 'typewriter 2s steps(40) 1s both';
                }, 1000);
            }
            
            // Add floating animation to gallery count
            const count = document.querySelector('.gallery-count');
            if (count) {
                setInterval(() => {
                    count.style.transform = 'translateY(-5px)';
                    setTimeout(() => {
                        count.style.transform = 'translateY(0)';
                    }, 500);
                }, 3000);
            }
        }

        // Enhanced modal opening effect
        const imageModal = document.getElementById('imageModal');
        if (imageModal) {
            imageModal.addEventListener('show.bs.modal', function() {
                document.body.classList.add('modal-open');
                
                // Add background blur
                document.querySelector('.gallery-section-bg').style.filter = 'blur(5px)';
                document.querySelector('.gallery-section-bg').style.transition = 'filter 0.3s ease';
            });
            
            imageModal.addEventListener('hidden.bs.modal', function() {
                document.body.classList.remove('modal-open');
                
                // Remove background blur
                document.querySelector('.gallery-section-bg').style.filter = 'none';
            });
        }

        // Add CSS for new animations if not already present
        if (!document.querySelector('style[data-custom-animations]')) {
            const style = document.createElement('style');
            style.setAttribute('data-custom-animations', '');
            style.textContent = `
                @keyframes ripple {
                    0% {
                        transform: scale(0);
                        opacity: 1;
                    }
                    100% {
                        transform: scale(60);
                        opacity: 0;
                    }
                }
                
                @keyframes starTwinkle {
                    0%, 100% {
                        opacity: 0.3;
                        transform: scale(1);
                    }
                    50% {
                        opacity: 1;
                        transform: scale(1.1);
                    }
                }
                
                @keyframes backgroundZoom {
                    0%, 100% {
                        transform: scale(1);
                    }
                    50% {
                        transform: scale(1.05);
                    }
                }
                
                @keyframes patternShift {
                    0% {
                        background-position: 0 0;
                    }
                    100% {
                        background-position: 200px 200px;
                    }
                }
                
                @keyframes gradientFlow {
                    0% {
                        background-position: 0% 50%;
                    }
                    50% {
                        background-position: 100% 50%;
                    }
                    100% {
                        background-position: 0% 50%;
                    }
                }
                
                @keyframes textGlow {
                    0%, 100% {
                        text-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
                    }
                    50% {
                        text-shadow: 0 0 20px rgba(255, 215, 0, 0.8);
                    }
                }
                
                @keyframes jelly {
                    0%, 100% {
                        transform: scale(1, 1);
                    }
                    25% {
                        transform: scale(0.9, 1.1);
                    }
                    50% {
                        transform: scale(1.1, 0.9);
                    }
                    75% {
                        transform: scale(0.95, 1.05);
                    }
                }
                
                @keyframes flipIn {
                    0% {
                        transform: perspective(400px) rotateY(90deg);
                        opacity: 0;
                    }
                    100% {
                        transform: perspective(400px) rotateY(0deg);
                        opacity: 1;
                    }
                }
            `;
            document.head.appendChild(style);
        }
    </script>
</body>
</html>