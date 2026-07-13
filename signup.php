<?php
require_once '../includes/config.php';

if (isLoggedIn()) {
    header("Location: dashboard.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = trim($_POST['full_name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $apartment_number = trim($_POST['apartment_number']);
    $role = $_POST['role'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Restrict role to an allowed whitelist so nobody can POST role=admin directly
    $allowed_roles = ['tenant', 'landlord', 'technician'];
    if (!in_array($role, $allowed_roles, true)) {
        $role = 'tenant';
    }

    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $user_status = ($role == 'technician') ? 'pending' : 'approved';

        $stmt = $conn->prepare("INSERT INTO users (full_name, username, email, phone, apartment_number, password, role, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $full_name, $username, $email, $phone, $apartment_number, $hashed_password, $role, $user_status);
        
        if ($stmt->execute()) {
            $success = ($role == 'technician') ? 
                "Technician application submitted. Waiting for admin approval." : 
                "Account created successfully!";
        } else {
            $error = "Username or email already exists!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="dashboard" style="justify-content: center; align-items: center; min-height: 100vh;">
        <div class="card" style="max-width: 480px; width: 100%;">
            <h2>Create Account</h2>
            
            <?php if ($error): ?><p style="color:red;"><?php echo $error; ?></p><?php endif; ?>
            <?php if ($success): ?>
                <p style="color:green;"><?php echo $success; ?></p>
                <p><a href="login.php">Go to Login</a></p>
            <?php else: ?>
            <form method="POST">
                <input type="text" name="full_name" placeholder="Full Name" required>
                <input type="text" name="username" placeholder="Username" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="text" name="phone" placeholder="Phone Number">
                <input type="text" name="apartment_number" placeholder="Apartment Number">
                
                <select name="role" required>
                    <option value="tenant">Tenant</option>
                    <option value="landlord">Landlord</option>
                    <option value="technician">Technician (Needs Approval)</option>
                </select>
                
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                
                <button type="submit" class="btn btn-primary" style="width:100%; margin-top:15px;">Register</button>
            </form>
            <?php endif; ?>
            
            <p style="text-align:center; margin-top:20px;">
                Already have an account? <a href="login.php">Login</a>
            </p>
        </div>
    </div>
</body>
</html>