<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$items_per_page = 9;
$current_page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($current_page - 1) * $items_per_page;

$stmt = $pdo->query("SELECT COUNT(*) FROM activities WHERE status = 'active'");
$total_items = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT * FROM activities WHERE status = 'active' ORDER BY activity_date DESC LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $items_per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$activities = $stmt->fetchAll();

$pagination = paginate($total_items, $items_per_page, $current_page);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Activities - AR-Rahma</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="hero-section hero-activities">
        <div class="hero-overlay"></div>
        <div class="container text-center hero-content">
            <div class="hero-icon">
                <i class="bi bi-calendar-event"></i>
            </div>
            <h1 class="hero-title">Our Activities</h1>
            <p class="hero-subtitle">Discover the impact we're making in our community</p>
        </div>
    </section>

    <!-- Activities Grid Section -->
    <section class="section">
        <div class="container">
            <?php if (empty($activities)): ?>
            <div class="empty-state text-center py-5">
                <i class="bi bi-inbox empty-icon"></i>
                <h4 class="mt-3 empty-title">No activities found</h4>
            </div>
            <?php else: ?>
            <div class="row activities-grid">
                <?php foreach ($activities as $index => $activity): ?>
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
                            <p class="card-text activity-description"><?php echo truncateText($activity['description'], 120); ?></p>
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
                                    View Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php if ($pagination['total_pages'] > 1): ?>
            <div class="mt-4 pagination-wrapper">
                <?php echo generatePaginationHTML($pagination['total_pages'], $pagination['current_page'], 'activities.php'); ?>
            </div>
            <?php endif; ?>
            <?php endif; ?>
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
                <div class="modal-body" id="activityModalBody"></div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>