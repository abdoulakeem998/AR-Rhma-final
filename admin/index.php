<?php
require_once '../config/database.php';
require_once '../includes/functions.php';
requireAdmin(); // Ensure admin is logged in

// Get statistics
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

include 'includes/admin_header.php';
?>

<div class="admin-content">
    <h2><i class="bi bi-speedometer2"></i> Dashboard</h2>
    
    <?php displayFlashMessage(); ?>
    
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon" style="background: #667eea;">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="stats-info">
                    <h3><?php echo number_format($total_users); ?></h3>
                    <p>Total Users</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon" style="background: #28a745;">
                    <i class="bi bi-calendar-event-fill"></i>
                </div>
                <div class="stats-info">
                    <h3><?php echo number_format($total_activities); ?></h3>
                    <p>Activities</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon" style="background: #ffc107;">
                    <i class="bi bi-file-earmark-text-fill"></i>
                </div>
                <div class="stats-info">
                    <h3><?php echo number_format($pending_apps); ?></h3>
                    <p>Pending Applications</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon" style="background: #dc3545;">
                    <i class="bi bi-envelope-fill"></i>
                </div>
                <div class="stats-info">
                    <h3><?php echo number_format($new_messages); ?></h3>
                    <p>New Messages</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Activities Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-clock-history"></i> Recent Activities</h5>
        </div>
        <div class="card-body">
            <?php if (empty($recent_activities)): ?>
            <div class="text-center py-4 text-muted">
                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                <p class="mt-2">No activities yet</p>
            </div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Date</th>
                            <th>Location</th>
                            <th>Beneficiaries</th>
                            <th>Category</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_activities as $activity): ?>
                        <tr>
                            <td>
                                <?php if ($activity['featured']): ?>
                                <i class="bi bi-star-fill text-warning" title="Featured"></i>
                                <?php endif; ?>
                                <?php echo htmlspecialchars($activity['title']); ?>
                            </td>
                            <td><?php echo formatDate($activity['activity_date']); ?></td>
                            <td><?php echo htmlspecialchars($activity['location']); ?></td>
                            <td>
                                <span class="badge bg-success">
                                    <?php echo number_format($activity['beneficiaries']); ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-info">
                                    <?php echo ucwords(str_replace('_', ' ', $activity['category'])); ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-<?php echo $activity['status'] === 'active' ? 'success' : 'secondary'; ?>">
                                    <?php echo ucfirst($activity['status']); ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
        <div class="card-footer">
            <a href="activities.php" class="btn btn-sm btn-primary">
                <i class="bi bi-eye"></i> View All Activities
            </a>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-lightning-fill"></i> Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="activities.php" class="btn btn-outline-primary w-100">
                                <i class="bi bi-calendar-plus"></i><br>
                                Add Activity
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="team.php" class="btn btn-outline-success w-100">
                                <i class="bi bi-person-plus"></i><br>
                                Add Team Member
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="gallery.php" class="btn btn-outline-info w-100">
                                <i class="bi bi-images"></i><br>
                                Upload Images
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="messages.php" class="btn btn-outline-warning w-100">
                                <i class="bi bi-envelope-open"></i><br>
                                View Messages
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Dashboard Statistics Cards */
.stats-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    transition: all 0.3s ease;
    margin-bottom: 1.5rem;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.stats-icon {
    width: 70px;
    height: 70px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1.5rem;
    font-size: 2rem;
    color: white;
}

.stats-info h3 {
    font-size: 2rem;
    font-weight: bold;
    color: #2C5F2D;
    margin: 0;
}

.stats-info p {
    color: #666;
    margin: 0;
    font-size: 0.95rem;
}

/* Table Enhancements */
.table thead th {
    background: #2C5F2D;
    color: white;
    border: none;
    font-weight: 600;
}

.table tbody tr {
    transition: background 0.2s ease;
}

.table tbody tr:hover {
    background: rgba(44, 95, 45, 0.05);
}

/* Quick Actions Buttons */
.btn-outline-primary,
.btn-outline-success,
.btn-outline-info,
.btn-outline-warning {
    padding: 1.5rem;
    font-weight: 600;
    border-width: 2px;
    transition: all 0.3s ease;
}

.btn-outline-primary:hover,
.btn-outline-success:hover,
.btn-outline-info:hover,
.btn-outline-warning:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.2);
}

/* Card Header */
.card-header {
    background: white;
    border-bottom: 2px solid #f0f0f0;
    padding: 1rem 1.5rem;
}

.card-header h5 {
    color: #2C5F2D;
    font-weight: 600;
}
</style>

<?php include 'includes/admin_footer.php'; ?>