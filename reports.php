<?php
require_once '../includes/config.php';
requireRole('admin');

$stats = $conn->query("SELECT 
    COUNT(*) as total_requests,
    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
    SUM(CASE WHEN status = 'assigned' THEN 1 ELSE 0 END) as assigned,
    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed
    FROM maintenance_requests")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="dashboard">
        <?php include '../includes/sidebar.php'; ?>

        <div class="main-content">
            <div class="card">
                <h2>System Reports</h2>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px;">
                    <div class="card" style="text-align:center;">
                        <h3 style="color:#3b82f6;"><?php echo $stats['total_requests']; ?></h3>
                        <p>Total Requests</p>
                    </div>
                    <div class="card" style="text-align:center;">
                        <h3 style="color:#f59e0b;"><?php echo $stats['pending']; ?></h3>
                        <p>Pending</p>
                    </div>
                    <div class="card" style="text-align:center;">
                        <h3 style="color:#8b5cf6;"><?php echo $stats['assigned']; ?></h3>
                        <p>Assigned</p>
                    </div>
                    <div class="card" style="text-align:center;">
                        <h3 style="color:#10b981;"><?php echo $stats['completed']; ?></h3>
                        <p>Completed</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>