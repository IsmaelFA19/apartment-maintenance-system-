<?php
require_once '../includes/config.php';
requireRole('admin');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = (int)$_POST['user_id'];
    $action = $_POST['action'];

    $new_status = ($action == 'approve') ? 'approved' : 'rejected';
    
    $stmt = $conn->prepare("UPDATE users SET status = ? WHERE id = ? AND role = 'technician'");
    $stmt->bind_param("si", $new_status, $user_id);
    $stmt->execute();
}

$pending = $conn->query("SELECT * FROM users WHERE role = 'technician' AND status = 'pending'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approve Technicians</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="dashboard">
        <?php include '../includes/sidebar.php'; ?>

        <div class="main-content">
            <div class="card">
                <h2>Pending Technician Approvals</h2>
                
                <?php if ($pending->num_rows == 0): ?>
                    <p>No pending approvals.</p>
                <?php else: ?>
                    <table style="width:100%;">
                        <tr>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                        <?php while ($user = $pending->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                    <button type="submit" name="action" value="approve" class="btn btn-primary">Approve</button>
                                    <button type="submit" name="action" value="reject" class="btn" style="background:#ef4444; color:white;">Reject</button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>