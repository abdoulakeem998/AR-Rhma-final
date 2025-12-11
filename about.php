<?php
require_once 'config/database.php';
require_once 'includes/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - AR-Rahma</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="hero-section hero-about">
        <div class="hero-overlay"></div>
        <div class="container text-center hero-content">
            <div class="hero-icon">
                <i class="bi bi-heart-fill"></i>
            </div>
            <h1 class="hero-title">About AR-Rahma</h1>
            <p class="hero-subtitle">Learn about our mission, vision, and the work we do</p>
        </div>
    </section>

    <!-- Who We Are Section -->
    <section class="section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 animate-left">
                    <div class="about-image-wrapper">
                        <img src="assets/images/im11.png" alt="AR-Rahma" class="img-fluid rounded about-image" onerror="this.src='assets/images/placeholder.jpg'">
                    </div>
                </div>
                <div class="col-lg-6 animate-right">
                    <h2 class="section-heading text-primary-color mb-4">Who We Are</h2>
                    <p class="about-text">AR-Rahma is a non-profit humanitarian association based in Niamey, Niger. Founded on Islamic principles of compassion, charity, and social justice, we are dedicated to making a tangible difference in the lives of the most vulnerable members of our society.</p>
                    <p class="about-text">Our name, "AR-Rahma," means "The Mercy" in Arabic, reflecting our core belief that showing mercy and compassion to those in need is a fundamental duty and a path to building a more just and caring community.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission and Vision Section -->
    <section class="section bg-light-gradient">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mb-4">
                    <div class="card mission-vision-card animate-scale">
                        <div class="card-body p-4 text-center">
                            <div class="card-icon mission-icon">
                                <i class="bi bi-bullseye"></i>
                            </div>
                            <h3 class="mt-3">Our Mission</h3>
                            <p class="card-description">To alleviate poverty and bring joy to orphans, people with disabilities, and low-income families through compassionate humanitarian work rooted in Islamic values.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mb-4">
                    <div class="card mission-vision-card animate-scale">
                        <div class="card-body p-4 text-center">
                            <div class="card-icon vision-icon">
                                <i class="bi bi-eye"></i>
                            </div>
                            <h3 class="mt-3">Our Vision</h3>
                            <p class="card-description">A community where every individual, regardless of their circumstances, has access to basic needs, dignity, and opportunities for a better life.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Core Values Section -->
    <section class="section">
        <div class="container">
            <div class="section-title">
                <h2>Our Core Values</h2>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4 text-center">
                    <div class="value-item animate-fade-up">
                        <div class="value-icon compassion-icon">
                            <i class="bi bi-heart-fill"></i>
                        </div>
                        <h5 class="value-title mt-3">Compassion</h5>
                        <p class="value-description">We approach every situation with empathy and understanding</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 text-center">
                    <div class="value-item animate-fade-up">
                        <div class="value-icon integrity-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h5 class="value-title mt-3">Integrity</h5>
                        <p class="value-description">We maintain transparency and accountability in all our work</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 text-center">
                    <div class="value-item animate-fade-up">
                        <div class="value-icon dignity-icon">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <h5 class="value-title mt-3">Dignity</h5>
                        <p class="value-description">We respect and honor the inherent worth of every person</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 text-center">
                    <div class="value-item animate-fade-up">
                        <div class="value-icon excellence-icon">
                            <i class="bi bi-star-fill"></i>
                        </div>
                        <h5 class="value-title mt-3">Excellence</h5>
                        <p class="value-description">We strive for the highest quality in our services</p>
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