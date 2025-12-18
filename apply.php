<?php
require_once 'config/database.php';
require_once 'includes/functions.php';
requireLogin();

$role_id = isset($_GET['role_id']) ? (int)$_GET['role_id'] : 0;
$stmt = $pdo->prepare("SELECT * FROM open_roles WHERE id = ? AND status = 'open'");
$stmt->execute([$role_id]);
$role = $stmt->fetch();

if (!$role) {
    setFlashMessage('error', 'Role not found or closed.');
    redirect('/~ngoila.karimou/uploads/AR-Rhma-final/volunteer.php');
}

if (hasApplied(getCurrentUserId(), $role_id)) {
    setFlashMessage('warning', 'You have already applied for this position.');
    redirect('/~ngoila.karimou/uploads/AR-Rhma-final/my_applications.php');
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cover_letter = cleanInput($_POST['cover_letter'] ?? '');
    $availability = cleanInput($_POST['availability'] ?? '');
    
    if (empty($cover_letter)) {
        $errors[] = 'Cover letter is required';
    }
    
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO applications (user_id, role_id, cover_letter, availability, status) VALUES (?, ?, ?, ?, 'pending')");
            if ($stmt->execute([getCurrentUserId(), $role_id, $cover_letter, $availability])) {
                setFlashMessage('success', 'Application submitted successfully!');
                
                // FIXED: Use proper redirect with exit
                header('Location: /~ngoila.karimou/uploads/AR-Rhma-final/my_applications.php');
                exit(); // This ensures nothing else is executed after redirect
            }
        } catch (PDOException $e) {
            $errors[] = 'Failed to submit application.';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Apply - AR-Rahma</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <section class="section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h2>Apply for: <?php echo htmlspecialchars($role['title']); ?></h2>
                    <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $error): echo "<div>$error</div>"; endforeach; ?>
                    </div>
                    <?php endif; ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Cover Letter *</label>
                            <textarea name="cover_letter" class="form-control" rows="8" required><?php echo htmlspecialchars($_POST['cover_letter'] ?? ''); ?></textarea>
                            <small class="text-muted">Tell us why you're interested and what you can bring to this role.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Your Availability</label>
                            <textarea name="availability" class="form-control" rows="3"><?php echo htmlspecialchars($_POST['availability'] ?? ''); ?></textarea>
                            <small class="text-muted">When are you available to start and how many hours per week?</small>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send"></i> Submit Application
                        </button>
                        <a href="volunteer.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <?php include 'includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>