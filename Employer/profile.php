<!-- start page common elements -->
<?php include '../config/database.php'; ?>
<?php include '../layouts/layout_start.php'; ?>
<?php include '../layouts/header.php'; ?>
<!-- end page common elements-->

<?php
// Check if user is logged in and is a recruiter
// session_start(); // Already started in layout
// if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2) {
//     header('Location: ../index.php');
//     exit();
// }

$user_id = $_SESSION['user_id'] ?? 1; // Default for testing

// Fetch company details
$query = "SELECT c.*, u.email, u.first_name, u.last_name 
          FROM companies c 
          JOIN users u ON u.company_id = c.id 
          WHERE u.id = ?";
$stmt = $con_main->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$company = $result->fetch_assoc();

$approval_status = $company['admin_approval'] ?? 'PENDING';
$status_class = strtolower($approval_status);
?>

<!-- start page main content -->
<section class="profile-container">
    <div class="profile-card">
        <div class="profile-header">
            <h2>Company Profile</h2>
            <span class="status-badge <?php echo $status_class; ?>">
                <?php echo $approval_status; ?>
            </span>
        </div>

        <?php if ($company): ?>
            <div class="info-row">
                <span class="info-label">Company Name:</span>
                <span class="info-value"><?php echo htmlspecialchars($company['name']); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Registration No:</span>
                <span class="info-value"><?php echo htmlspecialchars($company['registration_no']); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Email:</span>
                <span class="info-value"><?php echo htmlspecialchars($company['email']); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Address:</span>
                <span class="info-value"><?php echo htmlspecialchars($company['address'] ?? 'Not provided'); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Website:</span>
                <span class="info-value">
                    <?php if ($company['website_link']): ?>
                        <a href="<?php echo htmlspecialchars($company['website_link']); ?>" target="_blank">
                            <?php echo htmlspecialchars($company['website_link']); ?>
                        </a>
                    <?php else: ?>
                        Not provided
                    <?php endif; ?>
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Description:</span>
                <span class="info-value"><?php echo nl2br(htmlspecialchars($company['description'] ?? 'Not provided')); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Status:</span>
                <span class="info-value"><?php echo $company['status'] == 1 ? 'Active' : 'Inactive'; ?></span>
            </div>

            <div style="margin-top: 30px;">
                <a href="edit_profile.php" class="btn btn-primary">Edit Profile</a>
            </div>
        <?php else: ?>
            <p>No company profile found. Please contact administrator.</p>
        <?php endif; ?>
    </div>
</section>
<!-- end page main content -->

<?php include '../layouts/footer.php'; ?>
<?php include '../layouts/layout_end.php'; ?>
