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
        
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.85);
            z-index: -1;
        }
        
        .section, .hero-section {
            position: relative;
            z-index: 1;
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
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 2rem;
            margin: 2rem 0;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        /* Ensure content is above background elements */
        .container, .hero-content, .section {
            position: relative;
            z-index: 2;
        }
        
        /* Gallery Hero Section */
        .hero-section {
            background: linear-gradient(135deg, rgba(44, 95, 45, 0.8) 0%, rgba(33, 82, 39, 0.8) 100%);
            color: white;
            border-radius: 0 0 20px 20px;
            margin-bottom: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            padding: 4rem 0;
        }
        
        .hero-section h1 {
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.2);
            font-size: 3rem;
        }
        
        .hero-section .lead {
            font-size: 1.2rem;
            opacity: 0.9;
        }
        
        .hero-icon {
            font-size: 5rem;
            margin-bottom: 1rem;
            animation: bounce 3s ease-in-out infinite;
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
        
        /* Gallery Items */
        .gallery-item {
            border-radius: 10px;
            border: 1px solid rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background-color: rgba(255, 255, 255, 0.9);
            overflow: hidden;
            position: relative;
            cursor: pointer;
            height: 250px;
        }
        
        .gallery-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .gallery-item:hover img {
            transform: scale(1.05);
        }
        
        .gallery-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
            color: white;
            padding: 1rem;
            opacity: 0;
            transition: opacity 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
        }
        
        .gallery-item:hover .gallery-overlay {
            opacity: 1;
        }
        
        .gallery-title {
            color: white;
            font-weight: 600;
            margin: 0;
            font-size: 0.9rem;
        }
        
        /* Gallery Actions */
        .gallery-actions {
            position: absolute;
            top: 10px;
            right: 10px;
            display: flex;
            gap: 5px;
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 10;
        }
        
        .gallery-item:hover .gallery-actions {
            opacity: 1;
        }
        
        .action-btn {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2C5F2D;
            font-size: 1rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        
        .action-btn:hover {
            background: #2C5F2D;
            color: white;
            transform: scale(1.1);
        }
        
        .download-btn {
            background: rgba(255, 255, 255, 0.9);
            color: #2C5F2D;
        }
        
        .download-btn:hover {
            background: #2C5F2D;
            color: white;
        }
        
        .gallery-icon {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 2rem;
            opacity: 0;
            transition: all 0.3s ease;
            background: rgba(44, 95, 45, 0.8);
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .gallery-item:hover .gallery-icon {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1.1);
        }
        
        /* Image Count */
        .gallery-count {
            background-color: #2C5F2D;
            color: white;
            padding: 10px 20px;
            border-radius: 20px;
            font-size: 0.9rem;
            display: inline-block;
            margin-top: 2rem;
        }
        
        .gallery-count i {
            margin-right: 8px;
        }
        
        .gallery-count span {
            font-weight: 700;
        }
        
        /* Empty State */
        .gallery-empty-state {
            padding: 4rem 1rem;
        }
        
        .gallery-empty-state i {
            font-size: 5rem;
            color: #2C5F2D;
            opacity: 0.3;
        }
        
        .gallery-empty-state h3 {
            color: #2C5F2D;
            font-weight: 600;
            margin: 1rem 0;
        }
        
        /* Modal Styles */
        .modal-content {
            border-radius: 15px;
            border: none;
            overflow: hidden;
        }
        
        .modal-header {
            background: linear-gradient(135deg, rgba(44, 95, 45, 0.9) 0%, rgba(33, 82, 39, 0.9) 100%);
            border-bottom: none;
            padding: 1rem 1.5rem;
        }
        
        .modal-title {
            color: white;
            font-weight: 600;
            font-size: 1.2rem;
        }
        
        .modal-actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        
        .modal-download-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 5px 15px;
            border-radius: 5px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 5px;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .modal-download-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
            transform: translateY(-2px);
        }
        
        .btn-close-white {
            filter: brightness(0) invert(1);
        }
        
        .modal-body {
            padding: 0;
        }
        
        .modal-image-wrapper {
            position: relative;
        }
        
        .modal-image-wrapper img {
            width: 100%;
            height: auto;
            display: block;
        }
        
        /* Scroll Indicator */
        .scroll-indicator {
            animation: bounce 2s infinite;
            margin-top: 2rem;
            cursor: pointer;
        }
        
        .scroll-indicator i {
            font-size: 2rem;
            color: white;
        }
        
        /* Button Styles */
        .btn-primary {
            background-color: #2C5F2D;
            border-color: #2C5F2D;
            padding: 8px 20px;
            border-radius: 5px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background-color: #1e3c1e;
            border-color: #1e3c1e;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(44, 95, 45, 0.3);
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-section h1 {
                font-size: 2rem;
            }
            
            .hero-icon {
                font-size: 3rem;
            }
            
            .gallery-item {
                height: 200px;
            }
            
            .gallery-section-bg {
                padding: 1rem;
                margin: 1rem 0;
            }
            
            .action-btn {
                width: 30px;
                height: 30px;
                font-size: 0.9rem;
            }
        }
        
        @media (max-width: 576px) {
            .gallery-item {
                height: 180px;
            }
            
            .hero-section {
                padding: 3rem 0;
            }
            
            .modal-download-btn {
                padding: 4px 10px;
                font-size: 0.8rem;
            }
        }
        
        /* Download Toast */
        .download-toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #2C5F2D;
            color: white;
            padding: 15px 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 9999;
            animation: slideIn 0.3s ease;
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        .download-toast i {
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="hero-section hero-gallery">
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
                    <?php foreach ($images as $index => $image): 
                        $filename = basename($image['image_url']);
                        $cleanTitle = preg_replace('/[^a-z0-9]/i', '-', strtolower($image['title']));
                        $downloadName = $cleanTitle . '-' . $filename;
                    ?>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="gallery-item" 
                             data-bs-toggle="modal" 
                             data-bs-target="#imageModal"
                             data-image="<?php echo htmlspecialchars($image['image_url']); ?>"
                             data-title="<?php echo htmlspecialchars($image['title']); ?>"
                             data-index="<?php echo $index; ?>"
                             data-download-url="<?php echo htmlspecialchars($image['image_url']); ?>"
                             data-download-name="<?php echo htmlspecialchars($downloadName); ?>">
                            
                            <!-- Download button on gallery item -->
                            <div class="gallery-actions">
                                <a href="<?php echo htmlspecialchars($image['image_url']); ?>" 
                                   class="action-btn download-btn" 
                                   download="<?php echo htmlspecialchars($downloadName); ?>"
                                   title="Download this image"
                                   onclick="downloadImage(event, '<?php echo htmlspecialchars($image['title']); ?>')">
                                    <i class="bi bi-download"></i>
                                </a>
                            </div>
                            
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
                <div class="text-center mt-4">
                    <div class="gallery-count">
                        <p class="mb-0">
                            <i class="bi bi-images"></i> 
                            Showing <span><?php echo count($images); ?></span> photos
                        </p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="modalImageTitle"></h5>
                    <div class="modal-actions">
                        <a href="#" id="modalDownloadBtn" class="modal-download-btn" download>
                            <i class="bi bi-download"></i> Download
                        </a>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="modal-image-wrapper">
                        <img id="modalImage" src="" alt="" class="img-fluid">
                    </div>
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
                    const downloadUrl = button.getAttribute('data-download-url');
                    const downloadName = button.getAttribute('data-download-name');
                    
                    const modalImage = document.getElementById('modalImage');
                    const modalTitle = document.getElementById('modalImageTitle');
                    const modalDownloadBtn = document.getElementById('modalDownloadBtn');
                    
                    modalImage.src = imageUrl;
                    modalImage.alt = imageTitle;
                    modalTitle.textContent = imageTitle;
                    
                    // Set download attributes
                    modalDownloadBtn.href = downloadUrl;
                    modalDownloadBtn.download = downloadName;
                    
                    // Update modal download button click handler
                    modalDownloadBtn.onclick = function(e) {
                        downloadImage(e, imageTitle, downloadUrl, downloadName);
                    };
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
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                        entry.target.style.animationDelay = `${entry.target.dataset.index * 0.05}s`;
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            });
            
            galleryItems.forEach((item) => {
                item.style.opacity = '0';
                item.style.transform = 'translateY(20px)';
                item.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
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

        // Download image function
        function downloadImage(event, title, url = null, filename = null) {
            event.preventDefault();
            event.stopPropagation();
            
            const downloadUrl = url || event.currentTarget.href;
            const downloadName = filename || event.currentTarget.download || 
                                generateFilename(title, downloadUrl);
            
            try {
                // Create a temporary link element
                const link = document.createElement('a');
                link.href = downloadUrl;
                link.download = downloadName;
                link.style.display = 'none';
                
                // Append to body, click, and remove
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                
                // Show download toast
                showDownloadToast(title);
                
                // Track download (optional - for analytics)
                trackDownload(title, downloadUrl);
                
            } catch (error) {
                console.error('Download failed:', error);
                alert('Sorry, could not download the image. Please try right-clicking and selecting "Save image as..."');
            }
            
            return false;
        }
        
        function generateFilename(title, url) {
            // Clean title for filename
            const cleanTitle = title.replace(/[^a-z0-9]/gi, '-').toLowerCase();
            
            // Extract extension from URL
            const urlParts = url.split('/');
            const lastPart = urlParts[urlParts.length - 1];
            const extension = lastPart.includes('.') ? lastPart.split('.').pop() : 'jpg';
            
            return `${cleanTitle}-${Date.now()}.${extension}`;
        }
        
        function showDownloadToast(title) {
            // Remove existing toast
            const existingToast = document.querySelector('.download-toast');
            if (existingToast) {
                existingToast.remove();
            }
            
            // Create toast
            const toast = document.createElement('div');
            toast.className = 'download-toast';
            toast.innerHTML = `
                <i class="bi bi-check-circle"></i>
                <div>
                    <strong>Download started!</strong>
                    <div style="font-size: 0.85rem;">"${title.substring(0, 30)}${title.length > 30 ? '...' : ''}"</div>
                </div>
            `;
            
            document.body.appendChild(toast);
            
            // Remove toast after 3 seconds
            setTimeout(() => {
                toast.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
        
        // Track download function (for analytics - optional)
        function trackDownload(title, url) {
            // You can implement Google Analytics or your own tracking here
            console.log('Download tracked:', { title, url, timestamp: new Date().toISOString() });
            
            // Example: Send to your server for tracking
            /*
            fetch('track_download.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    title: title,
                    url: url,
                    userAgent: navigator.userAgent,
                    timestamp: new Date().toISOString()
                })
            });
            */
        }
        
        // Add slideOut animation
        if (!document.querySelector('style[data-slideout-animation]')) {
            const style = document.createElement('style');
            style.setAttribute('data-slideout-animation', '');
            style.textContent = `
                @keyframes slideOut {
                    from {
                        transform: translateX(0);
                        opacity: 1;
                    }
                    to {
                        transform: translateX(100%);
                        opacity: 0;
                    }
                }
                
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
                
                @keyframes floatParticle {
                    0% {
                        transform: translateY(100vh) rotate(0deg);
                        opacity: 0;
                    }
                    10% {
                        opacity: 0.5;
                    }
                    90% {
                        opacity: 0.5;
                    }
                    100% {
                        transform: translateY(-100px) rotate(360deg);
                        opacity: 0;
                    }
                }
                
                .particle {
                    position: absolute;
                    border-radius: 50%;
                    animation: floatParticle 20s linear infinite;
                }
            `;
            document.head.appendChild(style);
        }
    </script>
</body>
</html>