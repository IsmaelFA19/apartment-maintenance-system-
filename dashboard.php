<?php
require_once '../includes/config.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - AMS</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="dashboard">
        <?php include '../includes/sidebar.php'; ?>

        <div class="main-content">
            <div class="card">
                <h1>Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?></h1>
                <p>Welcome to the Apartment Maintenance System</p>
            </div>
        </div>
    </div>
</body>
</html>