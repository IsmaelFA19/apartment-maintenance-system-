<?php
require_once '../includes/config.php';
requireRole('landlord');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['approve'])) {
    $request_id = (int)$_POST['request_id'];
    $stmt = $conn->prepare("UPDATE maintenance_requests SET status = 'approved_by_landlord' WHERE id = ?");
    $stmt->bind_param("i", $request_id);
    $stmt->execute();
}

$requests = [];
$result = $conn->query("SELECT r.*, u.full_name as tenant_name 
                       FROM maintenance_requests r 
                       JOIN users u ON r.tenant_id = u.id 
                       WHERE r.status = 'assigned' OR r.status = 'pending' 
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
    <title>Approve Requests - Landlord</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="dashboard">
        <?php include '../includes/sidebar.php'; ?>

        <div class="main-content">
            <div class="card">
                <h2>Approve Maintenance Requests</h2>
                
                <table style="width:100%;">
                    <tr>
                        <th>Request #</th>
                        <th>Tenant</th>
                        <th>Issue</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    <?php foreach ($requests as $req): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($req['request_number']); ?></td>
                        <td><?php echo htmlspecialchars($req['tenant_name']); ?></td>
                        <td><?php echo htmlspecialchars($req['title']); ?></td>
                        <td><?php echo ucfirst($req['status']); ?></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="request_id" value="<?php echo $req['id']; ?>">
                                <button type="submit" name="approve" class="btn btn-success">Approve for Technician</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</body>
</html>