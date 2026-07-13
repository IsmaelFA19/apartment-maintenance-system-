<?php
require_once '../includes/config.php';
requireRole('tenant');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category = $_POST['category'];
    $priority = $_POST['priority'];
    $request_number = 'REQ-' . date('YmdHi');

    $image_path = null;
    if (isset($_FILES['problem_image']) && $_FILES['problem_image']['error'] == 0) {
        $target_dir = "../uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $image_path = $target_dir . time() . '_' . basename($_FILES["problem_image"]["name"]);
        move_uploaded_file($_FILES["problem_image"]["tmp_name"], $image_path);
    }

    $stmt = $conn->prepare("INSERT INTO maintenance_requests (request_number, tenant_id, title, description, category, priority, image_path) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sisssss", $request_number, $_SESSION['user_id'], $title, $description, $category, $priority, $image_path);
    
    if ($stmt->execute()) {
        header("Location: my_requests.php?success=1");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Request</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="dashboard">
        <?php include '../includes/sidebar.php'; ?>

        <div class="main-content">
            <div class="card">
                <h2>New Maintenance Request</h2>
                
                <form method="POST" enctype="multipart/form-data">
                    <input type="text" name="title" placeholder="Issue Title" required>
                    <textarea name="description" rows="6" placeholder="Describe the problem..." required></textarea>
                    
                    <select name="category" required>
                        <option value="plumbing">Plumbing</option>
                        <option value="electrical">Electrical</option>
                        <option value="general">General Maintenance</option>
                        <option value="other">Other</option>
                    </select>
                    
                    <select name="priority" required>
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>
                    
                    <label>Upload Photo (Optional)</label>
                    <input type="file" name="problem_image" accept="image/*">
                    
                    <button type="submit" class="btn btn-primary" style="width:100%; margin-top:20px;">Submit Request</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>