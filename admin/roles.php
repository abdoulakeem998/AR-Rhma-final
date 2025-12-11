<?php
require_once '../config/database.php';
include 'includes/admin_header.php';

$stmt = $pdo->query("SELECT * FROM open_roles ORDER BY created_at DESC");
$roles = $stmt->fetchAll();
?>
<h2>Volunteer Roles</h2>
<p>Manage volunteer opportunities (Add CRUD functionality similar to activities.php)</p>
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Type</th>
                <th>Location</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($roles as $role): ?>
            <tr>
                <td><?php echo htmlspecialchars($role['title']); ?></td>
                <td><?php echo $role['type']; ?></td>
                <td><?php echo htmlspecialchars($role['location']); ?></td>
                <td><?php echo $role['status']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include 'includes/admin_footer.php'; ?>
