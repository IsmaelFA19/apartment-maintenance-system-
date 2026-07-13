<?php
require_once '../includes/config.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="dashboard">
        <?php include '../includes/sidebar.php'; ?>

        <div class="main-content">
            <div class="card">
                <h2>Profile</h2>
                
                <div style="text-align:center; margin:30px 0;">
                    <div style="width:140px; height:140px; background:#e2e8f0; border-radius:50%; margin:0 auto 20px; display:flex; align-items:center; justify-content:center; font-size:60px;">
                        👤
                    </div>
                    <h3><?php echo htmlspecialchars($_SESSION['full_name']); ?></h3>
                    <p style="color:#64748b;"><?php echo ucfirst($_SESSION['role']); ?></p>
                </div>

                <p><strong>Username:</strong> <?php echo htmlspecialchars($_SESSION['username']); ?></p>
                <p><strong>User ID:</strong> <?php echo $_SESSION['user_id']; ?></p>
            </div>
        </div>
    </div>
</body>
</html>