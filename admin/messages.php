<?php
require_once '../config/database.php';
include 'includes/admin_header.php';

$stmt = $pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
$messages = $stmt->fetchAll();
?>
<h2>Contact Messages</h2>
<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Subject</th>
                <th>Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($messages as $msg): ?>
            <tr>
                <td><?php echo htmlspecialchars($msg['name']); ?></td>
                <td><?php echo htmlspecialchars($msg['email']); ?></td>
                <td><?php echo htmlspecialchars($msg['subject']); ?></td>
                <td><?php echo formatDate($msg['created_at']); ?></td>
                <td><span class="badge badge-<?php echo $msg['status'] === 'new' ? 'warning' : 'primary'; ?>"><?php echo $msg['status']; ?></span></td>
                <td>
                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#msg<?php echo $msg['id']; ?>">View</button>
                </td>
            </tr>
            <div class="modal fade" id="msg<?php echo $msg['id']; ?>">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Message from <?php echo htmlspecialchars($msg['name']); ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>From:</strong> <?php echo htmlspecialchars($msg['name']); ?></p>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($msg['email']); ?></p>
                            <p><strong>Phone:</strong> <?php echo htmlspecialchars($msg['phone']); ?></p>
                            <p><strong>Subject:</strong> <?php echo htmlspecialchars($msg['subject']); ?></p>
                            <p><strong>Message:</strong></p>
                            <p><?php echo nl2br(htmlspecialchars($msg['message'])); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include 'includes/admin_footer.php'; ?>
