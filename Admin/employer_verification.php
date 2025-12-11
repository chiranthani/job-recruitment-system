<!-- start page common elements -->
<?php include '../config/database.php'; ?>
<?php include '../layouts/layout_start.php'; ?>
<link rel="stylesheet" href="../assets/css/main.css">
<?php include '../layouts/header.php'; ?>
<!-- end page common elements-->

<?php
// Check if user is admin
// if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 3) {
//     header('Location: ../index.php');
//     exit();
// }

$message = '';

// Handle approval/rejection
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $company_id = $_POST['company_id'];
    $action = $_POST['action'];
    
    if ($action == 'approve') {
        $update = "UPDATE companies SET admin_approval = 'APPROVED', updatedAt = NOW() WHERE id = ?";
    } elseif ($action == 'reject') {
        $update = "UPDATE companies SET admin_approval = 'REJECTED', updatedAt = NOW() WHERE id = ?";
    }
    
    if (isset($update)) {
        $stmt = $con_main->prepare($update);
        $stmt->bind_param("i", $company_id);
        if ($stmt->execute()) {
            $message = 'Company ' . ($action == 'approve' ? 'approved' : 'rejected') . ' successfully!';
        }
    }
}

// Handle activate/deactivate
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['toggle_status'])) {
    $company_id = $_POST['company_id'];
    $new_status = $_POST['new_status'];
    
    $update = "UPDATE companies SET status = ?, updatedAt = NOW() WHERE id = ?";
    $stmt = $con_main->prepare($update);
    $stmt->bind_param("ii", $new_status, $company_id);
    if ($stmt->execute()) {
        $message = 'Company status updated successfully!';
    }
}

// Fetch all companies
$query = "SELECT c.*, u.email, u.first_name, u.last_name, u.createdAt as user_created 
          FROM companies c 
          LEFT JOIN users u ON u.company_id = c.id 
          WHERE u.role_id = 2
          ORDER BY c.createdAt DESC";
$result = $con_main->query($query);
?>

<!-- start page main content -->
<section class="admin-container">
    <div class="admin-card">
        <h2>Employer Verification & Management</h2>
        
        <?php if ($message): ?>
            <div class="alert"><?php echo $message; ?></div>
        <?php endif; ?>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Company Name</th>
                        <th>Registration No</th>
                        <th>Email</th>
                        <th>Approval Status</th>
                        <th>Active Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($company = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($company['name']); ?></td>
                            <td><?php echo htmlspecialchars($company['registration_no']); ?></td>
                            <td><?php echo htmlspecialchars($company['email']); ?></td>
                            <td>
                                <span class="status-badge <?php echo strtolower($company['admin_approval']); ?>">
                                    <?php echo $company['admin_approval']; ?>
                                </span>
                            </td>
                            <td>
                                <span class="<?php echo $company['status'] == 1 ? 'active-status' : 'inactive-status'; ?>">
                                    <?php echo $company['status'] == 1 ? 'Active' : 'Inactive'; ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($company['admin_approval'] == 'PENDING'): ?>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="company_id" value="<?php echo $company['id']; ?>">
                                        <button type="submit" name="action" value="approve" class="btn-sm btn-success">Approve</button>
                                        <button type="submit" name="action" value="reject" class="btn-sm btn-danger">Reject</button>
                                    </form>
                                <?php endif; ?>
                                
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="company_id" value="<?php echo $company['id']; ?>">
                                    <input type="hidden" name="new_status" value="<?php echo $company['status'] == 1 ? 0 : 1; ?>">
                                    <button type="submit" name="toggle_status" class="btn-sm <?php echo $company['status'] == 1 ? 'btn-warning' : 'btn-info'; ?>">
                                        <?php echo $company['status'] == 1 ? 'Deactivate' : 'Reactivate'; ?>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<!-- end page main content -->

<?php include '../layouts/footer.php'; ?>
<?php include '../layouts/layout_end.php'; ?>
