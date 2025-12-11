<?php
// Database configuration
$server = "localhost";
$user = "ngoila.karimou";
$password = "Joker99@";
$database = "webtech_2025A_ngoila_karimou";
$port = 3306;

// Create connection using mysqli
$connection = new mysqli($server, $user, $password, $database, $port);

// Check connection
if($connection->connect_error){
    die("Connection Failed: " . $connection->connect_error);
}

// Set charset to UTF-8
$connection->set_charset("utf8mb4");

// Also create PDO connection for prepared statements
try {
    $pdo = new PDO("mysql:host=$server;dbname=$database;port=$port;charset=utf8mb4", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("PDO Connection Failed: " . $e->getMessage());
}

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
