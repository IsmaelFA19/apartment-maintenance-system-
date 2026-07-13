<?php
require_once '../includes/config.php';
requireRole('admin');   // Can also allow technician later

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request_id = (int)$_POST['request_id'];
    $new_status = $_POST['status'];
    $update_text = trim($_POST['update_text']);

    $stmt = $conn->prepare("UPDATE maintenance_requests SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $request_id);
    $stmt->execute();

    // Add update comment
    $stmt2 = $conn->prepare("INSERT INTO request_updates (request_id, user_id, update_text, status_update) VALUES (?, ?, ?, ?)");
    $stmt2->bind_param("iiss", $request_id, $_SESSION['user_id'], $update_text, $new_status);
    $stmt2->execute();

    echo "<script>alert('Status updated successfully!'); window.location.href='all_requests.php';</script>";
    exit();
}

// Get request details
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $conn->prepare("SELECT * FROM maintenance_requests WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$request = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Status</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; }
        .form-box { max-width: 600px; margin: 50px auto; background: white; padding: 30px; border-radius: 10px; }
        input, textarea, select, button { width: 100%; padding: 12px; margin: 10px 0; border-radius: 5px; }
        button { background: #27ae60; color: white; border: none; }
    </style>
</head>
<body>
    <div class="form-box">
        <h2>Update Request Status</h2>
        <p><strong>Request:</strong> <?php echo $request['request_number']; ?> - <?php echo $request['title']; ?></p>
        
        <form method="POST">
            <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
            
            <select name="status" required>
                <option value="pending">Pending</option>
                <option value="assigned">Assigned</option>
                <option value="in_progress">In Progress</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select>
            
            <textarea name="update_text" rows="4" placeholder="Add comment or progress note..."></textarea>
            
            <button type="submit">Update Status</button>
        </form>
    </div>
</body>
</html>