<?php
$current_page = basename($_SERVER['PHP_SELF']);

// Get base path for links (defined in config/database.php)
$base = defined('BASE_PATH') ? BASE_PATH : '/';
?>
<header class="main-header">
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="<?php echo $base; ?>index.php">
                <img src="<?php echo $base; ?>assets/images/logo.png" alt="AR-Rahma" style="height: 60px; width: auto;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>" 
                           href="<?php echo $base; ?>index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'about.php') ? 'active' : ''; ?>" 
                           href="<?php echo $base; ?>about.php">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'activities.php') ? 'active' : ''; ?>" 
                           href="<?php echo $base; ?>activities.php">Activities</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'team.php') ? 'active' : ''; ?>" 
                           href="<?php echo $base; ?>team.php">Our Team</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'volunteer.php') ? 'active' : ''; ?>" 
                           href="<?php echo $base; ?>volunteer.php">Volunteer</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'gallery.php') ? 'active' : ''; ?>" 
                           href="<?php echo $base; ?>gallery.php">Gallery</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'contact.php') ? 'active' : ''; ?>" 
                           href="<?php echo $base; ?>contact.php">Contact</a>
                    </li>
                    
                    <?php if (isLoggedIn()): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> My Account
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?php echo $base; ?>my_applications.php">My Applications</a></li>
                            <li><a class="dropdown-item" href="<?php echo $base; ?>profile.php">Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?php echo $base; ?>logout.php">Logout</a></li>
                        </ul>
                    </li>
                    <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base; ?>login.php">
                            <i class="bi bi-box-arrow-in-right"></i> Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base; ?>signup.php">Sign Up</a>
                    </li>
                    <?php endif; ?>
                    
                    <li class="nav-item">
                        <a class="btn btn-donate" href="<?php echo $base; ?>donate.php">
                            <i class="bi bi-heart"></i> Donate
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>