<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect('/ar-rahma-website/index.php');
}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = cleanInput($_POST['full_name'] ?? '');
    $email = cleanInput($_POST['email'] ?? '');
    $phone = cleanInput($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validation
    if (empty($full_name)) {
        $errors[] = 'Full name is required';
    }
    
    if (empty($email) || !isValidEmail($email)) {
        $errors[] = 'Valid email is required';
    }
    
    if (empty($password)) {
        $errors[] = 'Password is required';
    } elseif (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters';
    }
    
    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match';
    }
    
    // Check if email already exists
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $errors[] = 'Email already registered';
            }
        } catch (PDOException $e) {
            $errors[] = 'Database error occurred';
        }
    }
    
    // Register user
    if (empty($errors)) {
        try {
            $password_hash = hashPassword($password);
            $stmt = $pdo->prepare("
                INSERT INTO users (full_name, email, phone, password_hash, status) 
                VALUES (?, ?, ?, ?, 'active')
            ");
            
            if ($stmt->execute([$full_name, $email, $phone, $password_hash])) {
                setFlashMessage('success', 'Registration successful! You can now login.');
                redirect('/ar-rahma-website/login.php');
            } else {
                $errors[] = 'Registration failed. Please try again.';
            }
        } catch (PDOException $e) {
            $errors[] = 'Registration failed. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - AR-Rahma</title>
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

    <!-- Signup Form Section -->
    <section class="auth-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="card auth-card animate-scale">
                        <div class="card-body p-4 p-md-5">
                            <!-- Header -->
                            <div class="text-center mb-4 auth-header">
                                <div class="auth-icon signup-icon">
                                    <i class="bi bi-person-plus-fill"></i>
                                </div>
                                <h2 class="auth-title">Create Your Account</h2>
                                <p class="auth-subtitle">
                                    Join AR-Rahma to apply for volunteer opportunities
                                </p>
                            </div>

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

                            <!-- Signup Form -->
                            <form method="POST" action="" class="auth-form needs-validation" novalidate>
                                <div class="form-group mb-3">
                                    <label for="full_name" class="form-label auth-label">
                                        <i class="bi bi-person"></i> Full Name *
                                    </label>
                                    <input type="text" 
                                           class="form-control auth-input" 
                                           id="full_name" 
                                           name="full_name" 
                                           value="<?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?>"
                                           required>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="email" class="form-label auth-label">
                                        <i class="bi bi-envelope"></i> Email Address *
                                    </label>
                                    <input type="email" 
                                           class="form-control auth-input" 
                                           id="email" 
                                           name="email"
                                           value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                                           required>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="phone" class="form-label auth-label">
                                        <i class="bi bi-telephone"></i> Phone Number
                                    </label>
                                    <input type="tel" 
                                           class="form-control auth-input" 
                                           id="phone" 
                                           name="phone"
                                           value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="password" class="form-label auth-label">
                                        <i class="bi bi-lock"></i> Password *
                                    </label>
                                    <input type="password" 
                                           class="form-control auth-input" 
                                           id="password" 
                                           name="password"
                                           minlength="8"
                                           required>
                                    <small class="password-hint">Minimum 8 characters</small>
                                    <div id="passwordStrength" class="password-strength mt-1"></div>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="confirm_password" class="form-label auth-label">
                                        <i class="bi bi-lock-fill"></i> Confirm Password *
                                    </label>
                                    <input type="password" 
                                           class="form-control auth-input" 
                                           id="confirm_password" 
                                           name="confirm_password"
                                           required>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 mb-3 auth-submit-btn">
                                    <i class="bi bi-person-plus"></i> Create Account
                                </button>

                                <div class="text-center auth-footer">
                                    <p class="mb-0 auth-link-text">
                                        Already have an account? 
                                        <a href="login.php" class="auth-link">Login here</a>
                                    </p>
                                </div>
                            </form>
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