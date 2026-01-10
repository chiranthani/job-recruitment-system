<?php
include '../config/database.php';
include '../layouts/layout_start.php';

// fetch Roles and Locations
$roles_result = $con_main->query("SELECT id, name FROM roles WHERE status = 1 ORDER BY id ASC");
$locations_result = $con_main->query("SELECT id, name FROM locations WHERE status = 1 ORDER BY name ASC");

$message = '';
$error = '';

// --- FORM PROCESSING (POST REQUEST) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitize and capture inputs for the users table
    $first_name = mysqli_real_escape_string($con_main, trim($_POST['first_name']));
    $last_name  = mysqli_real_escape_string($con_main, trim($_POST['last_name']));
    $gender     = mysqli_real_escape_string($con_main, $_POST['gender']) ?? 0;
    $email      = mysqli_real_escape_string($con_main, trim($_POST['email']));
    $username   = mysqli_real_escape_string($con_main, trim($_POST['username']));
    $role_id    = (int)$_POST['role_id'];
    $password   = $_POST['password'];

    if ($role_id == 2) {
        $company_name   = trim($_POST['company_name'] ?? '');
        $company_reg_no = trim($_POST['company_reg_no'] ?? '');

        if ($company_name === '' || $company_reg_no === '') {
            $error = "Company name and registration number are required.";
            $query = false;
        }
    }

    // --- IMAGE UPLOAD LOGIC ---
    $profile_img_url = 'assets/images/default_profile_image.png';
    if (!empty($_FILES['profile_image']['name'])) {
        $fileName = time() . "_" . basename($_FILES['profile_image']['name']);
        $target = "../assets/uploads/profile_pics/" . $fileName;

        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target)) {
            $profile_img_url = "assets/uploads/profile_pics/" . $fileName;
        }
    }

    // --- ADD LOGIC ---
    // 1. Check if email already exists before adding
    $check_username = mysqli_query($con_main, "SELECT id FROM users WHERE username = '$username' OR email = '$email' LIMIT 1");

    if (mysqli_num_rows($check_username) > 0) {
        $error = "Error: This email/username is already registered.";
        $query = false;
    } else {

        $company_id = NULL;
        $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
        if ($role_id == 2) {
            // check exsiting company avaible or not
            $getCompany = $con_main->query("SELECT id FROM companies WHERE registration_no = '$company_reg_no' LIMIT 1 ");
            if($getCompany->num_rows > 0){
                $company_id = $getCompany['id'];
            }else{
                $companyQuery = "INSERT INTO companies (name, registration_no, admin_approval)
                VALUES ('$company_name','$company_reg_no', 'APPROVED')";

                if ($con_main->query($companyQuery) == true) {
                    $company_id = $con_main->insert_id;
                    $query = "INSERT INTO users (first_name, last_name, gender, email, username, password, role_id, profile_image,company_id)
                        VALUES ('$first_name', '$last_name', '$gender','$email', '$username', '$hashed_pass', '$role_id', '$profile_img_url','$company_id')";
                } else {
                    $error = "Failed to create company.";
                    $query = false;
                }
            }
            
        } else {
            $query = "INSERT INTO users (first_name, last_name, gender, email, username, password, role_id, profile_image)
                      VALUES ('$first_name', '$last_name', '$gender','$email', '$username', '$hashed_pass', '$role_id', '$profile_img_url')";
        }
    }


    // --- 3c. EXECUTION ---
    if ($query && $con_main->query($query)) {

        $user_id = $con_main->insert_id;
        // candidate 
        if ($role_id == 1) {
            $candidateQuery = "INSERT INTO candidates (user_id) VALUES ('$user_id')";
            if ($con_main->query($candidateQuery) == true) {
                $message = "User created successfully!";
            } else {
                $error = "Failed to create candidate.";
            }
        }
        $message = "User created successfully!";
    } else {
        $error = "User creation failed.";
    }
}


?>

<title>New User Creation</title>
<link rel="stylesheet" href="style.css">

<?php include '../layouts/header.php'; ?>

<div class="container" style="position: relative;">
    <div class="user-card">
        <h1>New User Creation</h1>

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

            <div style="position: absolute; top: 30px; right: 55px; text-align: center; z-index: 10;">
                <div id="image-preview" style="width: 100px; height: 100px; border: 3px solid var(--ink-color); border-radius: 50%; margin: 0 auto 10px; display: flex; justify-content: center; align-items: center; font-size: 40px; overflow: hidden; background-color: #f9f9f9;">
                    <span id="placeholder-icon">👤</span>
                    <img id="user-img" src="" style="display: none;width: 100%; height: 100%; object-fit: cover;">
                </div>
                <input type="file" id="user-photo-input" name="profile_image" accept="image/*" style="display: none;" onchange="previewUserImage(this)">
                <div class="admin-actions" style="display: flex; flex-direction: row; gap: 5px;">
                    <button type="button" class="btn btn-add" style="padding: 5px 10px; font-size: 12px;" onclick="document.getElementById('user-photo-input').click();">Add/Change</button>
                    <button type="button" class="btn btn-delete" style="padding: 5px 10px; font-size: 12px;" onclick="removeUserImage()">Remove</button>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>User Role <span class="required">*</span></label>
                    <select name="role_id" id="roleSelect" required>
                        <option value="">Select Role</option>
                        <?php while ($role = $roles_result->fetch_assoc()): ?>
                            <option value="<?php echo $role['id']; ?>" <?php echo (isset($user['role_id']) && $user['role_id'] == $role['id']) ? 'selected' : ''; ?>>
                                <?php echo $role['name']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Email <span class="required">*</span></label>
                    <input type="text" name="email" value="" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>First Name <span class="required">*</span></label>
                    <input type="text" name="first_name" value="" required>
                </div>
                <div class="form-group">
                    <label>Last Name <span class="required">*</span></label>
                    <input type="text" name="last_name" value="" required>
                </div>

            </div>

            <div class="form-row">

                <div class="form-group">
                    <label>User name <span class="required">*</span></label>
                    <input type="text" name="username" value="" required>
                </div>
                <div class="form-group">
                    <label>Password <span class="required">*</span></label><input type="password" name="password">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Gender <span class="required">*</span></label>
                    <div>
                        <label style="display:inline;"><input type="radio" name="gender" value="Male" required> Male</label>
                        <label style="display:inline; margin-left: 10px;"><input type="radio" name="gender" value="Female"> Female</label>
                        <label style="display:inline; margin-left: 10px;"><input type="radio" name="gender" value="Other"> Other</label>
                    </div>
                </div>
                <div class="form-group">
                    <label>Confirm Password <span class="required">*</span></label><input type="password" name="confirm_password">
                </div>
            </div>

            <!-- Employer Only Fields -->
            <div id="employerFields" style="display:none;">

                <div class="form-row">
                    <div class="form-group">
                        <label>Company Register Number <span class="required">*</span></label>
                        <input type="text" name="company_reg_no">
                    </div>

                    <div class="form-group">
                        <label>Company Name <span class="required">*</span></label>
                        <input type="text" name="company_name">
                    </div>
                </div>

            </div>

            <div class="form-row">

                <div class="mt-20">
                    <div class="mt-20 d-flex" style="gap: 10px;">

                        <button type="button" class="btn-delete" onclick="clearForm()">Clear Form</button>
                        <button type="submit" class="btn-add">Create</button>
                    </div>

                </div>
        </form>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {

        const roleSelect = document.getElementById("roleSelect");
        const employerFields = document.getElementById("employerFields");

        roleSelect.addEventListener("change", function() {
            const roleId = this.value;

            // Employer role_id = 2
            if (roleId === "2") {
                employerFields.style.display = "block";
                setEmployerRequired(true);
            } else {
                employerFields.style.display = "none";
                setEmployerRequired(false);
            }
        });

        function setEmployerRequired(isRequired) {
            employerFields.querySelectorAll("input").forEach(input => {
                input.required = isRequired;
            });
        }

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

<script src="script.js"></script>
<?php include '../layouts/footer.php'; ?>
<?php include '../layouts/layout_end.php'; ?>