<?php
require_once '../includes/config.php';
requireRole('admin');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['assign'])) {
    $request_id = (int)$_POST['request_id'];
    $tech_id = (int)$_POST['technician_id'];
    
    $stmt = $conn->prepare("UPDATE maintenance_requests SET assigned_to = ?, status = 'assigned' WHERE id = ?");
    $stmt->bind_param("ii", $tech_id, $request_id);
    $stmt->execute();
}

$requests = [];
$result = $conn->query("SELECT r.*, u.full_name as tenant_name, t.full_name as technician_name 
                       FROM maintenance_requests r 
                       JOIN users u ON r.tenant_id = u.id 
                       LEFT JOIN users t ON r.assigned_to = t.id 
                       ORDER BY r.created_at DESC");

$technicians = [];
$tech_result = $conn->query("SELECT id, full_name FROM users WHERE role = 'technician'");
while ($t = $tech_result->fetch_assoc()) {
    $technicians[] = $t;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Requests</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="dashboard">
        <?php include '../includes/sidebar.php'; ?>

        <div class="main-content">
            <div class="card">
                <h2>All Maintenance Requests</h2>
                
                <table style="width:100%; border-collapse: collapse;">
                    <tr>
                        <th style="padding:14px; text-align:left;">Request #</th>
                        <th style="padding:14px; text-align:left;">Tenant</th>
                        <th style="padding:14px; text-align:left;">Issue</th>
                        <th style="padding:14px;">Status</th>
                        <th style="padding:14px;">Technician</th>
                        <th style="padding:14px;">Action</th>
                    </tr>
                    <?php while ($req = $result->fetch_assoc()): ?>
                    <tr>
                        <td style="padding:14px;"><?php echo htmlspecialchars($req['request_number']); ?></td>
                        <td style="padding:14px;"><?php echo htmlspecialchars($req['tenant_name']); ?></td>
                        <td style="padding:14px;"><?php echo htmlspecialchars($req['title']); ?></td>
                        <td style="padding:14px;"><?php echo ucfirst($req['status']); ?></td>
                        <td style="padding:14px;"><?php echo $req['technician_name'] ?: 'Not Assigned'; ?></td>
                        <td style="padding:14px;">
                            <?php if ($req['status'] != 'assigned' && $req['status'] != 'completed'): ?>
                            <form method="POST" style="display:flex; gap:8px;">
                                <input type="hidden" name="request_id" value="<?php echo $req['id']; ?>">
                                <select name="technician_id">
                                    <?php foreach ($technicians as $tech): ?>
                                        <option value="<?php echo $tech['id']; ?>"><?php echo $tech['full_name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="submit" name="assign" class="btn btn-primary">Assign</button>
                            </form>
                            <?php else: ?>
                                <span style="color:green;">Assigned</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>
    </div>
</body>
</html>