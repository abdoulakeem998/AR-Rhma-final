<?php
require_once '../includes/functions.php';
requireAdmin();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel - AR-Rahma</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 admin-sidebar">
            <h4 class="text-white p-3">AR-Rahma CMS</h4>
            <nav class="nav flex-column">
                <a class="nav-link" href="index.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
                <a class="nav-link" href="activities.php"><i class="bi bi-calendar-event"></i> Activities</a>
                <a class="nav-link" href="team.php"><i class="bi bi-people"></i> Team</a>
                <a class="nav-link" href="roles.php"><i class="bi bi-briefcase"></i> Roles</a>
                <a class="nav-link" href="applications.php"><i class="bi bi-file-text"></i> Applications</a>
                <a class="nav-link" href="messages.php"><i class="bi bi-envelope"></i> Messages</a>
                <a class="nav-link" href="gallery.php"><i class="bi bi-images"></i> Gallery</a>
                <a class="nav-link" href="settings.php"><i class="bi bi-gear"></i> Settings</a>
                <a class="nav-link" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
            </nav>
        </div>
        <div class="col-md-10 p-4">
