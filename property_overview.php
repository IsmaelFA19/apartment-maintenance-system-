<?php
require_once '../includes/config.php';
requireRole('landlord');

$requests = [];
$result = $conn->query("SELECT r.*, u.full_name as tenant_name 
                       FROM maintenance_requests r 
                       JOIN users u ON r.tenant_id = u.id 
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
    <title>Property Overview</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="dashboard">
        <?php include '../includes/sidebar.php'; ?>

        <div class="main-content">
            <div class="card">
                <h2>Property Maintenance Overview</h2>
                <table style="width:100%;">
                    <tr>
                        <th>Request #</th>
                        <th>Tenant</th>
                        <th>Issue</th>
                        <th>Status</th>
                    </tr>
                    <?php foreach ($requests as $req): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($req['request_number']); ?></td>
                        <td><?php echo htmlspecialchars($req['tenant_name']); ?></td>
                        <td><?php echo htmlspecialchars($req['title']); ?></td>
                        <td><?php echo ucfirst($req['status']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</body>
</html>