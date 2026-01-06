<?php 
// 1. Start the session to "remember" the user across pages

include '../config/database.php'; 
include '../layouts/layout_start.php';
$message = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $email = mysqli_real_escape_string($con_main, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Basic Validation
    if ($password !== $confirm_password) {
        $message = "Passwords do not match!";
    } else {
        // Check if user already exists
        $check = $con_main->query("SELECT id FROM users WHERE email = '$email'");
        if ($check->num_rows > 0) {
            $message = "Email already registered!";
        } else {
            // Hash password for security
            $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
            $role_id = 1; // 1 = Candidate (Job Seeker)
            $status = 1;  // 1 = Active

            // Insert into database
            // We use email as the default username for now
            $sql = "INSERT INTO users (email, username, password, role_id, status) 
                    VALUES ('$email', '$email', '$hashed_pass', '$role_id', '$status')";

            if ($con_main->query($sql) === TRUE) {
                // SUCCESS: Save the new User ID to the session
                $_SESSION['user_id'] = $con_main->insert_id;
                
                // REDIRECT: This is what loads profile.php
                header("Location: ../login.php");
                exit(); 
            } else {
                $message = "Database Error: " . $con_main->error;
            }
        }
    }
}
?>


<link rel="stylesheet" href="style.css">
<script src="script.js"></script>
<?php include '../layouts/header.php'; ?>
<div class="container">
    <h1>Let's Get Started</h1>
    
    <?php if ($message != ""): ?>
        <div style="color: red; border: 1px solid red; padding: 10px; margin-bottom: 15px; text-align: center;">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>
    
    <form action="signup.php" method="POST">
        <div class="form-group">
            <label for="email">Email Address <span class="required">*</span></label>
            <input type="email" id="email" name="email" placeholder="example@gmail.com" required>
        </div>

        <div class="form-group">
            <label for="password">Password <span class="required">*</span></label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirm Password <span class="required">*</span></label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>

        <div class="form-group">
            <label style="display: flex; align-items: center;">
                <input type="checkbox" required style="width: auto; margin-right: 15px;">
                I Agree to the Terms and Conditions
            </label>
        </div>

        <div class="text-center mt-20">
            <button type="submit" class="btn-add">Create Profile</button>
        </div>

        <p class="text-center mt-20" style="color: #f81818ff; font-weight:bold ">
            Already have an account? <a href="../login.php" style="color: #0300b2ff; font-weight: bold;">Sign in</a>
        </p>
    </form>
</div>

<script src="script.js"></script>

<?php include '../layouts/footer.php'; ?>
<?php include '../layouts/layout_end.php'; ?>