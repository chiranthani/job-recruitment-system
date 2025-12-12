<!-- start page common elements -->
<?php include '../config/database.php'; ?>
<?php include '../layouts/layout_start.php'; ?>
<link rel="stylesheet" href="../assets/css/main.css">
<?php include '../layouts/header.php'; ?>
<!-- end page common elements-->

<?php
$user_id = $_SESSION['user_id'] ?? 1;
$message = '';
$error = '';

// Fetch company details
$query = "SELECT c.* FROM companies c JOIN users u ON u.company_id = c.id WHERE u.id = ?";
$stmt = $con_main->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$company = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $address = trim($_POST['address']);
    $website_link = trim($_POST['website_link']);
    $description = trim($_POST['description']);
    
    if (empty($name)) {
        $error = 'Company name is required';
    } else {
        $update_query = "UPDATE companies SET name = ?, address = ?, website_link = ?, description = ?, updatedAt = NOW() WHERE id = ?";
        $update_stmt = $con_main->prepare($update_query);
        $update_stmt->bind_param("ssssi", $name, $address, $website_link, $description, $company['id']);
        
        if ($update_stmt->execute()) {
            $message = 'Profile updated successfully!';
            // Refresh company data
            $stmt->execute();
            $result = $stmt->get_result();
            $company = $result->fetch_assoc();
        } else {
            $error = 'Failed to update profile';
        }
    }
}
?>

<!-- start page main content -->
<section class="form-container">
    <div class="form-card">
        <h2>Edit Company Profile</h2>
        
        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>Company Name *</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($company['name']); ?>" required>
            </div>

            <div class="form-group">
                <label>Registration Number</label>
                <input type="text" value="<?php echo htmlspecialchars($company['registration_no']); ?>" disabled>
                <small style="color: #666;">Registration number cannot be changed</small>
            </div>

            <div class="form-group">
                <label>Address</label>
                <input type="text" name="address" value="<?php echo htmlspecialchars($company['address'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label>Website</label>
                <input type="url" name="website_link" value="<?php echo htmlspecialchars($company['website_link'] ?? ''); ?>" placeholder="https://example.com">
            </div>

            <div class="form-group">
                <label>Company Description</label>
                <textarea name="description"><?php echo htmlspecialchars($company['description'] ?? ''); ?></textarea>
            </div>

            <div>
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="company_profile.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</section>
<!-- end page main content -->

<?php include '../layouts/footer.php'; ?>
<?php include '../layouts/layout_end.php'; ?>
