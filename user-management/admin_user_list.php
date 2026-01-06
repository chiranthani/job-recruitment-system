<?php 
include '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_user'])) {
    
    // 1. Get the ID and Status from the form
    $user_id = (int)$_POST['user_id']; 
    $status = (int)$_POST['status']; // Captures 1 or 0

    // 2. Run the update query
    $sql = "UPDATE users SET status = '$status' WHERE id = '$user_id'";

    if ($con_main->query($sql)) {
        // Redirect back to your User List View after saving
        header("Location: admin_user_list.php?success=1");
        exit();
    }
}
// Fetch the requested columns from the users table
$query = "SELECT id, first_name, last_name, email, role_id, status FROM users WHERE is_deleted = 0";
$result = $con_main->query($query);


include '../layouts/layout_start.php'; 
?>

<title>User List View</title>
<link rel="stylesheet" href="style.css">

<?php include '../layouts/header.php'; ?>


    <div class="container" style="max-width: 1000px;">
        <h1 style="text-align: left;">User List View</h1>

        <div class="d-flex justify-between mt-20" style="border-bottom: 2px dashed var(--ink-color); padding-bottom: 20px; margin-bottom: 20px;">
            <div style="flex: 2; margin-right: 20px; display: flex; align-items: center; border: 2px solid var(--ink-color); padding: 5px; border-radius: 10px;">
                <span style="font-size: 1.5rem; margin-right: 10px;">🔍</span>
                <input type="text" placeholder="Search by Name, Email or ID" style="border: none; flex: 1;">
            </div>
            <button class="btn-add" a href="../admin_user_form.php">+ Add New User</button>
        </div>

        <div class="d-flex justify-between" style="align-items: flex-end;">
            <div>
                <button class="btn-add">All Users</button>
                 <button class="btn-add">Job Seekers</button>
            </div>
            <div class="d-flex" style="gap: 20px;">
        <div class="form-group" style="margin-bottom: 0;">
            <label>Status</label>
            <select name="status" style="border: 2px solid var(--ink-color); padding: 8px; border-radius: 5px; width: 100%;">
                <option value="1" <?php echo (isset($user['status']) && $user['status'] == 1) ? 'selected' : ''; ?>>
                Active
                </option>
                <option value="0" <?php echo (isset($user['status']) && $user['status'] == 0) ? 'selected' : ''; ?>>
                Inactive
                </option>
            </select>
        </div>
                 <div class="form-group" style="margin-bottom: 0;">
                    <label>Date Range</label>
                    <select style="border: 2px solid var(--ink-color);">
                        <option>Last 30 Days</option>
                    </select>
                </div>
            </div>
        </div>
    
<table class="admin-table">
        <thead>
            <tr>
                <th>User ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 12px;">#<?php echo $row['id']; ?></td>
                        
                        <td style="padding: 12px;"><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
                        
                        <td style="padding: 12px;"><?php echo $row['email']; ?></td>
                        
                        <td style="padding: 12px;">
                            <?php 
                                if($row['role_id'] == 1) echo "Candidate";
                                elseif($row['role_id'] == 2) echo "Recruiter";
                                elseif($row['role_id'] == 3) echo "Admin";
                            ?>
                        </td>
                        
                        <td style="padding: 12px;">
                            <?php echo ($row['status'] == 1) ? '<span style="color: green;">Active</span>' : '<span style="color: red;">Inactive</span>'; ?>
                        </td>
                        
                        <td style="padding: 12px;">
                            <a href="admin_user_form.php?id=<?php echo $row['id']; ?>" style="color: blue; text-decoration: none;">View</a>|  
                            <a href="admin_user_form.php?id=<?php echo $row['id']; ?>" style="color: orange; text-decoration: none;">Update</a>| 
                            <a href="admin_user_form.php?id=<?php echo $row['id']; ?>" style="color: red; text-decoration: none;" onclick="return confirm('Delete this user?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6" style="padding: 20px; text-align: center;">No users found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    
    <div class="d-flex mt-20" style="justify-content: flex-end; gap: 10px;">
        <button class="btn-update">Previous</button>
        <button class="btn-add">Next</button>
    </div>
    
</div>
</div>



<script src="script.js"></script>

<?php include '../layouts/footer.php'; ?>
<?php include '../layouts/layout_end.php'; ?>