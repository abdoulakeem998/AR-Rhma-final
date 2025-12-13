<?php
require_once '../config/database.php';
include 'includes/admin_header.php';

$stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE status = 'active'");
$total_users = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM activities WHERE status = 'active'");
$total_activities = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM applications WHERE status = 'pending'");
$pending_apps = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM contact_messages WHERE status = 'new'");
$new_messages = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT * FROM activities ORDER BY created_at DESC LIMIT 5");
$recent_activities = $stmt->fetchAll();
?>
<h2>Dashboard</h2>
<?php displayFlashMessage(); ?>
<div class="row mb-4">
    <div class="col-md-3">
        <div class="dashboard-card">
            <h3><?php echo $total_users; ?></h3>
            <p>Total Users</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="dashboard-card success">
            <h3><?php echo $total_activities; ?></h3>
            <p>Activities</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="dashboard-card warning">
            <h3><?php echo $pending_apps; ?></h3>
            <p>Pending Applications</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="dashboard-card danger">
            <h3><?php echo $new_messages; ?></h3>
            <p>New Messages</p>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header">Recent Activities</div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Beneficiaries</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recent_activities as $activity): ?>
                <tr>
                    <td><?php echo htmlspecialchars($activity['title']); ?></td>
                    <td><?php echo formatDate($activity['activity_date']); ?></td>
                    <td><?php echo $activity['beneficiaries']; ?></td>
                    <td><span class="badge badge-primary"><?php echo $activity['status']; ?></span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include 'includes/admin_footer.php'; ?>
