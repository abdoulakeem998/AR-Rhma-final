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
                <i class="bi bi-briefcase" style="font-size: 4rem; color: #ccc;"></i>
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
                            <?php if (isLoggedIn()): ?>
                                <?php if (hasApplied(getCurrentUserId(), $role['id'])): ?>
                                <button class="btn btn-secondary btn-sm" disabled>
                                    <i class="bi bi-check-circle"></i> Already Applied
                                </button>
                                <?php else: ?>
                                <a href="apply.php?role_id=<?php echo $role['id']; ?>" class="btn btn-primary btn-sm">
                                    <i class="bi bi-file-text"></i> Apply Now
                                </a>
                                <?php endif; ?>
                            <?php else: ?>
                            <a href="login.php" class="btn btn-primary btn-sm">
                                <i class="bi bi-box-arrow-in-right"></i> Login to Apply
                            </a>
                            <?php endif; ?>
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
