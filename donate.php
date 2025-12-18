<?php
require_once 'config/database.php';
require_once 'includes/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support Our Mission - AR-Rahma</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Additional inline styles for enhanced effects */
        .sparkle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: white;
            border-radius: 50%;
            animation: sparkle 2s linear infinite;
            opacity: 0;
        }
        
        @keyframes sparkle {
            0%, 100% { opacity: 0; transform: scale(0) rotate(0deg); }
            50% { opacity: 1; transform: scale(1) rotate(180deg); }
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #FF6B6B, #FFD93D, #2C5F2D);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            background-size: 200% 200%;
            animation: gradientShift 3s ease infinite;
        }
    </style>
</head>
<body>
    <!-- Floating Particles -->
    <div class="donate-particles" id="particles"></div>
    
    <?php include 'includes/header.php'; ?>

    <!-- Beautiful Hero Section -->
    <section class="donate-hero">
        <div class="container text-center hero-content">
            <!-- Floating Heart Icon -->
            <div class="floating-heart mb-4">
                <i class="bi bi-heart-fill"></i>
            </div>
            
            <!-- Main Title -->
            <h1 class="hero-title gradient-text">Support Our Mission</h1>
            
            <!-- Beautiful Arabic Text -->
            <div class="arabic-text">
                الرَّاحِمُونَ يَرْحَمُهُمْ الرَّحْمَنُ ارْحَمُوا مَنْ فِي الْأَرْضِ يَرْحَمْكُمْ مَنْ فِي السَّمَاءِ
            </div>
            
            <!-- English Translation -->
            <div class="english-translation">
                <p>"The merciful will be shown mercy by the Most Merciful.</p>
                <p>Be merciful to those on the earth and the One in the heavens will have mercy upon you."</p>
                <p class="mt-3 hadith-reference">- Prophet Muhammad ﷺ (Sunan al-Tirmidhī)</p>
            </div>
            
            <!-- Scroll Indicator -->
            <div class="scroll-indicator mt-5">
                <i class="bi bi-chevron-down"></i>
            </div>
        </div>
    </section>

    <!-- Main Donation Content -->
    <section class="section" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-xl-9">
                    <!-- Main Donation Card -->
                    <div class="card donation-card">
                        <!-- Beautiful Card Header -->
                        <div class="card-header-custom">
                            <i class="bi bi-heart-fill"></i>
                            <h2 class="mb-0">Make a Donation</h2>
                            <p class="mb-0 mt-2">Every contribution makes a difference in someone's life</p>
                        </div>
                        
                        <!-- Card Body -->
                        <div class="card-body p-4 p-md-5">
                            <!-- Bank Transfer Section -->
                            <div class="text-center mb-5">
                                <h4 class="section-heading">
                                    <i class="bi bi-bank"></i> Bank Transfer Details
                                </h4>
                                <p class="text-muted">Transfer directly to our official bank account</p>
                            </div>
                            
                            <!-- Bank Details Box -->
                            <div class="bank-details-box">
                                <div class="detail-item">
                                    <span class="detail-label">Bank Name:</span>
                                    <div class="detail-value">Bank of Africa Niger (BOA Niger)</div>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Account Name:</span>
                                    <div class="detail-value">AR-Rahma Association</div>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Account Number:</span>
                                    <div class="detail-value">227-08-45621-78</div>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">SWIFT Code:</span>
                                    <div class="detail-value">AFRINERX</div>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Branch:</span>
                                    <div class="detail-value">Niamey Central Branch</div>
                                </div>
                            </div>

                            <!-- Mobile Money Section -->
                            <div class="text-center mt-5 mb-4">
                                <h4 class="section-heading">
                                    <i class="bi bi-phone"></i> Mobile Money (Niger)
                                </h4>
                                <p class="text-muted">Quick and convenient mobile donations</p>
                            </div>
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="mobile-money-card orange-money">
                                        <i class="bi bi-phone-fill mobile-icon"></i>
                                        <h5 class="mobile-provider">Orange Money</h5>
                                        <p class="mb-2 text-muted">Send to this number:</p>
                                        <p class="mb-0 mobile-number">+227 96 12 34 56</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mobile-money-card moov-money">
                                        <i class="bi bi-phone-fill mobile-icon"></i>
                                        <h5 class="mobile-provider">Moov Money</h5>
                                        <p class="mb-2 text-muted">Send to this number:</p>
                                        <p class="mb-0 mobile-number">+227 90 87 65 43</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Important Note -->
                            <div class="note-box">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-info-circle-fill note-icon"></i>
                                    <div class="ms-3">
                                        <h5 class="note-title">Important Note</h5>
                                        <p class="note-text">
                                            After making a donation, please send us the transaction reference via email or WhatsApp 
                                            so we can acknowledge and thank you properly. May Allah reward you abundantly!
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <div class="contact-info-box">
                                <h4 class="mb-4">
                                    <i class="bi bi-envelope-heart"></i> Contact Us for Confirmation
                                </h4>
                                <div class="row justify-content-center">
                                    <div class="col-md-8">
                                        <p class="contact-item">
                                            <i class="bi bi-envelope"></i> 
                                            <strong>Email:</strong> <?php echo htmlspecialchars(getSiteSetting('contact_email', 'contact@ar-rahma.org')); ?>
                                        </p>
                                        <p class="contact-item">
                                            <i class="bi bi-telephone"></i> 
                                            <strong>Phone:</strong> <?php echo htmlspecialchars(getSiteSetting('contact_phone', '+227 XX XX XX XX')); ?>
                                        </p>
                                        <p class="contact-item mb-0">
                                            <i class="bi bi-geo-alt"></i> 
                                            <strong>Address:</strong> <?php echo htmlspecialchars(getSiteSetting('contact_address', 'Niamey, Niger')); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Impact Section -->
                            <div class="impact-section">
                                <h4 class="text-center mb-5">
                                    <i class="bi bi-stars"></i> What Your Donation Supports
                                </h4>
                                <div class="row g-4">
                                    <div class="col-md-6 col-lg-4">
                                        <div class="impact-item">
                                            <i class="bi bi-heart-fill"></i>
                                            <span>Orphan Support Programs</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="impact-item">
                                            <i class="bi bi-wheelchair"></i>
                                            <span>Disability Care Services</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="impact-item">
                                            <i class="bi bi-basket-fill"></i>
                                            <span>Food Distribution</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="impact-item">
                                            <i class="bi bi-book-fill"></i>
                                            <span>Education Scholarships</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="impact-item">
                                            <i class="bi bi-heart-pulse-fill"></i>
                                            <span>Healthcare Assistance</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="impact-item">
                                            <i class="bi bi-lightning-fill"></i>
                                            <span>Emergency Relief</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Closing Hadith -->
                            <div class="closing-hadith-section">
                                <h5 class="closing-hadith">
                                    "Whoever relieves a believer's distress of the distressful aspects of this world, 
                                    Allah will rescue him from a difficulty of the difficulties of the Hereafter."
                                </h5>
                                <p class="text-white mt-3 hadith-source">- Prophet Muhammad ﷺ (Sahih Muslim)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Create floating particles
        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            const particleCount = 15;
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'donate-particle';
                particle.style.cssText = `
                    width: ${Math.random() * 100 + 50}px;
                    height: ${Math.random() * 100 + 50}px;
                    left: ${Math.random() * 100}%;
                    animation-delay: ${Math.random() * 20}s;
                    animation-duration: ${Math.random() * 10 + 15}s;
                    background: radial-gradient(circle, 
                        rgba(${i % 3 === 0 ? '212, 175, 55' : i % 3 === 1 ? '44, 95, 45' : '232, 73, 29'}, ${Math.random() * 0.2 + 0.1}) 0%, 
                        transparent 70%);
                `;
                particlesContainer.appendChild(particle);
            }
        }
        
        // Create sparkle effects
        function createSparkles() {
            const hero = document.querySelector('.donate-hero');
            const sparkleCount = 8;
            
            for (let i = 0; i < sparkleCount; i++) {
                const sparkle = document.createElement('div');
                sparkle.className = 'sparkle';
                sparkle.style.cssText = `
                    top: ${Math.random() * 100}%;
                    left: ${Math.random() * 100}%;
                    animation-delay: ${Math.random() * 3}s;
                    animation-duration: ${Math.random() * 2 + 1}s;
                `;
                hero.appendChild(sparkle);
            }
        }
        
        // Scroll to content
        document.querySelector('.scroll-indicator').addEventListener('click', function() {
            document.querySelector('.donation-card').scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        });
        
        // Add hover effect to cards
        document.querySelectorAll('.mobile-money-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-15px) scale(1.05)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
        
        // Animate detail items on scroll
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animated');
                }
            });
        }, {
            threshold: 0.2,
            rootMargin: '0px 0px -50px 0px'
        });
        
        // Observe all detail items
        document.querySelectorAll('.detail-item').forEach(item => {
            observer.observe(item);
        });
        
        // Initialize animations
        document.addEventListener('DOMContentLoaded', function() {
            createParticles();
            createSparkles();
            
            // Add scroll animation to hero title
            const heroTitle = document.querySelector('.hero-title');
            window.addEventListener('scroll', function() {
                const scrolled = window.pageYOffset;
                const rate = scrolled * -0.5;
                heroTitle.style.transform = `translateY(${rate}px)`;
            });
        });
        
        // Copy bank details to clipboard
        document.querySelectorAll('.detail-value').forEach(element => {
            element.addEventListener('click', function() {
                const text = this.textContent;
                navigator.clipboard.writeText(text).then(() => {
                    const originalText = this.textContent;
                    this.textContent = 'Copied! ✓';
                    this.style.color = '#28a745';
                    this.style.fontWeight = 'bold';
                    
                    setTimeout(() => {
                        this.textContent = originalText;
                        this.style.color = '';
                        this.style.fontWeight = '';
                    }, 2000);
                });
            });
        });
    </script>
</body>
</html>