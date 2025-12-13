<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$stmt = $pdo->query("SELECT * FROM team_members WHERE status = 'active' ORDER BY display_order ASC");
$team_members = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Team - AR-Rahma</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Ensure background image shows */
        .hero-team {
            background: linear-gradient(rgba(44, 95, 45, 0.85), rgba(26, 58, 27, 0.9)), 
                        url('https://images.unsplash.com/photo-1529156069898-49953e39b3ac?w=1920&h=600&fit=crop&q=80') center/cover !important;
            background-size: cover !important;
            background-position: center !important;
            min-height: 500px;
            position: relative;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <!-- Hero Section with Background Image -->
    <section class="hero-section hero-team">
        <div class="hero-overlay"></div>
        <div class="container text-center hero-content">
            <div class="hero-icon">
                <i class="bi bi-people"></i>
            </div>
            <h1 class="hero-title">Our Team</h1>
            <p class="hero-subtitle">Meet the dedicated individuals behind AR-Rahma</p>
        </div>
    </section>

    <!-- Team Section -->
    <section class="section">
        <div class="container">
            <?php if (empty($team_members)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-people" style="font-size: 5rem; color: var(--border-color);"></i>
                    <h3 class="mt-3">No Team Members Yet</h3>
                    <p class="text-muted">Check back soon to meet our dedicated team.</p>
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($team_members as $member): ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 text-center">
                            <div class="card-body p-4">
                                <?php if ($member['photo_url']): ?>
                                <img src="<?php echo htmlspecialchars($member['photo_url']); ?>" 
                                     alt="<?php echo htmlspecialchars($member['full_name']); ?>"
                                     class="rounded-circle mx-auto d-block mb-3"
                                     style="width: 150px; height: 150px; object-fit: cover; border: 4px solid var(--primary-color);"
                                     onerror="this.src='assets/images/placeholder.jpg'">
                                <?php else: ?>
                                <div class="rounded-circle mx-auto d-block mb-3" 
                                     style="width: 150px; height: 150px; background: var(--light-bg); display: flex; align-items: center; justify-content: center; border: 4px solid var(--border-color);">
                                    <i class="bi bi-person" style="font-size: 4rem; color: var(--light-text);"></i>
                                </div>
                                <?php endif; ?>
                                
                                <h5 class="mb-2"><?php echo htmlspecialchars($member['full_name']); ?></h5>
                                <p class="text-muted mb-3" style="color: var(--secondary-color) !important; font-weight: 600;">
                                    <?php echo htmlspecialchars($member['position']); ?>
                                </p>
                                
                                <?php if ($member['bio']): ?>
                                <p class="small text-muted mb-3"><?php echo truncateText($member['bio'], 150); ?></p>
                                <?php endif; ?>
                                
                                <div class="d-flex gap-2 justify-content-center">
                                    <?php if ($member['email']): ?>
                                    <a href="mailto:<?php echo htmlspecialchars($member['email']); ?>" 
                                       class="btn btn-sm btn-outline-primary" 
                                       title="Email <?php echo htmlspecialchars($member['full_name']); ?>">
                                        <i class="bi bi-envelope"></i> Email
                                    </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($member['phone']): ?>
                                    <a href="tel:<?php echo htmlspecialchars($member['phone']); ?>" 
                                       class="btn btn-sm btn-outline-primary"
                                       title="Call <?php echo htmlspecialchars($member['full_name']); ?>">
                                        <i class="bi bi-telephone"></i> Call
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
    <script src="assets/js/main.js"></script>
</body>
</html>