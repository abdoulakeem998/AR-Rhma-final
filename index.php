<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

// Fetch featured activities
$stmt = $pdo->query("SELECT * FROM activities WHERE status = 'active' AND featured = 1 ORDER BY activity_date DESC LIMIT 3");
$featured_activities = $stmt->fetchAll();

// Fetch stats
$stmt = $pdo->query("SELECT SUM(beneficiaries) as total FROM activities WHERE status = 'active'");
$beneficiaries = $stmt->fetch()['total'] ?? 0;

$stmt = $pdo->query("SELECT COUNT(*) as total FROM activities WHERE status = 'active'");
$activities_count = $stmt->fetch()['total'] ?? 0;

$stmt = $pdo->query("SELECT COUNT(*) as total FROM users WHERE status = 'active'");
$volunteers = $stmt->fetch()['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AR-Rahma - Humanitarian Association | Niamey, Niger</title>
    <meta name="description" content="AR-Rahma is a non-profit humanitarian association in Niamey, Niger, dedicated to supporting orphans, people with disabilities, and low-income families.">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="hero-section hero-home">
        <div class="hero-overlay"></div>
        <div class="container hero-content">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <h1 class="hero-title">Bringing Hope & Joy to Those in Need</h1>
                    <p class="hero-subtitle">
                        AR-Rahma is a non-profit humanitarian association based in Niamey, Niger, 
                        dedicated to alleviating poverty and supporting orphans, people with disabilities, 
                        and low-income families.
                    </p>
                    <div class="mt-4 hero-buttons">
                        <a href="donate.php" class="btn btn-donate me-3">Donate Now</a>
                        <a href="about.php" class="btn btn-outline">Learn More</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics -->
    <section class="stats-section">
        <div class="stats-overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-4 text-center">
                    <div class="stat-item animate-stat" data-delay="0">
                        <div class="stat-icon">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div class="stat-number"><?php echo number_format($beneficiaries); ?>+</div>
                        <div class="stat-label">Beneficiaries Helped</div>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="stat-item animate-stat" data-delay="200">
                        <div class="stat-icon">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                        <div class="stat-number"><?php echo number_format($activities_count); ?>+</div>
                        <div class="stat-label">Activities Completed</div>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="stat-item animate-stat" data-delay="400">
                        <div class="stat-icon">
                            <i class="bi bi-heart-fill"></i>
                        </div>
                        <div class="stat-number"><?php echo number_format($volunteers); ?>+</div>
                        <div class="stat-label">Active Volunteers</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="section about-section">
        <div class="container">
            <div class="section-title animate-title">
                <h2>Who We Are</h2>
                <p class="section-subtitle">
                    Founded on Islamic principles, we are committed to making a positive impact 
                    in the lives of the most vulnerable members of our community.
                </p>
            </div>
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 animate-left">
                    <div class="about-image-wrapper">
                        <img src="assets/images/im11.png" alt="About AR-Rahma" class="img-fluid rounded about-image" 
                             onerror="this.src='assets/images/placeholder.jpg'">
                    </div>
                </div>
                <div class="col-lg-6 animate-right">
                    <h3 class="mission-title">Our Mission</h3>
                    <p class="mission-text">
                        To alleviate poverty and bring joy to orphans, people with disabilities, 
                        and low-income families through compassionate humanitarian work rooted in Islamic values.
                    </p>
                    <h3 class="vision-title mt-4">Our Vision</h3>
                    <p class="vision-text">
                        A community where every individual, regardless of their circumstances, 
                        has access to basic needs, dignity, and opportunities for a better life.
                    </p>
                    <a href="about.php" class="btn btn-primary mt-3">Learn More About Us</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Activities -->
    <?php if (!empty($featured_activities)): ?>
    <section class="section featured-activities-section bg-light-gradient">
        <div class="container">
            <div class="section-title animate-title">
                <h2>Our Recent Activities</h2>
                <p class="section-subtitle">
                    See the impact we're making in our community
                </p>
            </div>
            <div class="row activities-grid">
                <?php foreach ($featured_activities as $index => $activity): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card activity-card h-100 animate-card" data-index="<?php echo $index; ?>">
                        <div class="activity-image-wrapper">
                            <img src="<?php echo htmlspecialchars($activity['image_url'] ?? 'assets/images/placeholder.jpg'); ?>" 
                                 class="card-img-top activity-image" alt="<?php echo htmlspecialchars($activity['title']); ?>"
                                 onerror="this.src='assets/images/placeholder.jpg'">
                        </div>
                        <div class="card-body">
                            <small class="activity-date text-muted">
                                <i class="bi bi-calendar"></i> <?php echo formatDate($activity['activity_date']); ?>
                            </small>
                            <h5 class="card-title activity-title mt-2"><?php echo htmlspecialchars($activity['title']); ?></h5>
                            <p class="card-text activity-description">
                                <?php echo truncateText(htmlspecialchars($activity['description']), 120); ?>
                            </p>
                            <div class="activity-location mb-2">
                                <small><i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($activity['location']); ?></small>
                            </div>
                            <?php if ($activity['beneficiaries']): ?>
                            <span class="badge badge-primary activity-badge">
                                <i class="bi bi-people"></i> <?php echo $activity['beneficiaries']; ?> beneficiaries
                            </span>
                            <?php endif; ?>
                            <div class="mt-3">
                                <button class="btn btn-primary btn-sm activity-details-btn" data-activity-id="<?php echo $activity['id']; ?>"
                                        data-bs-toggle="modal" data-bs-target="#activityModal">
                                    Read More
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="text-center mt-4">
                <a href="activities.php" class="btn btn-secondary">View All Activities</a>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Call to Action -->
    <section class="section cta-section">
        <div class="cta-overlay"></div>
        <div class="container text-center cta-content">
            <h2 class="cta-title">Make a Difference Today</h2>
            <p class="cta-subtitle">
                Your support can change lives. Join us in our mission to help those in need.
            </p>
            <div class="mt-4 cta-buttons">
                <a href="donate.php" class="btn btn-donate me-3">Donate Now</a>
                <a href="volunteer.php" class="btn btn-outline">Become a Volunteer</a>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <!-- Activity Modal -->
    <div class="modal fade" id="activityModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content activity-modal">
                <div class="modal-header">
                    <h5 class="modal-title" id="activityModalTitle">Activity Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="activityModalBody">
                    <!-- Loaded via JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>