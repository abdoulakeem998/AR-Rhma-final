<?php
require_once '../config/database.php';
include 'includes/admin_header.php';

// Handle accept/reject
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $action = $_GET['action'];
    $new_status = ($action === 'accept') ? 'accepted' : 'rejected';
    
    $stmt = $pdo->prepare("UPDATE applications SET status = ?, reviewed_by = ?, reviewed_at = NOW() WHERE id = ?");
    if ($stmt->execute([$new_status, getCurrentAdminId(), $id])) {
        logAdminActivity(getCurrentAdminId(), 'review_application', 'application', $id, "Application $new_status");
        setFlashMessage('success', "Application $new_status");
    }
    header('Location: applications.php');
    exit;
}

$stmt = $pdo->query("
    SELECT a.*, u.full_name, u.email, r.title as role_title 
    FROM applications a 
    JOIN users u ON a.user_id = u.id 
    JOIN open_roles r ON a.role_id = r.id 
    ORDER BY a.created_at DESC
");
$applications = $stmt->fetchAll();
?>
<h2>Applications</h2>
<?php displayFlashMessage(); ?>
<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Applicant</th>
                <th>Email</th>
                <th>Role</th>
                <th>Applied On</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($applications as $app): ?>
            <tr>
                <td><?php echo htmlspecialchars($app['full_name']); ?></td>
                <td><?php echo htmlspecialchars($app['email']); ?></td>
                <td><?php echo htmlspecialchars($app['role_title']); ?></td>
                <td><?php echo formatDate($app['created_at']); ?></td>
                <td><?php echo getStatusBadge($app['status']); ?></td>
                <td>
                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#modal<?php echo $app['id']; ?>">View</button>
                    <?php if ($app['status'] === 'pending'): ?>
                    <a href="?action=accept&id=<?php echo $app['id']; ?>" class="btn btn-sm btn-success">Accept</a>
                    <a href="?action=reject&id=<?php echo $app['id']; ?>" class="btn btn-sm btn-danger">Reject</a>
                    <?php endif; ?>
                </td>
            </tr>
            <div class="modal fade" id="modal<?php echo $app['id']; ?>">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Application Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Applicant:</strong> <?php echo htmlspecialchars($app['full_name']); ?></p>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($app['email']); ?></p>
                            <p><strong>Role:</strong> <?php echo htmlspecialchars($app['role_title']); ?></p>
                            <p><strong>Cover Letter:</strong></p>
                            <p><?php echo nl2br(htmlspecialchars($app['cover_letter'])); ?></p>
                            <?php if ($app['availability']): ?>
                            <p><strong>Availability:</strong></p>
                            <p><?php echo nl2br(htmlspecialchars($app['availability'])); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include 'includes/admin_footer.php'; ?>
