<?php
session_start();
require_once '../includes/functions.php';

if (isAdminLoggedIn()) {
    logAdminActivity(getCurrentAdminId(), 'logout', null, null, 'Admin logged out');
    $_SESSION = array();
    session_destroy();
}

header('Location: login.php');
exit;
