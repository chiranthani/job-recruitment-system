<!-- start page common elements -->
<?php include '../config/database.php'; ?>
<?php include '../layouts/layout_start.php'; ?>
<link rel="stylesheet" href="../assets/css/main.css">
<?php include '../layouts/header.php'; ?>
<!-- end page common elements-->

<?php
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $company_name = trim($_POST['company_name']);
    $registration_no = trim($_POST['registration_no']);
    
    // Validation
    if (empty($email) || empty($username) || empty($password) || empty($company_name) || empty($registration_no)) {
        $error = 'All required fields must be filled';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } else {
        // Check if username or email exists
        $check = "SELECT id FROM users WHERE username = ? OR email = ?";
        $stmt = $con_main->prepare($check);
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $error = 'Username or email already exists';
        } else {
            // Check if registration number exists
            $check_reg = "SELECT id FROM companies WHERE registration_no = ?";
            $stmt_reg = $con_main->prepare($check_reg);
            $stmt_reg->bind_param("s", $registration_no);
            $stmt_reg->execute();
            if ($stmt_reg->get_result()->num_rows > 0) {
                $error = 'Company registration number already exists';
            } else {
                // Start transaction
                $con_main->begin_transaction();
                
                try {
                    // Insert company
                    $insert_company = "INSERT INTO companies (name, registration_no, admin_approval, createdAt) VALUES (?, ?, 'PENDING', NOW())";
                    $stmt_company = $con_main->prepare($insert_company);
                    $stmt_company->bind_param("ss", $company_name, $registration_no);
                    $stmt_company->execute();
                    $company_id = $con_main->insert_id;
                    
                    // Insert user
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $role_id = 2; // Recruiter
                    $insert_user = "INSERT INTO users (email, username, password, role_id, first_name, last_name, company_id, createdAt) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
                    $stmt_user = $con_main->prepare($insert_user);
                    $stmt_user->bind_param("ssssssi", $email, $username, $hashed_password, $role_id, $first_name, $last_name, $company_id);
                    $stmt_user->execute();
                    
                    $con_main->commit();
                    $message = 'Registration successful! Your account is pending admin approval.';
                } catch (Exception $e) {
                    $con_main->rollback();
                    $error = 'Registration failed. Please try again.';
                }
            }
        }
    }
}
?>

<body class="register-body">
<div class="register-container">
    <div class="register-card">
        <h2>Employer Registration</h2>
        
        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>Company Name *</label>
                <input type="text" name="company_name" required>
            </div>

            <div class="form-group">
                <label>Company Registration Number *</label>
                <input type="text" name="registration_no" required>
            </div>

            <div class="form-group">
                <label>First Name</label>
                <input type="text" name="first_name">
            </div>

            <div class="form-group">
                <label>Last Name</label>
                <input type="text" name="last_name">
            </div>

            <div class="form-group">
                <label>Email *</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-group">
                <label>Username *</label>
                <input type="text" name="username" required>
            </div>

            <div class="form-group">
                <label>Password *</label>
                <input type="password" name="password" required>
            </div>

            <div class="form-group">
                <label>Confirm Password *</label>
                <input type="password" name="confirm_password" required>
            </div>

            <button type="submit" class="btn btn-primary">Register</button>
        </form>

        <div class="text-center">
            <p>Already have an account? <a href="../login.php">Login here</a></p>
        </div>
    </div>
</div>

<?php include '../layouts/layout_end.php'; ?>
