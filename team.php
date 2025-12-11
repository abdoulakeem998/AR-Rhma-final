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
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <section class="hero-section" style="padding: 3rem 0;">
        <div class="container text-center">
            <h1>Our Team</h1>
            <p class="lead">Meet the dedicated individuals behind AR-Rahma</p>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="row">
                <?php if (empty($team_members)): ?>
                <div class="col-12 text-center py-5">
                    <p>No team members to display yet.</p>
                </div>
                <?php else: ?>
                <?php foreach ($team_members as $member): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 text-center">
                        <div class="card-body p-4">
                            <?php if ($member['photo_url']): ?>
                            <img src="<?php echo htmlspecialchars($member['photo_url']); ?>" 
                                 alt="<?php echo htmlspecialchars($member['full_name']); ?>"
                                 class="rounded-circle mx-auto d-block mb-3"
                                 style="width: 150px; height: 150px; object-fit: cover;"
                                 onerror="this.src='assets/images/placeholder.jpg'">
                            <?php else: ?>
                            <div class="rounded-circle mx-auto d-block mb-3" 
                                 style="width: 150px; height: 150px; background: var(--light-bg); display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-person" style="font-size: 4rem; color: var(--light-text);"></i>
                            </div>
                            <?php endif; ?>
                            <h5><?php echo htmlspecialchars($member['full_name']); ?></h5>
                            <p class="text-muted"><?php echo htmlspecialchars($member['position']); ?></p>
                            <?php if ($member['bio']): ?>
                            <p class="small"><?php echo truncateText($member['bio'], 150); ?></p>
                            <?php endif; ?>
                            <?php if ($member['email']): ?>
                            <a href="mailto:<?php echo htmlspecialchars($member['email']); ?>" class="btn btn-sm btn-outline-primary mt-2">
                                <i class="bi bi-envelope"></i> Email
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
