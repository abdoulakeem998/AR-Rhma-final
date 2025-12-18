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
</head>
<body>  
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
    <section class="section bg-light-gradient">
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
                            <!-- Impact Stats -->
                            <div class="row text-center mb-5">
                                <div class="col-md-4 mb-4">
                                    <div class="counter-animation">
                                        <h3 class="counter" data-count="1250">1250+</h3>
                                        <p class="text-muted mb-0">Lives Impacted</p>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <div class="counter-animation">
                                        <h3 class="counter" data-count="85">85+</h3>
                                        <p class="text-muted mb-0">Projects Completed</p>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <div class="counter-animation">
                                        <h3 class="counter" data-count="250">250+</h3>
                                        <p class="text-muted mb-0">Volunteers</p>
                                    </div>
                                </div>
                            </div>
                            
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
                                    <div class="detail-value">
                                        Bank of Africa Niger (BOA Niger)
                                        <button class="copy-btn ms-2" data-text="Bank of Africa Niger (BOA Niger)">
                                            <i class="bi bi-copy"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Account Name:</span>
                                    <div class="detail-value">
                                        AR-Rahma Association
                                        <button class="copy-btn ms-2" data-text="AR-Rahma Association">
                                            <i class="bi bi-copy"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Account Number:</span>
                                    <div class="detail-value">
                                        227-08-45621-78
                                        <button class="copy-btn ms-2" data-text="227-08-45621-78">
                                            <i class="bi bi-copy"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">SWIFT Code:</span>
                                    <div class="detail-value">
                                        AFRINERX
                                        <button class="copy-btn ms-2" data-text="AFRINERX">
                                            <i class="bi bi-copy"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Branch:</span>
                                    <div class="detail-value">
                                        Niamey Central Branch
                                        <button class="copy-btn ms-2" data-text="Niamey Central Branch">
                                            <i class="bi bi-copy"></i>
                                        </button>
                                    </div>
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
                                        <p class="mb-0 mobile-number">
                                            +227 96 12 34 56
                                            <button class="copy-btn ms-2" data-text="+227 96 12 34 56">
                                                <i class="bi bi-copy"></i>
                                            </button>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mobile-money-card moov-money">
                                        <i class="bi bi-phone-fill mobile-icon"></i>
                                        <h5 class="mobile-provider">Moov Money</h5>
                                        <p class="mb-2 text-muted">Send to this number:</p>
                                        <p class="mb-0 mobile-number">
                                            +227 90 87 65 43
                                            <button class="copy-btn ms-2" data-text="+227 90 87 65 43">
                                                <i class="bi bi-copy"></i>
                                            </button>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Donation Progress -->
                            <div class="mt-5 p-4 bg-light rounded-3">
                                <h5 class="mb-3">Monthly Donation Goal</h5>
                                <div class="progress mb-2">
                                    <div class="progress-bar" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                                        <span class="visually-hidden">75% Complete</span>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">₣ 7,500 collected</span>
                                    <span class="text-muted">Goal: ₣ 10,000</span>
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
                            <div class="contact-info-box subtle">
                                <h4 class="mb-4">
                                    <i class="bi bi-envelope-heart"></i> Contact Us for Confirmation
                                </h4>
                                <div class="row justify-content-center">
                                    <div class="col-md-8">
                                        <p class="contact-item">
                                            <i class="bi bi-envelope"></i> 
                                            <strong>Email:</strong> <?php echo htmlspecialchars(getSiteSetting('contact_email', 'contact@ar-rahma.org')); ?>
                                            <button class="copy-btn ms-2" data-text="<?php echo htmlspecialchars(getSiteSetting('contact_email', 'contact@ar-rahma.org')); ?>">
                                                <i class="bi bi-copy"></i>
                                            </button>
                                        </p>
                                        <p class="contact-item">
                                            <i class="bi bi-telephone"></i> 
                                            <strong>Phone:</strong> <?php echo htmlspecialchars(getSiteSetting('contact_phone', '+227 XX XX XX XX')); ?>
                                            <button class="copy-btn ms-2" data-text="<?php echo htmlspecialchars(getSiteSetting('contact_phone', '+227 XX XX XX XX')); ?>">
                                                <i class="bi bi-copy"></i>
                                            </button>
                                        </p>
                                        <p class="contact-item mb-0">
                                            <i class="bi bi-geo-alt"></i> 
                                            <strong>Address:</strong> <?php echo htmlspecialchars(getSiteSetting('contact_address', 'Niamey, Niger')); ?>
                                            <button class="copy-btn ms-2" data-text="<?php echo htmlspecialchars(getSiteSetting('contact_address', 'Niamey, Niger')); ?>">
                                                <i class="bi bi-copy"></i>
                                            </button>
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

                            <!-- Testimonials -->
                            <div class="mt-5">
                                <h4 class="section-heading text-center mb-4">
                                    <i class="bi bi-chat-heart-fill"></i> Donor Testimonials
                                </h4>
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="card testimonial-card">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="rounded-circle bg-primary text-white p-3 me-3">
                                                        <i class="bi bi-person-fill"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">Fatima A.</h6>
                                                        <small class="text-muted">Regular Donor</small>
                                                    </div>
                                                </div>
                                                <p class="mb-0">"Seeing the impact of my donations through AR-Rahma's updates gives me peace. Every franc counts!"</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card testimonial-card">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="rounded-circle bg-success text-white p-3 me-3">
                                                        <i class="bi bi-person-fill"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">Mohammed K.</h6>
                                                        <small class="text-muted">Corporate Sponsor</small>
                                                    </div>
                                                </div>
                                                <p class="mb-0">"Transparent and effective. AR-Rahma ensures every donation reaches those who need it most."</p>
                                            </div>
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
                    entry.target.style.animationPlayState = 'running';
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
        
        // Observe impact items
        document.querySelectorAll('.impact-item').forEach(item => {
            observer.observe(item);
        });
        
        // Observe contact items
        document.querySelectorAll('.contact-item').forEach(item => {
            observer.observe(item);
        });
        
        // Animate counters
        function animateCounters() {
            const counters = document.querySelectorAll('.counter');
            counters.forEach(counter => {
                const target = parseInt(counter.getAttribute('data-count'));
                const increment = target / 100;
                let current = 0;
                
                const updateCounter = () => {
                    if (current < target) {
                        current += increment;
                        counter.textContent = Math.floor(current) + "+";
                        setTimeout(updateCounter, 20);
                    } else {
                        counter.textContent = target + "+";
                    }
                };
                
                const counterObserver = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            updateCounter();
                            counterObserver.unobserve(entry.target);
                        }
                    });
                });
                
                counterObserver.observe(counter);
            });
        }
        
        // Copy to clipboard functionality
        document.querySelectorAll('.copy-btn').forEach(button => {
            button.addEventListener('click', function() {
                const text = this.getAttribute('data-text');
                navigator.clipboard.writeText(text).then(() => {
                    // Show success state
                    const icon = this.querySelector('i');
                    const originalClass = icon.className;
                    icon.className = 'bi bi-check';
                    this.classList.add('copied');
                    
                    // Reset after 2 seconds
                    setTimeout(() => {
                        icon.className = originalClass;
                        this.classList.remove('copied');
                    }, 2000);
                    
                    // Show toast notification
                    showToast('Copied to clipboard!');
                }).catch(err => {
                    console.error('Failed to copy: ', err);
                    showToast('Failed to copy. Please try again.', 'error');
                });
            });
        });
        
        // Toast notification function
        function showToast(message, type = 'success') {
            // Remove existing toast
            const existingToast = document.querySelector('.custom-toast');
            if (existingToast) {
                existingToast.remove();
            }
            
            // Create toast
            const toast = document.createElement('div');
            toast.className = `custom-toast alert alert-${type === 'error' ? 'danger' : 'success'} position-fixed`;
            toast.style.cssText = `
                top: 20px;
                right: 20px;
                z-index: 9999;
                animation: fadeInDown 0.5s ease-out;
                box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            `;
            toast.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="bi ${type === 'error' ? 'bi-x-circle' : 'bi-check-circle'} me-2"></i>
                    <span>${message}</span>
                </div>
            `;
            
            document.body.appendChild(toast);
            
            // Remove toast after 3 seconds
            setTimeout(() => {
                toast.style.animation = 'fadeOut 0.5s ease-out';
                setTimeout(() => toast.remove(), 500);
            }, 3000);
        }
        
        // Initialize animations on load
        document.addEventListener('DOMContentLoaded', function() {
            createParticles();
            createSparkles();
            animateCounters();
            
            // Add parallax effect to hero title
            const heroTitle = document.querySelector('.hero-title');
            window.addEventListener('scroll', function() {
                const scrolled = window.pageYOffset;
                const rate = scrolled * -0.3;
                if (heroTitle) {
                    heroTitle.style.transform = `translateY(${rate}px)`;
                }
            });
            
            // Add ripple effect to donation card
            const donationCard = document.querySelector('.donation-card');
            donationCard.addEventListener('mouseenter', function() {
                const ripple = document.createElement('div');
                ripple.style.cssText = `
                    position: absolute;
                    border-radius: 50%;
                    background: rgba(212, 175, 55, 0.2);
                    transform: scale(0);
                    animation: ripple 0.6s linear;
                    pointer-events: none;
                `;
                
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = event.clientX - rect.left - size / 2;
                const y = event.clientY - rect.top - size / 2;
                
                ripple.style.width = ripple.style.height = size + 'px';
                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';
                
                this.appendChild(ripple);
                
                // Remove ripple after animation
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
            
            // Animate progress bar
            const progressBar = document.querySelector('.progress-bar');
            if (progressBar) {
                setTimeout(() => {
                    progressBar.style.transition = 'width 2s ease-in-out';
                    progressBar.style.width = '75%';
                }, 1000);
            }
        });
        
        // Add keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                // Close any open toasts
                const toasts = document.querySelectorAll('.custom-toast');
                toasts.forEach(toast => toast.remove());
            }
        });
        
        // Add touch support for mobile
        let touchStartY = 0;
        let touchEndY = 0;
        
        document.addEventListener('touchstart', e => {
            touchStartY = e.changedTouches[0].screenY;
        });
        
        document.addEventListener('touchend', e => {
            touchEndY = e.changedTouches[0].screenY;
            handleSwipe();
        });
        
        function handleSwipe() {
            const swipeLength = touchEndY - touchStartY;
            
            // Swipe up to scroll down
            if (swipeLength < -50) {
                document.querySelector('.donation-card').scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        }
    </script>
</body>
</html>