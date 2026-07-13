<div class="sidebar">
    <h3>AMS</h3>
    <p><strong><?php echo htmlspecialchars($_SESSION['full_name']); ?></strong></p>
    <p style="color:#94a3b8;"><?php echo ucfirst($_SESSION['role']); ?></p>
    
    <a href="dashboard.php" class="nav-link">Dashboard</a>
    
    <?php if ($_SESSION['role'] == 'tenant'): ?>
        <a href="submit_request.php" class="nav-link">New Request</a>
        <a href="my_requests.php" class="nav-link">My Requests</a>
    <?php elseif ($_SESSION['role'] == 'admin'): ?>
        <a href="all_requests.php" class="nav-link">All Requests</a>
        <a href="approve_users.php" class="nav-link">Approve Technicians</a>
        <a href="reports.php" class="nav-link">Reports</a>
    <?php elseif ($_SESSION['role'] == 'technician'): ?>
        <a href="my_tasks.php" class="nav-link">My Tasks</a>
    <?php elseif ($_SESSION['role'] == 'landlord'): ?>
        <a href="property_overview.php" class="nav-link">Maintenance Reports</a>
        <a href="landlord_approve.php" class="nav-link">Review Activities</a>
    <?php endif; ?>
    
    <a href="profile.php" class="nav-link">Profile</a>
    <a href="logout.php" class="nav-link">Logout</a>
</div>