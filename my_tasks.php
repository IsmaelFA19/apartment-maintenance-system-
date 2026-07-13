<?php
require_once '../includes/config.php';
requireRole('technician');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_task'])) {
    $request_id = (int)$_POST['request_id'];
    $new_status = $_POST['status'];
    $comment = trim($_POST['comment']);

    $stmt = $conn->prepare("UPDATE maintenance_requests SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $request_id);
    $stmt->execute();

    echo "<script>alert('Task updated successfully!');</script>";
}

$tasks = [];
$result = $conn->query("SELECT * FROM maintenance_requests WHERE assigned_to = " . $_SESSION['user_id'] . " ORDER BY created_at DESC");
while ($row = $result->fetch_assoc()) {
    $tasks[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Tasks</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="dashboard">
        <?php include '../includes/sidebar.php'; ?>

        <div class="main-content">
            <div class="card">
                <h2>My Tasks</h2>
                
                <?php foreach ($tasks as $task): ?>
                <div class="card" style="margin-bottom:25px;">
                    <h3><?php echo htmlspecialchars($task['title']); ?></h3>
                    <p><strong>Request #:</strong> <?php echo $task['request_number']; ?></p>
                    <p><strong>Status:</strong> <?php echo ucfirst($task['status']); ?></p>
                    
                    <form method="POST">
                        <input type="hidden" name="request_id" value="<?php echo $task['id']; ?>">
                        <select name="status">
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                        </select>
                        <textarea name="comment" rows="3" placeholder="Add notes..."></textarea>
                        <button type="submit" name="update_task" class="btn btn-primary">Update Task</button>
                    </form>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>
</html>