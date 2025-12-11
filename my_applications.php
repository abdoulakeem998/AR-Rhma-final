<?php
require_once 'config/database.php';
require_once 'includes/functions.php';
requireLogin();

$user_id = getCurrentUserId();
$stmt = $pdo->prepare("
    SELECT a.*, r.title as role_title, r.type 
    FROM applications a 
    JOIN open_roles r ON a.role_id = r.id 
    WHERE a.user_id = ? 
    ORDER BY a.created_at DESC
");
$stmt->execute([$user_id]);
$applications = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Applications - AR-Rahma</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <section class="section">
        <div class="container">
            <h2>My Applications</h2>
            <?php displayFlashMessage(); ?>
            <?php if (empty($applications)): ?>
            <div class="alert alert-info">You haven't submitted any applications yet. <a href="volunteer.php">Browse opportunities</a></div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Role</th>
                            <th>Type</th>
                            <th>Applied On</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($applications as $app): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($app['role_title']); ?></td>
                            <td><?php echo ucfirst($app['type']); ?></td>
                            <td><?php echo formatDate($app['created_at']); ?></td>
                            <td><?php echo getStatusBadge($app['status']); ?></td>
                            <td>
                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailsModal<?php echo $app['id']; ?>">View</button>
                            </td>
                        </tr>
                        <div class="modal fade" id="detailsModal<?php echo $app['id']; ?>">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Application Details</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Role:</strong> <?php echo htmlspecialchars($app['role_title']); ?></p>
                                        <p><strong>Status:</strong> <?php echo getStatusBadge($app['status']); ?></p>
                                        <p><strong>Cover Letter:</strong></p>
                                        <p><?php echo nl2br(htmlspecialchars($app['cover_letter'])); ?></p>
                                        <?php if ($app['admin_notes']): ?>
                                        <p><strong>Admin Notes:</strong></p>
                                        <p><?php echo nl2br(htmlspecialchars($app['admin_notes'])); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </section>
    <?php include 'includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
