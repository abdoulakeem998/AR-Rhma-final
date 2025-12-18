<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$stmt = $pdo->query("SELECT * FROM open_roles WHERE status = 'open' ORDER BY created_at DESC");
$roles = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer Opportunities - AR-Rahma</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
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
        
        .card {
            border-radius: 10px;
            border: 1px solid rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background-color: rgba(255, 255, 255, 0.9);
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .badge-primary {
            background-color: #2C5F2D;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
        }
        
        .hero-section {
            background: linear-gradient(135deg, rgba(44, 95, 45, 0.8) 0%, rgba(33, 82, 39, 0.8) 100%);
            color: white;
            border-radius: 0 0 20px 20px;
            margin-bottom: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .hero-section h1 {
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.2);
        }
        
        .hero-section .lead {
            font-size: 1.2rem;
            opacity: 0.9;
        }
        
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
        
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            padding: 8px 20px;
            border-radius: 5px;
            font-weight: 500;
        }
        
        .empty-state-icon {
            font-size: 4rem;
            color: #ccc;
            margin-bottom: 1rem;
        }
        
        .text-muted {
            color: #6c757d !important;
        }
        
        .card-title {
            color: #2C5F2D;
            font-weight: 600;
        }
        
        .bi-geo-alt, .bi-calendar {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <section class="hero-section" style="padding: 3rem 0;">
        <div class="container text-center">
            <h1>Volunteer Opportunities</h1>
            <p class="lead">Join us in making a difference in our community</p>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <?php if (empty($roles)): ?>
            <div class="text-center py-5">
                <i class="bi bi-briefcase empty-state-icon"></i>
                <h4 class="mt-3">No open positions at the moment</h4>
                <p>Check back later for new opportunities</p>
            </div>
            <?php else: ?>
            <div class="row">
                <?php foreach ($roles as $role): ?>
                <div class="col-lg-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="card-title"><?php echo htmlspecialchars($role['title']); ?></h5>
                                <span class="badge badge-primary"><?php echo ucfirst($role['type']); ?></span>
                            </div>
                            <p class="card-text"><?php echo truncateText($role['description'], 150); ?></p>
                            
                            <div class="role-details mb-3">
                                <div class="mb-2">
                                    <small class="text-muted">
                                        <i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($role['location'] ?? 'Flexible'); ?>
                                    </small>
                                </div>
                                <?php if ($role['deadline']): ?>
                                <div class="mb-2">
                                    <small class="text-muted">
                                        <i class="bi bi-calendar"></i> Deadline: <?php echo formatDate($role['deadline']); ?>
                                    </small>
                                </div>
                                <?php endif; ?>
                                
                                <?php if (isset($role['commitment']) && !empty($role['commitment'])): ?>
                                <div class="mb-2">
                                    <small class="text-muted">
                                        <i class="bi bi-clock"></i> Commitment: <?php echo htmlspecialchars($role['commitment']); ?>
                                    </small>
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="apply-section">
                                <?php if (isLoggedIn()): ?>
                                    <?php if (hasApplied(getCurrentUserId(), $role['id'])): ?>
                                    <button class="btn btn-secondary" disabled>
                                        <i class="bi bi-check-circle"></i> Already Applied
                                    </button>
                                    <?php else: ?>
                                    <a href="apply.php?role_id=<?php echo $role['id']; ?>" class="btn btn-primary">
                                        <i class="bi bi-file-text"></i> Apply Now
                                    </a>
                                    <?php endif; ?>
                                <?php else: ?>
                                <a href="login.php" class="btn btn-primary">
                                    <i class="bi bi-box-arrow-in-right"></i> Login to Apply
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Add animation to cards when they come into view
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                }
            });
        }, observerOptions);
        
        // Observe all cards
        document.querySelectorAll('.card').forEach(card => {
            observer.observe(card);
        });
        
        // Add some interactivity to buttons
        document.querySelectorAll('.btn-primary').forEach(button => {
            button.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
            });
            button.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    </script>
</body>
</html>