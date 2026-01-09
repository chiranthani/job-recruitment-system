<?php 
include '../config/database.php'; 

// 1. Fetch User Data if 'id' is provided (Edit Mode)
$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$user = [];
if ($user_id > 0) {
    // Select based on users table structure
    $user_query = "SELECT * FROM users WHERE id = $user_id";
    $user_result = $con_main->query($user_query);
    $user = $user_result->fetch_assoc();
}

// 2. Fetch Roles from the database
$roles_query = "SELECT id, name FROM roles WHERE status = 1 ORDER BY id ASC";
$roles_result = $con_main->query($roles_query);

// 3. Fetch Cities/Towns (Locations)
$location_query = "SELECT id, name FROM locations WHERE status = 1 ORDER BY name ASC";
$locations_result = $con_main->query($location_query);

// --- 3. FORM PROCESSING (POST REQUEST) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and capture inputs for the users table
    $user_id    = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
    $first_name = mysqli_real_escape_string($con_main, trim($_POST['first_name']));
    $last_name  = mysqli_real_escape_string($con_main, trim($_POST['last_name']));
    $gender     = mysqli_real_escape_string($con_main, $_POST['gender']);
    $status     = (int)$_POST['status'];
    $email      = mysqli_real_escape_string($con_main, trim($_POST['email']));
    $username   = mysqli_real_escape_string($con_main, trim($_POST['username']));
    $role_id    = (int)$_POST['role_id'];
    $password   = $_POST['password'];

    // --- 3a. IMAGE UPLOAD LOGIC ---
    $profile_img_url = '';
    if (!empty($_FILES['profile_image']['name'])) {
        $fileName = time() . "_" . basename($_FILES['profile_image']['name']);
        $target = "../assets/uploads/profile_pics/" . $fileName;

        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target)) {
            $profile_img_url = "assets/uploads/profile_pics/" . $fileName;
        }
    }

    // --- 3b. ADD vs UPDATE (UPSERT) LOGIC ---
    if ($user_id > 0) {
        // --- UPDATE LOGIC ---
        $pass_update = "";
        if (!empty($password)) {
            $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
            $pass_update = ", password = '$hashed_pass'";
        }

        $img_update = "";
        if ($profile_img_url != '') {
            $img_update = ", profile_image = '$profile_img_url'";
        }

        $query = "UPDATE users SET 
                  first_name = '$first_name', 
                  last_name = '$last_name', 
                  gender = '$gender', 
                  status = '$status', 
                  email = '$email', 
                  username = '$username', 
                  role_id = '$role_id'
                  $pass_update
                  $img_update
                  WHERE id = '$user_id'";
        
        $action_label = "updated";
    } else {
        // --- ADD LOGIC ---
        // 1. Check if email already exists before adding
        $check_email = mysqli_query($con_main, "SELECT id FROM users WHERE email = '$email' LIMIT 1");
        
        if (mysqli_num_rows($check_email) > 0) {
            $message = "Error: This email is already registered.";
            $query = false; // Prevent execution
        } else {
            $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO users (first_name, last_name, gender, status, email, username, password, role_id, profile_image)
                      VALUES ('$first_name', '$last_name', '$gender', '$status', '$email', '$username', '$hashed_pass', '$role_id', '$profile_img_url')";
            
            $action_label = "added";
        }
    }

    // --- 3c. EXECUTION ---
    if ($query && $con_main->query($query)) {
        // Successful operation
        $success_trigger = true;
        $message = "User $action_label successfully!";
        
        // Update Session if the admin just edited their own account
        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $user_id) {
            $_SESSION['username'] = $username;
        }
    } elseif (!isset($message)) {
        $message = "Database Error: " . $con_main->error;
    }
}

include '../layouts/layout_start.php'; 
?>

<title><?php echo $user_id ? 'Update User' : 'User Registration'; ?></title>
<link rel="stylesheet" href="style.css">

<?php include '../layouts/header.php'; ?>

<div class="container" style="position: relative;">
    <div class="user-card">
    <h1><?php echo $user_id ? 'Update User Details' : 'User Registration'; ?></h1>
    
    <form action="process_user.php" method="POST" enctype="multipart/form-data" style="margin-top: 70px;">
        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">

        <div style="position: absolute; top: 30px; right: 55px; text-align: center; z-index: 10;">
            <div id="image-preview" style="width: 100px; height: 100px; border: 3px solid var(--ink-color); border-radius: 50%; margin: 0 auto 10px; display: flex; justify-content: center; align-items: center; font-size: 40px; overflow: hidden; background-color: #f9f9f9;">
                <span id="placeholder-icon" style="<?php echo !empty($user['profile_image']) ? 'display:none;' : ''; ?>">👤</span>
                <img id="user-img" src="../<?php echo $user['profile_image'] ?? ''; ?>" 
                     style="<?php echo empty($user['profile_image']) ? 'display:none;' : ''; ?> width: 100%; height: 100%; object-fit: cover;">
            </div>
            <input type="file" id="user-photo-input" name="profile_image" accept="image/*" style="display: none;" onchange="previewUserImage(this)">
            <div class="admin-actions" style="display: flex; flex-direction: row; gap: 5px;">
                <button type="button" class="btn btn-add" style="padding: 5px 10px; font-size: 12px;" onclick="document.getElementById('user-photo-input').click();">Add/Change</button>
                <button type="button" class="btn btn-delete" style="padding: 5px 10px; font-size: 12px;" onclick="removeUserImage()">Remove</button>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>First Name <span class="required">*</span></label>
                <input type="text" name="first_name" value="<?php echo $user['first_name'] ?? ''; ?>" required>
            </div>
            <div class="form-group">
                 <label>Country <span class="required">*</span></label>
                 <select name="country" id="country-select" onchange="updateRegions()" required>
                    <option value="Sri Lanka" selected>Sri Lanka</option></select>
                    <small style="color: #656363ff;">Service currently only available in Sri Lanka.</small>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Last Name <span class="required">*</span></label>
                <input type="text" name="last_name" value="<?php echo $user['last_name'] ?? ''; ?>" required>
            </div>
            <div class="form-group">
                <label>Email <span class="required">*</span></label>
                <input type="text" name="email" value="<?php echo $user['email'] ?? ''; ?>" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Gender <span class="required">*</span></label>
                <div>
                    <label style="display:inline;"><input type="radio" name="gender" value="Male" <?php echo (isset($user['gender']) && $user['gender'] == 'Male') ? 'checked' : ''; ?>> Male</label>
                    <label style="display:inline; margin-left: 10px;"><input type="radio" name="gender" value="Female" <?php echo (isset($user['gender']) && $user['gender'] == 'Female') ? 'checked' : ''; ?>> Female</label>
                    <label style="display:inline; margin-left: 10px;"><input type="radio" name="gender" value="Other" <?php echo (isset($user['gender']) && $user['gender'] == 'Other') ? 'checked' : ''; ?>> Other</label>
                </div>
            </div>
            <div class="form-group"><label>Postal code/zip</label><input type="text" name="postal_code"></div>
        </div>

        <div class="form-row">
            <div class="form-group"><label>Date of Birth <span class="required">*</span></label><input type="date" name="dob"></div>
            <div class="form-group">
                <label>Town <span class="required">*</span></label>
                <select name="location_id" id="city-select" required>
                    <option value="">Select Town</option>
                    <?php while($loc = $locations_result->fetch_assoc()): ?>
                        <option value="<?php echo $loc['id']; ?>" <?php echo (isset($user['location_id']) && $user['location_id'] == $loc['id']) ? 'selected' : ''; ?>>
                            <?php echo $loc['name']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Contact Number <span class="required">*</span></label>
                <input type="text" name="contact_no">
            </div>
            <div class="form-group">
                <label>User name <span class="required">*</span></label>
                <input type="text" name="username" value="<?php echo $user['username'] ?? ''; ?>" required>
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
            <div class="form-group"><label>Password <span class="required">*</span></label><input type="password" name="password" <?php echo $user_id ? '' : 'required'; ?>></div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>User Role <span class="required">*</span></label>
                <select name="role_id" required>
                    <option value="">Select Role</option>
                    <?php while($role = $roles_result->fetch_assoc()): ?>
                        <option value="<?php echo $role['id']; ?>" <?php echo (isset($user['role_id']) && $user['role_id'] == $role['id']) ? 'selected' : ''; ?>>
                            <?php echo $role['name']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group"><label>Confirm Password <span class="required">*</span></label><input type="password" name="confirm_password"></div>
        </div>

        <div class="form-row">  

        <div class="mt-20">
            <button type="submit" name="btn_action" value="add" class="btn-add">Add</button>
            <?php if($user_id > 0): ?>
                <button type="submit" name="btn_action" value="update" class="btn-update" style="margin-left: 20px;">Update</button>
            <?php endif; ?>
        </div>
    </form>
    </div>
</div>

<script src="script.js"></script>
<?php include '../layouts/footer.php'; ?>
<?php include '../layouts/layout_end.php'; ?>