<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$success = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = cleanInput($_POST['name'] ?? '');
    $email = cleanInput($_POST['email'] ?? '');
    $phone = cleanInput($_POST['phone'] ?? '');
    $subject = cleanInput($_POST['subject'] ?? '');
    $message = cleanInput($_POST['message'] ?? '');
    
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $errors[] = 'All required fields must be filled';
    } elseif (!isValidEmail($email)) {
        $errors[] = 'Invalid email address';
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)");
            if ($stmt->execute([$name, $email, $phone, $subject, $message])) {
                $success = 'Thank you! Your message has been sent successfully.';
                $_POST = [];
            }
        } catch (PDOException $e) {
            $errors[] = 'Failed to send message. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - AR-Rahma</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="hero-section hero-contact">
        <div class="hero-overlay"></div>
        <div class="container text-center hero-content">
            <div class="hero-icon">
                <i class="bi bi-envelope-heart"></i>
            </div>
            <h1 class="hero-title">Contact Us</h1>
            <p class="hero-subtitle">Get in touch with AR-Rahma</p>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="section">
        <div class="container">
            <div class="row">
                <!-- Contact Form -->
                <div class="col-lg-8 mb-4">
                    <div class="contact-form-wrapper animate-left">
                        <?php if ($success): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="bi bi-check-circle me-2"></i><?php echo $success; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <?php foreach ($errors as $error): ?>
                            <div><?php echo $error; ?></div>
                            <?php endforeach; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php endif; ?>

                        <div class="card contact-card">
                            <div class="card-body p-4 p-md-5">
                                <h3 class="mb-4">Send Us a Message</h3>
                                <form method="POST" class="contact-form">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Name *</label>
                                            <input type="text" name="name" class="form-control form-input" required value="<?php echo $_POST['name'] ?? ''; ?>">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Email *</label>
                                            <input type="email" name="email" class="form-control form-input" required value="<?php echo $_POST['email'] ?? ''; ?>">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Phone</label>
                                            <input type="tel" name="phone" class="form-control form-input" value="<?php echo $_POST['phone'] ?? ''; ?>">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Subject *</label>
                                            <input type="text" name="subject" class="form-control form-input" required value="<?php echo $_POST['subject'] ?? ''; ?>">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Message *</label>
                                        <textarea name="message" class="form-control form-textarea" rows="5" required><?php echo $_POST['message'] ?? ''; ?></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-submit">
                                        <i class="bi bi-send"></i> Send Message
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="col-lg-4">
                    <div class="contact-info-sidebar animate-right">
                        <div class="card contact-info-card">
                            <div class="card-body p-4">
                                <h4 class="mb-4">Contact Information</h4>
                                
                                <div class="contact-info-item">
                                    <div class="contact-info-icon">
                                        <i class="bi bi-envelope"></i>
                                    </div>
                                    <div class="contact-info-content">
                                        <h6>Email</h6>
                                        <p><?php echo getSiteSetting('contact_email'); ?></p>
                                    </div>
                                </div>

                                <div class="contact-info-item">
                                    <div class="contact-info-icon">
                                        <i class="bi bi-phone"></i>
                                    </div>
                                    <div class="contact-info-content">
                                        <h6>Phone</h6>
                                        <p><?php echo getSiteSetting('contact_phone'); ?></p>
                                    </div>
                                </div>

                                <div class="contact-info-item">
                                    <div class="contact-info-icon">
                                        <i class="bi bi-geo-alt"></i>
                                    </div>
                                    <div class="contact-info-content">
                                        <h6>Address</h6>
                                        <p><?php echo getSiteSetting('contact_address'); ?></p>
                                    </div>
                                </div>

                                <div class="contact-hours mt-4">
                                    <h6><i class="bi bi-clock"></i> Office Hours</h6>
                                    <p>Monday - Friday: 8:00 AM - 5:00 PM</p>
                                    <p>Saturday: 9:00 AM - 1:00 PM</p>
                                    <p>Sunday: Closed</p>
                                </div>
                            </div>
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