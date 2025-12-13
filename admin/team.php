<?php
require_once '../config/database.php';
include 'includes/admin_header.php';

$stmt = $pdo->query("SELECT * FROM team_members ORDER BY display_order ASC");
$members = $stmt->fetchAll();
?>
<h2>Team Members</h2>
<p>Manage team members (Add CRUD functionality similar to activities.php)</p>
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Position</th>
                <th>Email</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($members as $member): ?>
            <tr>
                <td><?php echo htmlspecialchars($member['full_name']); ?></td>
                <td><?php echo htmlspecialchars($member['position']); ?></td>
                <td><?php echo htmlspecialchars($member['email']); ?></td>
                <td><?php echo $member['status']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include 'includes/admin_footer.php'; ?>
