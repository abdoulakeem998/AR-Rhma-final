<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$stmt = $pdo->query("SELECT * FROM team_members WHERE status = 'active' ORDER BY display_order ASC");
$team_members = $stmt->fetchAll();

$page_title = "Our Team";
include 'includes/header.php';
?>

<!-- Hero Section with Background -->
<section class="hero-team">
    <div class="hero-overlay"></div>
    <div class="container">
        <div class="hero-content text-center">
            <h1 class="animate-fade-down">Our Dedicated Team</h1>
            <p class="hero-subtitle animate-fade-up">United by compassion, driven by purpose</p>
            <div class="hero-description animate-fade-up" style="animation-delay: 0.2s;">
                <p>Meet the hearts and hands behind AR-Rahma. Our team is committed to bringing hope, 
                dignity, and support to orphans, disabled individuals, and low-income families across Niger.</p>
            </div>
        </div>
    </div>
</section>

<!-- Mission Statement -->
<section class="team-mission py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="mission-box animate-scale">
                    <i class="fas fa-heart heartbeat-icon"></i>
                    <h3 class="mb-3">Together We Make a Difference</h3>
                    <p class="lead">Every member of our team brings unique skills, unwavering dedication, 
                    and a shared vision of creating lasting positive change in our community. 
                    We work tirelessly to ensure that every person we serve feels valued, supported, and empowered.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team Members Section -->
<section class="team-section py-5">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 class="animate-title">Meet Our Team</h2>
            <div class="title-underline"></div>
        </div>

        <?php if (empty($team_members)): ?>
            <div class="empty-state text-center py-5">
                <i class="fas fa-users fa-4x text-muted mb-3"></i>
                <h3>Building Our Team</h3>
                <p class="text-muted">We're assembling a dedicated group of individuals. Check back soon!</p>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($team_members as $index => $member): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="team-member-card animate-card" style="animation-delay: <?= $index * 0.1 ?>s">
                            <div class="member-image-wrapper">
                                <?php if ($member['photo']): ?>
                                    <img src="<?= htmlspecialchars($member['photo']) ?>" 
                                         alt="<?= htmlspecialchars($member['name']) ?>"
                                         class="member-photo"
                                         onerror="this.parentElement.innerHTML='<div class=\'member-photo-placeholder\'><i class=\'fas fa-user\'></i></div>'">
                                <?php else: ?>
                                    <div class="member-photo-placeholder">
                                        <i class="fas fa-user"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($member['social_facebook'] || $member['social_twitter'] || $member['social_linkedin']): ?>
                                <div class="member-overlay">
                                    <div class="social-links">
                                        <?php if ($member['social_facebook']): ?>
                                            <a href="<?= htmlspecialchars($member['social_facebook']) ?>" 
                                               target="_blank" 
                                               class="social-icon"
                                               title="Facebook">
                                                <i class="fab fa-facebook-f"></i>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <?php if ($member['social_twitter']): ?>
                                            <a href="<?= htmlspecialchars($member['social_twitter']) ?>" 
                                               target="_blank" 
                                               class="social-icon"
                                               title="Twitter">
                                                <i class="fab fa-twitter"></i>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <?php if ($member['social_linkedin']): ?>
                                            <a href="<?= htmlspecialchars($member['social_linkedin']) ?>" 
                                               target="_blank" 
                                               class="social-icon"
                                               title="LinkedIn">
                                                <i class="fab fa-linkedin-in"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="member-info">
                                <h4 class="member-name"><?= htmlspecialchars($member['name']) ?></h4>
                                <p class="member-position"><?= htmlspecialchars($member['position']) ?></p>
                                
                                <?php if ($member['bio']): ?>
                                    <p class="member-bio"><?= htmlspecialchars(substr($member['bio'], 0, 150)) ?><?= strlen($member['bio']) > 150 ? '...' : '' ?></p>
                                <?php endif; ?>
                                
                                <?php if ($member['email'] || $member['phone']): ?>
                                <div class="member-contact">
                                    <?php if ($member['email']): ?>
                                        <a href="mailto:<?= htmlspecialchars($member['email']) ?>" 
                                           class="contact-link"
                                           title="Send Email">
                                            <i class="fas fa-envelope"></i>
                                            <span>Email</span>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($member['phone']): ?>
                                        <a href="tel:<?= htmlspecialchars($member['phone']) ?>" 
                                           class="contact-link"
                                           title="Call">
                                            <i class="fas fa-phone"></i>
                                            <span>Call</span>
                                        </a>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Join Team CTA -->
<section class="join-team-section py-5">
    <div class="container">
        <div class="join-team-box animate-scale">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h3><i class="fas fa-hands-helping"></i> Want to Join Our Team?</h3>
                    <p class="mb-lg-0">We're always looking for passionate individuals who want to make a difference. 
                    If you share our vision of serving those in need, we'd love to hear from you.</p>
                </div>
                <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                    <a href="volunteer.php" class="btn btn-light btn-lg">
                        <i class="fas fa-user-plus"></i> Become a Volunteer
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team Values -->
<section class="team-values py-5">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 class="animate-title">Our Core Values</h2>
            <div class="title-underline"></div>
        </div>
        
        <div class="row g-4">
            <div class="col-md-3 col-sm-6">
                <div class="value-card animate-card">
                    <div class="value-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h4>Compassion</h4>
                    <p>We lead with empathy and understanding in everything we do</p>
                </div>
            </div>
            
            <div class="col-md-3 col-sm-6">
                <div class="value-card animate-card" style="animation-delay: 0.1s">
                    <div class="value-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h4>Unity</h4>
                    <p>Together we are stronger, working as one for our community</p>
                </div>
            </div>
            
            <div class="col-md-3 col-sm-6">
                <div class="value-card animate-card" style="animation-delay: 0.2s">
                    <div class="value-icon">
                        <i class="fas fa-hands"></i>
                    </div>
                    <h4>Service</h4>
                    <p>Dedicated to uplifting those who need support the most</p>
                </div>
            </div>
            
            <div class="col-md-3 col-sm-6">
                <div class="value-card animate-card" style="animation-delay: 0.3s">
                    <div class="value-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <h4>Excellence</h4>
                    <p>Committed to the highest standards in all our initiatives</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>