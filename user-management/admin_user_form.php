<?php 
/**
 * USER MANAGEMENT FORM (ADD/EDIT)
 * Purpose: This file handles both the registration of new users and the updating of existing profiles.
 */

include '../layouts/layout_start.php';

// INITIALIZE VARIABLES: Prevents "Undefined variable" warnings during initial page load
$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$user = [];
$role_id = 0; 
$message = "";

// 2. FETCH EXISTING USER DATA (EDIT MODE ONLY)
if ($user_id > 0) {
    // Sanitize ID and fetch user details from the database
    $user_query = "SELECT * FROM users WHERE id = $user_id";
    $user_result = $con_main->query($user_query);
    if ($user_result && $user_result->num_rows > 0) {
        $user = $user_result->fetch_assoc();
        $role_id = (int)$user['role_id']; // Track role to handle conditional fields (like Company)
    }
}

// 3. FETCH DATA FOR DYNAMIC DROPDOWNS
// Get active roles and companies for the <select> menus
$roles_result = $con_main->query("SELECT id, name FROM roles WHERE status = 1 ORDER BY id ASC");
$companies_result = $con_main->query("SELECT id, name FROM companies WHERE status = 1 ORDER BY name ASC");

$message = '';
$error = '';

// --- 4. FORM PROCESSING (POST REQUEST) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and Sanitize Inputs to prevent SQL Injection
    $user_id    = (int)$_POST['user_id'];
    $first_name = mysqli_real_escape_string($con_main, trim($_POST['first_name']));
    $last_name  = mysqli_real_escape_string($con_main, trim($_POST['last_name']));
    $gender     = mysqli_real_escape_string($con_main, $_POST['gender']);
    $email      = mysqli_real_escape_string($con_main, trim($_POST['email']));
    $username   = mysqli_real_escape_string($con_main, trim($_POST['username']));
    $confirm_password   = mysqli_real_escape_string($con_main, trim($_POST['confirm_password']));
    $password   = $_POST['password'];
    $status     = (int)$_POST['status'];
    $role_id    = (int)$_POST['role_id'];

    $company_id = ($role_id == 2 && !empty($_POST['company_id'])) ? (int)$_POST['company_id'] : NULL;
// --- SERVER-SIDE VALIDATION ---
    
    // Check if Email already exists (excluding current user)
    $check_email = $con_main->query("SELECT id FROM users WHERE email = '$email' AND id != $user_id");
    // Check if Username already exists
    $check_user = $con_main->query("SELECT id FROM users WHERE username = '$username' AND id != $user_id");

    if ($check_email->num_rows > 0) {
        $error = "This email address is already registered.";
    } elseif ($check_user->num_rows > 0) {
        $error = "This username is already taken.";
    } elseif (!empty($password) && $password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif ($user_id == 0 && empty($password)) {
        $error = "Password is required for new users.";
    } elseif ($role_id == 2 && $company_id == NULL) {
        $error = "Please select a company for the Recruiter role.";
    }

    // logic: Only Recruiters (Role 2) require a Company ID


    // IMAGE UPLOAD LOGIC
    $profile_img_url = $user['profile_image'] ?? ''; // Keep old image if no new one is uploaded
    if (!empty($_FILES['profile_image']['name'])) {
        $fileName = time() . "_" . basename($_FILES['profile_image']['name']);
        $target = "../assets/uploads/profile_pics/" . $fileName;
        
        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target)) {
            $profile_img_url = "assets/uploads/profile_pics/" . $fileName;
        }
    }

    $pass_update = "";
    // Only update password if a new one is typed in the form
    if (!empty($password)) {
        $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
        $pass_update = ", `password` = '$hashed_pass'";
    }

    $company_update = "";
    if ($company_id !== NULL) {
        $company_id = (int)$company_id;
        $company_update = ", `company_id` = $company_id";
    }

    if ($user_id > 0) {
        // --- 5. UPDATE LOGIC ---

        $query = "UPDATE `users` SET 
                  `email`         = '$email', 
                  `username`      = '$username', 
                  `role_id`       = '$role_id', 
                  `first_name`    = '$first_name', 
                  `last_name`     = '$last_name', 
                  `profile_image` = '$profile_img_url', 
                  `gender`        = '$gender', 
                  `status`        = '$status'
                  $company_update
                  $pass_update 
                  WHERE `id`      = '$user_id'";
    } else {
        // --- 6. ADD (INSERT) LOGIC ---
    
        $query = "INSERT INTO `users` (`first_name`, `last_name`, `gender`, `status`, `email`, `username`, `password`, `role_id`, `company_id`, `profile_image`)
                  VALUES ('$first_name', '$last_name', '$gender', '$status', '$email', '$username', '$hashed_pass', '$role_id', '$company_id', '$profile_img_url')";
    }

    if ($con_main->query($query)) {
        // Redirect on Success
        header("Location: admin_user_list.php");
        exit();
    } else {
        $error = "Error: " . $con_main->error;
    }
}

// Include Layouts after processing logic to prevent "Headers Already Sent" errors
 
?>

<title><?php echo $user_id ? 'Update User' : 'User Registration'; ?></title>
<link rel="stylesheet" href="../assets/css/user_management.css">

<?php include '../layouts/header.php'; ?>

<div class="container" style="position: relative;">
    <div class="user-card">
        <h1><?php echo $user_id ? 'Update User Details' : 'User Registration'; ?></h1>

         <?php if ($message): ?>
            <div class="alert alert-success" id="pageAlert">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-error" id="pageAlert">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" style="margin-top: 70px;">
            
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">

            <div style="position: absolute; top: 30px; right: 55px; text-align: center; z-index: 10;">
                <div id="image-preview"
                    style="width: 100px; height: 100px; border: 3px solid var(--ink-color); border-radius: 50%; margin: 0 auto 10px; display: flex; justify-content: center; align-items: center; font-size: 40px; overflow: hidden; background-color: #f9f9f9;">
                    <span id="placeholder-icon"
                        style="<?php echo !empty($user['profile_image']) ? 'display:none;' : ''; ?>">👤</span>
                    <img id="user-img" src="../<?php echo $user['profile_image'] ?? ''; ?>"
                        style="<?php echo empty($user['profile_image']) ? 'display:none;' : ''; ?> width: 100%; height: 100%; object-fit: cover;">
                </div>
                <input type="file" id="user-photo-input" name="profile_image" accept="image/*" style="display: none;"
                    onchange="previewUserImage(this)">
                <div class="admin-actions" style="display: flex; flex-direction: row; gap: 5px;">
                    <button type="button" class="btn btn-add" style="padding: 5px 10px; font-size: 12px;"
                        onclick="document.getElementById('user-photo-input').click();">Add/Change</button>
                    <button type="button" class="btn btn-delete" style="padding: 5px 10px; font-size: 12px;"
                        onclick="removeUserImage()">Remove</button>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>First Name <span class="required">*</span></label>
                    <input type="text" name="first_name" value="<?php echo $user['first_name'] ?? ''; ?>" required>
                </div>
                <div class="form-group">
                    <label>Email <span class="required">*</span></label>
                    <input type="text" name="email" value="<?php echo $user['email'] ?? ''; ?>" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Last Name <span class="required">*</span></label>
                    <input type="text" name="last_name" value="<?php echo $user['last_name'] ?? ''; ?>" required>
                </div>
                <div class="form-group">
                    <label>User name <span class="required">*</span></label>
                    <input type="text" name="username" value="<?php echo $user['username'] ?? ''; ?>" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Gender <span class="required">*</span></label>
                    <div>
                        <label style="display:inline;"><input type="radio" name="gender" value="Male"
                                <?php echo (isset($user['gender']) && $user['gender'] == 'Male') ? 'checked' : ''; ?>> Male</label>
                        <label style="display:inline; margin-left: 10px;"><input type="radio" name="gender" value="Female"
                                <?php echo (isset($user['gender']) && $user['gender'] == 'Female') ? 'checked' : ''; ?>> Female</label>
                        <label style="display:inline; margin-left: 10px;"><input type="radio" name="gender" value="Other"
                                <?php echo (isset($user['gender']) && $user['gender'] == 'Other') ? 'checked' : ''; ?>> Other</label>
                    </div>
                </div>
                <div class="form-group">
                    <label>Password <?php echo $user_id ? '(Leave blank to keep current)' : '<span class="required">*</span>'; ?></label>
                    <input type="password" name="password" id="password"  autocomplete="new-password" <?php echo $user_id ? '' : 'required'; ?>>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Status <span class="required">*</span></label>
                    <select name="status">
                        <option value="1" <?php echo (isset($user['status']) && $user['status'] == 1) ? 'selected' : ''; ?>>Active</option>
                        <option value="0" <?php echo (isset($user['status']) && $user['status'] == 0) ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Confirm Password <span class="required">*</span></label>
                    <input type="password" name="confirm_password" autocomplete="new-password">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>User Role <span class="required">*</span></label>
                    <?php if ($user_id > 0): ?>
                        <select id="role_id" name="role_id_display" disabled style="background-color: #f4f4f4;">
                            <?php 
                            $roles_result->data_seek(0); 
                            while($role = $roles_result->fetch_assoc()): ?>
                                <option value="<?php echo $role['id']; ?>" <?php echo ($user['role_id'] == $role['id']) ? 'selected' : ''; ?>>
                                    <?php echo $role['name']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                        <input type="hidden" name="role_id" id="hidden_role_id" value="<?php echo $user['role_id']; ?>">
                    <?php else: ?>
                        <select name="role_id" id="role_id" required>
                            <option value="">Select Role</option>
                            <?php 
                            $roles_result->data_seek(0);
                            while($role = $roles_result->fetch_assoc()): ?>
                                <option value="<?php echo $role['id']; ?>"><?php echo $role['name']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    <?php endif; ?>
                </div>

                <div class="form-group" id="company_wrapper" style="display: none;">
                    <label>Company Name <span class="required">*</span></label>
                    <select name="company_id" id="company_id">
                        <option value="" disabled>Select Company</option>
                        <?php 
                        $companies_result->data_seek(0);
                        while($company = $companies_result->fetch_assoc()): ?>
                            <option value="<?php echo $company['id']; ?>" 
                                <?php echo (isset($user['company_id']) && $user['company_id'] == $company['id']) ? 'selected' : ''; ?>>
                                <?php echo $company['name']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="mt-20">
                    <div class="mt-20 d-flex" style="gap: 10px;">
                        <button type="button" class="btn-update" onclick="window.location.href='admin_user_list.php';">Close</button>
                        <?php if($user_id > 0): ?>
                            <button type="submit" class="btn-add">Update</button>
                        <?php else: ?>
                            <button type="submit" class="btn-add">Register</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="script.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {

        const alertBox = document.getElementById("pageAlert");

        if (alertBox) {
            setTimeout(() => {
                alertBox.classList.add("hide");

                setTimeout(() => {
                    alertBox.remove();
                }, 400);
            }, 3500); // hide after 3.5 seconds
        }

    });
</script>
<?php 
// Include layout ends
include '../layouts/footer.php'; 
include '../layouts/layout_end.php'; 
?>