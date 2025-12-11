<?php
// Get base path for links (defined in config/database.php)
$base = defined('BASE_PATH') ? BASE_PATH : '/';

$site_name = getSiteSetting('site_name', 'AR-Rahma');
$contact_email = getSiteSetting('contact_email', 'info@ar-rahma.org');
$contact_phone = getSiteSetting('contact_phone', '+227 XX XX XX XX');
$contact_address = getSiteSetting('contact_address', 'Niamey, Niger');
$facebook = getSiteSetting('facebook_url', '');
$twitter = getSiteSetting('twitter_url', '');
$instagram = getSiteSetting('instagram_url', '');
$linkedin = getSiteSetting('linkedin_url', '');
?>
<footer class="main-footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6 footer-section mb-4">
                <h5><?php echo htmlspecialchars($site_name); ?></h5>
                <p>
                    A non-profit humanitarian association based in Niamey, Niger, 
                    dedicated to alleviating poverty and supporting orphans, people with disabilities, 
                    and low-income families.
                </p>
                <div class="social-links mt-3">
                    <?php if ($facebook): ?>
                    <a href="<?php echo htmlspecialchars($facebook); ?>" target="_blank" class="me-2">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <?php endif; ?>
                    <?php if ($twitter): ?>
                    <a href="<?php echo htmlspecialchars($twitter); ?>" target="_blank" class="me-2">
                        <i class="bi bi-twitter"></i>
                    </a>
                    <?php endif; ?>
                    <?php if ($instagram): ?>
                    <a href="<?php echo htmlspecialchars($instagram); ?>" target="_blank" class="me-2">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <?php endif; ?>
                    <?php if ($linkedin): ?>
                    <a href="<?php echo htmlspecialchars($linkedin); ?>" target="_blank">
                        <i class="bi bi-linkedin"></i>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-6 footer-section mb-4">
                <h5>Quick Links</h5>
                <ul>
                    <li><a href="<?php echo $base; ?>index.php">Home</a></li>
                    <li><a href="<?php echo $base; ?>about.php">About Us</a></li>
                    <li><a href="<?php echo $base; ?>activities.php">Activities</a></li>
                    <li><a href="<?php echo $base; ?>team.php">Our Team</a></li>
                    <li><a href="<?php echo $base; ?>faq.php">FAQs</a></li>
                </ul>
            </div>
            
            <div class="col-lg-3 col-md-6 footer-section mb-4">
                <h5>Get Involved</h5>
                <ul>
                    <li><a href="<?php echo $base; ?>donate.php">Make a Donation</a></li>
                    <li><a href="<?php echo $base; ?>volunteer.php">Volunteer</a></li>
                    <li><a href="<?php echo $base; ?>signup.php">Create Account</a></li>
                    <li><a href="<?php echo $base; ?>gallery.php">Gallery</a></li>
                    <li><a href="<?php echo $base; ?>contact.php">Contact Us</a></li>
                </ul>
            </div>
            
            <div class="col-lg-3 col-md-6 footer-section mb-4">
                <h5>Contact Us</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="bi bi-geo-alt"></i>
                        <?php echo htmlspecialchars($contact_address); ?>
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-envelope"></i>
                        <a href="mailto:<?php echo htmlspecialchars($contact_email); ?>">
                            <?php echo htmlspecialchars($contact_email); ?>
                        </a>
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-phone"></i>
                        <a href="tel:<?php echo str_replace(' ', '', $contact_phone); ?>">
                            <?php echo htmlspecialchars($contact_phone); ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="footer-bottom">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p>&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($site_name); ?>. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="<?php echo $base; ?>privacy.php">Privacy Policy</a> | 
                    <a href="<?php echo $base; ?>terms.php">Terms of Service</a>
                </div>
            </div>
        </div>
    </div>
</footer>