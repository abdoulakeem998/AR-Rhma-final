<?php
session_start();
require_once '../config/database.php';
require_once '../php/functions.php';

// Redirect if already logged in
if (isAdminLoggedIn()) {
    redirect('index.php');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = cleanInput($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $errors[] = 'Please enter both username and password';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ? AND status = 'active'");
            $stmt->execute([$username]);
            $admin = $stmt->fetch();
            
            if ($admin && verifyPassword($password, $admin['password_hash'])) {
                // Login successful
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                $_SESSION['admin_name'] = $admin['full_name'];
                $_SESSION['admin_role'] = $admin['role'];
                
                // Update last login
                $stmt = $pdo->prepare("UPDATE admins SET last_login = NOW() WHERE id = ?");
                $stmt->execute([$admin['id']]);
                
                // Log activity
                logAdminActivity($admin['id'], 'login', null, null, 'Admin logged in');
                
                redirect('index.php');
            } else {
                $errors[] = 'Invalid username or password';
                // Log failed attempt
                if ($admin) {
                    logAdminActivity($admin['id'], 'login_failed', null, null, 'Failed login attempt');
                }
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
    <title>Admin Login - AR-Rahma</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            max-width: 450px;
            width: 100%;
        }
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .login-header h1 {
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        .login-header p {
            color: var(--light-text);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card login-card">
                    <div class="card-body p-5">
                        <div class="login-header">
                            <i class="bi bi-shield-lock" style="font-size: 3rem; color: var(--primary-color);"></i>
                            <h1>Admin Panel</h1>
                            <p>AR-Rahma Content Management System</p>
                        </div>

                        <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endif; ?>

                        <form method="POST" action="">
                            <div class="form-group mb-3">
                                <label for="username" class="form-label">
                                    <i class="bi bi-person"></i> Username
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="username" 
                                       name="username"
                                       value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                                       required
                                       autofocus>
                            </div>

                            <div class="form-group mb-4">
                                <label for="password" class="form-label">
                                    <i class="bi bi-lock"></i> Password
                                </label>
                                <input type="password" 
                                       class="form-control" 
                                       id="password" 
                                       name="password"
                                       required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 mb-3">
                                <i class="bi bi-box-arrow-in-right"></i> Login to Admin Panel
                            </button>

                            <div class="text-center">
                                <a href="../index.php" class="text-muted">
                                    <i class="bi bi-arrow-left"></i> Back to Main Website
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="text-center mt-3 text-white">
                    <small>
                        <i class="bi bi-shield-check"></i> Secure Admin Access
                    </small>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
