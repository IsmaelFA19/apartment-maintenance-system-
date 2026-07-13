<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'Fauz@1974');
define('DB_NAME', 'apartment_maintenance');

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start Session
session_start();

// Helper Functions
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function hasRole($role) {
    return isset($_SESSION['role']) && $_SESSION['role'] === $role;
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

function requireRole($role) {
    requireLogin();
    if (!hasRole($role)) {
        header("Location: unauthorised.php"); // fixed: matches actual filename (unauthorised.php, with an 's')
        exit();
    }
}
?>