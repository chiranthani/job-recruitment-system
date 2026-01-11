<?php include '../config/database.php'; ?>
<?php include '../layouts/layout_start.php'; ?>
<link rel="stylesheet" href="../assets/css/main.css">

<?php include '../layouts/header.php'; ?>

<?php 
$job_count_query = "SELECT COUNT(*) AS total FROM job_posts WHERE post_status = 'published' AND (expiry_date IS NULL OR expiry_date >= CURDATE())";
$stmt_jobs = $con_main->prepare($job_count_query);
$stmt_jobs->execute();
$job_count = $stmt_jobs->get_result()->fetch_assoc()['total'];

$company_count_query = "SELECT COUNT(*) AS total FROM companies WHERE status = 1";
$stmt_company = $con_main->prepare($company_count_query);
$stmt_company->execute();
$company_count = $stmt_company->get_result()->fetch_assoc()['total'];

$pending_count_query = "SELECT COUNT(*) AS total FROM companies WHERE admin_approval='PENDING'";
$stmt_pending = $con_main->prepare($pending_count_query);
$stmt_pending->execute();
$pending_count = $stmt_pending->get_result()->fetch_assoc()['total'];

$users_count_query = "SELECT COUNT(*) AS total FROM users WHERE is_deleted = 0";
$stmt_users = $con_main->prepare($users_count_query);
$stmt_users->execute();
$users_count = $stmt_users->get_result()->fetch_assoc()['total'];

?>
<div class="dashboard-container">
    <div class="welcome-section">
        <h2 class="page-title">Admin Dashboard</h2>
        <p class="page-sub-title">Manage and monitor the Job Recruitment System</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div style="font-size:18px; margin-bottom: 10px;">👤 <span> Total Users</span></div>
            <div class="stat-value">
                <div><?= $users_count ?? 0 ?></div>
            </div>
        </div>

        <div class="stat-card">
            <div style="font-size:18px; margin-bottom: 10px;">🏢 <span> Active Companies</span></div>
            <div class="stat-value">
                <div><?= $company_count ?? 0 ?></div>
            </div>
        </div>

        <div class="stat-card">
            <div style="font-size:18px; margin-bottom: 10px;">📄 <span> Active Posts</span></div>
            <div class="stat-value">
                <div><?= $job_count ?? 0 ?></div>
            </div>
        </div>

        <div class="stat-card">
            <div style="font-size:18px; margin-bottom: 10px;">⏳ <span> Pending Approvals</span></div>
            <div class="stat-value">
                <div><?= $pending_count ?? 0 ?></div>
            </div>
        </div>

    </div>

    <div class="quick-actions">
        <h2>Quick Actions</h2>
        <div class="action-grid">
            <a href="employer_verification.php" class="action-btn">🏢 Manage Companies</a>
            <a href="../user-management/admin_user_list.php" class="action-btn">👤 Manage Users</a>
        </div>
    </div>

</div>

<?php include '../layouts/footer.php'; ?>
<?php include '../layouts/layout_end.php'; ?>