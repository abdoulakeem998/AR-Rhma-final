<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect('/ar-rahma-website/index.php');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = cleanInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $errors[] = 'Please enter both email and password';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND status = 'active'");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user && verifyPassword($password, $user['password_hash'])) {
                // Login successful
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['full_name'];
                $_SESSION['user_email'] = $user['email'];
                
                // Redirect to intended page or index
                $redirect_to = $_SESSION['redirect_after_login'] ?? '/ar-rahma-website/index.php';
                unset($_SESSION['redirect_after_login']);
                redirect($redirect_to);
            } else {
                $errors[] = 'Invalid email or password';
            }
        } catch (PDOException $e) {
            $errors[] = 'Login failed. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - AR-Rahma</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <!-- Auth Hero Section -->
    <section class="auth-hero">
        <div class="auth-overlay"></div>
    </section>

    <!-- Login Form Section -->
    <section class="auth-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-5 col-lg-4">
                    <div class="card auth-card animate-scale">
                        <div class="card-body p-4 p-md-5">
                            <!-- Header -->
                            <div class="text-center mb-4 auth-header">
                                <div class="auth-icon login-icon">
                                    <i class="bi bi-box-arrow-in-right"></i>
                                </div>
                                <h2 class="auth-title">Welcome Back</h2>
                                <p class="auth-subtitle">Login to your account</p>
                            </div>

                            <!-- Flash Messages -->
                            <?php displayFlashMessage(); ?>

                            <!-- Error Messages -->
                            <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger auth-alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <ul class="mb-0 auth-error-list">
                                    <?php foreach ($errors as $error): ?>
                                    <li><?php echo htmlspecialchars($error); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <?php endif; ?>

                            <!-- Login Form -->
                            <form method="POST" action="" class="auth-form">
                                <div class="form-group mb-3">
                                    <label for="email" class="form-label auth-label">
                                        <i class="bi bi-envelope"></i> Email Address
                                    </label>
                                    <input type="email" 
                                           class="form-control auth-input" 
                                           id="email" 
                                           name="email"
                                           value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                                           required>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="password" class="form-label auth-label">
                                        <i class="bi bi-lock"></i> Password
                                    </label>
                                    <input type="password" 
                                           class="form-control auth-input" 
                                           id="password" 
                                           name="password"
                                           required>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 mb-3 auth-submit-btn">
                                    <i class="bi bi-box-arrow-in-right"></i> Login
                                </button>

                                <div class="text-center auth-footer">
                                    <p class="mb-0 auth-link-text">
                                        Don't have an account? 
                                        <a href="signup.php" class="auth-link">Sign up here</a>
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Additional Info Box -->
                    <div class="auth-info-box mt-4 animate-fade-up">
                        <div class="text-center">
                            <i class="bi bi-info-circle"></i>
                            <p class="mb-0">Need help? Contact us at</p>
                            <a href="mailto:<?php echo getSiteSetting('contact_email'); ?>" class="auth-help-link">
                                <?php echo getSiteSetting('contact_email'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>