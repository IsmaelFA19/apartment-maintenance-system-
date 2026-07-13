<?php
require_once '../includes/config.php';
requireRole('tenant');

$requests = [];
$result = $conn->query("SELECT r.*, t.full_name as technician_name 
                       FROM maintenance_requests r 
                       LEFT JOIN users t ON r.assigned_to = t.id 
                       WHERE r.tenant_id = " . $_SESSION['user_id'] . " 
                       ORDER BY r.created_at DESC");

while ($row = $result->fetch_assoc()) {
    $requests[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Requests</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="dashboard">
        <?php include '../includes/sidebar.php'; ?>

        <div class="main-content">
            <div class="card">
                <h2>My Requests</h2>
                
                <?php if (isset($_GET['success'])): ?>
                    <p style="color:green;">Request submitted successfully!</p>
                <?php endif; ?>

                <table style="width:100%; border-collapse: collapse;">
                    <tr>
                        <th style="padding:14px; text-align:left;">Request #</th>
                        <th style="padding:14px; text-align:left;">Title</th>
                        <th style="padding:14px;">Category</th>
                        <th style="padding:14px;">Priority</th>
                        <th style="padding:14px;">Status</th>
                        <th style="padding:14px;">Technician</th>
                    </tr>
                    <?php if (empty($requests)): ?>
                        <tr><td colspan="6" style="padding:40px; text-align:center;">No requests yet.</td></tr>
                    <?php else: ?>
                        <?php foreach ($requests as $req): ?>
                        <tr>
                            <td style="padding:14px;"><?php echo htmlspecialchars($req['request_number']); ?></td>
                            <td style="padding:14px;"><?php echo htmlspecialchars($req['title']); ?></td>
                            <td style="padding:14px;"><?php echo ucfirst($req['category']); ?></td>
                            <td style="padding:14px;"><?php echo ucfirst($req['priority']); ?></td>
                            <td style="padding:14px;"><?php echo ucfirst($req['status']); ?></td>
                            <td style="padding:14px;"><?php echo $req['technician_name'] ? htmlspecialchars($req['technician_name']) : 'Not Assigned'; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>
</body>
</html>