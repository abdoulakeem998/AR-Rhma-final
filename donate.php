<?php
require_once 'config/database.php';
require_once 'includes/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donate - AR-Rahma</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <!-- Beautiful Hero Section -->
    <section class="donate-hero">
        <div class="hero-overlay"></div>
        <div class="container text-center hero-content">
            <div class="floating-icon">
                <i class="bi bi-heart-fill"></i>
            </div>
            <h1 class="hero-title">Support Our Mission</h1>
            
            <!-- Arabic Hadith -->
            <div class="arabic-text">
                الرَّاحِمُونَ يَرْحَمُهُمْ الرَّحْمَنُ ارْحَمُوا مَنْ فِي الْأَرْضِ يَرْحَمْكُمْ مَنْ فِي السَّمَاءِ
            </div>
            
            <!-- English Translation -->
            <div class="english-translation">
                <p class="mb-2">"The merciful will be shown mercy by the Most Merciful.</p>
                <p class="mb-0">Be merciful to those on the earth and the One in the heavens will have mercy upon you."</p>
                <p class="mt-2 hadith-reference"><small>(Prophet Muhammad ﷺ)</small></p>
            </div>
        </div>
    </section>

    <!-- Main Donation Content -->
    <section class="section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <!-- Main Donation Card -->
                    <div class="card donation-card">
                        <div class="card-header-custom">
                            <i class="bi bi-heart-fill"></i>
                            <h2 class="mb-0">Make a Donation</h2>
                            <p class="mb-0 mt-2">Every contribution makes a difference</p>
                        </div>
                        <div class="card-body p-4 p-md-5">
                            
                            <!-- Bank Transfer Details -->
                            <h4 class="text-center mb-4 section-heading">
                                <i class="bi bi-bank"></i> Bank Transfer Details
                            </h4>
                            
                            <div class="bank-details-box">
                                <div class="detail-item">
                                    <span class="detail-label">Bank Name:</span>
                                    <div class="detail-value">Bank of Africa Niger (BOA Niger)</div>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Account Name:</span>
                                    <div class="detail-value">AR-Rahma Association</div>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Account Number:</span>
                                    <div class="detail-value">227-08-45621-78</div>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">SWIFT Code:</span>
                                    <div class="detail-value">AFRINERX</div>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Branch:</span>
                                    <div class="detail-value">Niamey Central Branch</div>
                                </div>
                            </div>

                            <!-- Mobile Money Section -->
                            <h4 class="text-center mt-5 mb-4 section-heading">
                                <i class="bi bi-phone"></i> Mobile Money (Niger)
                            </h4>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="mobile-money-card orange-money">
                                        <i class="bi bi-phone-fill mobile-icon"></i>
                                        <h5 class="mobile-provider">Orange Money</h5>
                                        <p class="mb-0 mobile-number">+227 96 12 34 56</p>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="mobile-money-card moov-money">
                                        <i class="bi bi-phone-fill mobile-icon"></i>
                                        <h5 class="mobile-provider">Moov Money</h5>
                                        <p class="mb-0 mobile-number">+227 90 87 65 43</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Important Note -->
                            <div class="note-box">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-info-circle-fill me-3 note-icon"></i>
                                    <div>
                                        <h5 class="mb-2 note-title">Important Note</h5>
                                        <p class="mb-0 note-text">
                                            After making a donation, please send us the transaction reference via email or WhatsApp 
                                            so we can acknowledge and thank you properly. May Allah reward you abundantly!
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <div class="contact-info-box">
                                <h4 class="mb-3">
                                    <i class="bi bi-envelope-heart"></i> Contact Us
                                </h4>
                                <p class="mb-2 contact-item">
                                    <i class="bi bi-envelope"></i> 
                                    <strong>Email:</strong> <?php echo getSiteSetting('contact_email'); ?>
                                </p>
                                <p class="mb-2 contact-item">
                                    <i class="bi bi-telephone"></i> 
                                    <strong>Phone:</strong> <?php echo getSiteSetting('contact_phone'); ?>
                                </p>
                                <p class="mb-0 contact-item">
                                    <i class="bi bi-geo-alt"></i> 
                                    <strong>Address:</strong> <?php echo getSiteSetting('contact_address'); ?>
                                </p>
                            </div>

                            <!-- What Your Donation Supports -->
                            <div class="impact-section">
                                <h4 class="text-center mb-4 section-heading">
                                    <i class="bi bi-stars"></i> What Your Donation Supports
                                </h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="impact-item">
                                            <i class="bi bi-check-circle-fill"></i>
                                            <span>Orphan support programs</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="impact-item">
                                            <i class="bi bi-check-circle-fill"></i>
                                            <span>Disability care services</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="impact-item">
                                            <i class="bi bi-check-circle-fill"></i>
                                            <span>Food distribution</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="impact-item">
                                            <i class="bi bi-check-circle-fill"></i>
                                            <span>Education scholarships</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="impact-item">
                                            <i class="bi bi-check-circle-fill"></i>
                                            <span>Healthcare assistance</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="impact-item">
                                            <i class="bi bi-check-circle-fill"></i>
                                            <span>Emergency relief</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Closing Message -->
                            <div class="text-center mt-5">
                                <h5 class="closing-hadith">
                                    "Whoever relieves a believer's distress of the distressful aspects of this world, 
                                    Allah will rescue him from a difficulty of the difficulties of the Hereafter."
                                </h5>
                                <p class="text-muted mt-2 hadith-source">- Prophet Muhammad ﷺ (Sahih Muslim)</p>
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