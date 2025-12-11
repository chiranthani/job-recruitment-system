<!-- start page common elements -->
<?php include '../config/database.php'; ?>
<?php include '../layouts/layout_start.php'; ?>
<?php include '../layouts/header.php'; ?>
<!-- end page common elements-->

<?php
$user_id = $_SESSION['user_id'] ?? 1;

// Fetch company and user details
$query = "SELECT c.*, u.email, u.first_name, u.last_name, u.last_login 
          FROM companies c 
          JOIN users u ON u.company_id = c.id 
          WHERE u.id = ?";
$stmt = $con_main->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

// Count job posts
$job_count_query = "SELECT COUNT(*) as total FROM job_posts WHERE company_id = ? AND is_deleted = 0";
$stmt_jobs = $con_main->prepare($job_count_query);
$stmt_jobs->bind_param("i", $data['id']);
$stmt_jobs->execute();
$job_count = $stmt_jobs->get_result()->fetch_assoc()['total'];

$approval_status = $data['admin_approval'];
$is_approved = $approval_status == 'APPROVED';
$is_active = $data['status'] == 1;
?>

<!-- start page main content -->
<section class="dashboard-container">
    <div class="welcome-section">
        <h1>Welcome, <?php echo htmlspecialchars($data['first_name'] ?? 'Employer'); ?>!</h1>
        <p><?php echo htmlspecialchars($data['name']); ?></p>
    </div>

    <?php if (!$is_approved): ?>
        <?php if ($approval_status == 'PENDING'): ?>
            <div class="alert-box alert-warning">
                <strong>⏳ Verification Pending</strong><br>
                Your company profile is awaiting admin approval. You'll be able to post jobs once your account is verified.
            </div>
        <?php elseif ($approval_status == 'REJECTED'): ?>
            <div class="alert-box alert-danger">
                <strong>❌ Verification Rejected</strong><br>
                Your company profile verification was rejected. Please contact support for more information.
            </div>
        <?php endif; ?>
    <?php elseif (!$is_active): ?>
        <div class="alert-box alert-warning">
            <strong>⚠️ Account Inactive</strong><br>
            Your account has been deactivated. Please contact support to reactivate your account.
        </div>
    <?php else: ?>
        <div class="alert-box alert-success">
            <strong>✅ Account Verified</strong><br>
            Your company profile is verified and active. You can now post jobs and manage applications.
        </div>
    <?php endif; ?>

    <div class="stats-grid">
        <div class="stat-card">
            <h3>Verification Status</h3>
            <span class="status-badge <?php echo strtolower($approval_status); ?>">
                <?php echo $approval_status; ?>
            </span>
        </div>
        <div class="stat-card">
            <h3>Account Status</h3>
            <div class="stat-value" style="font-size: 20px; color: <?php echo $is_active ? '#28a745' : '#dc3545'; ?>">
                <?php echo $is_active ? 'Active' : 'Inactive'; ?>
            </div>
        </div>
        <div class="stat-card">
            <h3>Total Job Posts</h3>
            <div class="stat-value"><?php echo $job_count; ?></div>
        </div>
        <div class="stat-card">
            <h3>Last Login</h3>
            <div class="stat-value" style="font-size: 16px;">
                <?php echo $data['last_login'] ? date('M d, Y', strtotime($data['last_login'])) : 'First time'; ?>
            </div>
        </div>
    </div>

    <div class="quick-actions">
        <h2>Quick Actions</h2>
        <div class="action-grid">
            <a href="profile.php" class="action-btn">
                👤 View Profile
            </a>
            <a href="edit_profile.php" class="action-btn">
                ✏️ Edit Profile
            </a>
            <a href="../Jobs/create.php" class="action-btn <?php echo (!$is_approved || !$is_active) ? 'disabled' : ''; ?>" 
               <?php echo (!$is_approved || !$is_active) ? 'onclick="return false;"' : ''; ?>>
                ➕ Post New Job
            </a>
            <a href="../Jobs/job_list.php" class="action-btn">
                📋 My Job Posts
            </a>
            <a href="../help.php" class="action-btn">
                ❓ Help & Support
            </a>
        </div>
    </div>
</section>
<!-- end page main content -->

<?php include '../layouts/footer.php'; ?>
<?php include '../layouts/layout_end.php'; ?>
