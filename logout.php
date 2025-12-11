<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

// Check if user is logged in
if (isLoggedIn()) {
    // Destroy session
    $_SESSION = array();
    
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    session_destroy();
    
    // Start new session for flash message
    session_start();
    setFlashMessage('success', 'You have been successfully logged out.');
}

redirect('/ar-rahma-website/login.php');
?>
