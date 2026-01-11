<?php 
/**
 * User Registration (Signup) Page
 * This file handles the creation of new candidate accounts.
 */

// Include database connection and layout initialization
include '../config/database.php'; 
include '../layouts/layout_start.php';

// Initialize message variable for error reporting
$message = ""; 

// --- 1. HANDLE FORM SUBMISSION ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Sanitize user inputs to prevent basic SQL Injection
    $email = mysqli_real_escape_string($con_main, $_POST['email']);
    $username = mysqli_real_escape_string($con_main, $_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // --- 2. VALIDATION ---
    // Ensure the user typed the same password twice
    if ($password !== $confirm_password) {
        $message = "Passwords do not match!";
    } else {
        // Check if the username is already taken in the database
        $check = $con_main->query("SELECT id FROM users WHERE username = '$username'");
        
        if ($check->num_rows > 0) {
            $message = "Username already registered!";
        } else {
            // --- 3. SECURITY & PREPARATION ---
            // Hash the password using the current standard (bcrypt)
            // Never store plain-text passwords in the database!
            $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
            
            // Set default values for new candidates
            $role_id = 1; // 1 = Candidate (Job Seeker)
            $status = 1;  // 1 = Active account by default

            // --- 4. DATABASE INSERTION ---
            $sql = "INSERT INTO users (email, username, password, role_id, status) 
                    VALUES ('$email', '$username', '$hashed_pass', '$role_id', '$status')";

            if ($con_main->query($sql) === TRUE) {
                // --- 5. SUCCESS HANDLING ---
                // Store the newly created User ID in a session variable
                // This "logs in" the user immediately
                $_SESSION['user_id'] = $con_main->insert_id;

                // Redirect the user to their profile setup page
                header("Location: profile.php");
                exit(); 
            } else {
                // Handle database errors (e.g., connection lost)
                $message = "Database Error: " . $con_main->error;
            }
        }
    }
}
?>

<link rel="stylesheet" href="../assets/css/user_management.css">

<?php include '../layouts/header.php'; ?>

<div class="container" style="max-width: 600px;">
    <div class="user-card">
    <h1>Let's Get Started</h1>
    
    <?php if ($message != ""): ?>
        <div style="color: red; border: 1px solid red; padding: 10px; margin-bottom: 15px; text-align: center; border-radius: 5px; background-color: #fffafa;">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>
    
    <form action="signup.php" method="POST" style="margin-top: 30px;">
        <div class="form-group">
            <label for="email">Email Address <span class="required">*</span></label>
            <input type="email" id="email" name="email" placeholder="example@gmail.com" required>
        </div>

        <div class="form-group">
            <label for="username">Username <span class="required">*</span></label>
            <input type="text" id="username" name="username" placeholder="Choose a unique username" required>
        </div>

        <div class="form-group">
            <label for="password">Password <span class="required">*</span></label>
            <input type="password" id="password" name="password" placeholder="At least 8 characters" required>
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirm Password <span class="required">*</span></label>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Re-type your password" required>
        </div>

        <div class="form-group">
            <label style="display: flex; align-items: center; cursor: pointer;">
                <input type="checkbox" required style="width: auto; margin-right: 15px;">
                <span>I agree to the <a href="../terms.php" style="text-decoration:none" target="_blank">Terms & Conditions</a></span>
            </label>
        </div>

        <div class="text-center mt-20">
            <button type="submit" class="btn-add" style="width: 100%; padding: 12px;">Create Profile</button>
        </div>

        <p class="text-center mt-20" style="color: #333;">
            Already have an account? <a href="../login.php" style="color: #0300b2ff; font-weight: bold;">Sign in</a>
        </p>
    </form>
    </div>
</div>

<script src="script.js"></script>

<?php include '../layouts/footer.php'; ?>
<?php include '../layouts/layout_end.php'; ?>