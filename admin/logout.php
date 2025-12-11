<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Get base path
$base = defined('BASE_PATH') ? BASE_PATH : '/';
$admin_base = $base . 'admin/';

if (isAdminLoggedIn()) {
    logAdminActivity(getCurrentAdminId(), 'logout', null, null, 'Admin logged out');
    $_SESSION = array();
    session_destroy();
}

redirect($admin_base . 'login.php');
?>