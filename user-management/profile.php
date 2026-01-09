<?php
/**
 * Job Seeker Profile Completion Page
 * This file handles the multi-step registration process for Candidates,
 * including personal info, professional details, skill tags, and CV uploads.
 */

include '../config/database.php';
include '../layouts/layout_start.php';

// --- 1. ACCESS CONTROL ---
// Ensure only logged-in users can access this page
$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$message = "";

// Success flag to trigger the "Welcome" popup modal
$success_trigger = false;

// --- 2. DATA PREPARATION (Dropdowns & Suggestions) ---
// Fetch active Locations for the City dropdown
$location_query = "SELECT id, name FROM locations WHERE status = 1 ORDER BY name ASC";
$locations_result = $con_main->query($location_query);

// Fetch Skill names for the autocomplete datalist
$skills_query = "SELECT id, name FROM skills WHERE status = 1";
$skills_result = $con_main->query($skills_query);
$skill_suggestions = [];
while($row = $skills_result->fetch_assoc()) {
    $skill_suggestions[] = $row;
}

// 2. THE ADD/UPDATE BUTTON LOGIC (Processing the POST request)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btn_action'])) {
    
    // Capture and sanitize inputs
    $u_id       = (int)$_POST['user_id'];
    $first_name = mysqli_real_escape_string($con_main, trim($_POST['first_name']));
    $last_name  = mysqli_real_escape_string($con_main, trim($_POST['last_name']));
    $email      = mysqli_real_escape_string($con_main, trim($_POST['email']));
    $username   = mysqli_real_escape_string($con_main, trim($_POST['username']));
    $gender     = mysqli_real_escape_string($con_main, $_POST['gender']);
    $role_id    = (int)$_POST['role_id'];
    $status     = (int)$_POST['status'];
    $password   = $_POST['password'];

    if ($u_id > 0) {
        // --- UPDATE LOGIC ---
        $pass_sql = "";
        if (!empty($password)) {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $pass_sql = ", password = '$hashed'";
        }

        $query = "UPDATE users SET 
                  first_name = '$first_name', last_name = '$last_name', 
                  email = '$email', username = '$username', 
                  gender = '$gender', role_id = '$role_id', status = '$status' 
                  $pass_sql 
                  WHERE id = $u_id";
    } else {
        // --- ADD LOGIC ---
        // Check if email already exists
        $check = $con_main->query("SELECT id FROM users WHERE email = '$email'");
        if ($check->num_rows > 0) {
            $message = "Error: Email is already registered!";
            $query = false;
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO users (first_name, last_name, email, username, password, gender, role_id, status) 
                      VALUES ('$first_name', '$last_name', '$email', '$username', '$hashed', '$gender', '$role_id', '$status')";
        }
    }

    if ($query && $con_main->query($query)) {
        $success_trigger = true;
        // Optionally redirect to clear POST data
        header("Location: admin_user_list.php?success=1");
        exit();
    } elseif ($message == "") {
        $message = "Database Error: " . $con_main->error;
    }
}

// 3. Fetch data for display (Edit Mode)
$user = [];
if ($user_id > 0) {
    $user = $con_main->query("SELECT * FROM users WHERE id = $user_id")->fetch_assoc();
}

// Fetch Roles & Locations for dropdowns
$roles_result = $con_main->query("SELECT id, name FROM roles WHERE status = 1");
$locations_result = $con_main->query("SELECT id, name FROM locations WHERE status = 1");
?>

<title>Job Seeker Profile</title>
<link rel="stylesheet" href="style.css">

<style>
    /* Hide the professional info tab content initially */
    #step2-content { display: none; }
</style>

<?php include '../layouts/header.php'; ?>

<div class="container" style="max-width: 650px;">
    <div class="user-card">
    <h1>Job Seeker Profile</h1>

    <div class="tabs">
        <a href="javascript:void(0)" class="tab-link active" id="tab1" onclick="navigateToStep(1)">About You</a>
        <a href="javascript:void(0)" class="tab-link" id="tab2" onclick="navigateToStep(2)">Professional Info</a>
    </div>

    <form id="multiStepProfileForm" method="POST" enctype="multipart/form-data">
        
        <div id="step1-content">
            <p>Fill in the following information so companies know who you are...</p>
            
            <div class="form-row">
                <div class="form-group">
                    <label>First Name <span class="required">*</span></label>
                    <input type="text" name="first_name" required>
                </div>
                <div class="form-group">
                    <label>Last Name <span class="required">*</span></label>
                    <input type="text" name="last_name" required>
                </div>
                <div class="form-group">
                    <label>Contact No</label>
                    <input type="text" name="contact_no">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Country <span class="required">*</span></label>
                    <select name="country" id="country" required>
                        <option value="Sri Lanka" selected>Sri Lanka</option>
                    </select>
                    <small style="color: #656363ff;">Service currently only available in Sri Lanka.</small>
                </div>
                <div class="form-group">
                    <label>City <span class="required">*</span></label>
                    <select name="location_id" id="location_id" required>
                        <option value="">Select City</option>
                        <?php while($loc = $locations_result->fetch_assoc()): ?>
                            <option value="<?php echo $loc['id']; ?>"><?php echo $loc['name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Postal Code</label>
                    <input type="text" name="postal_code">
                </div>
            </div>

            <div class="mt-20">
                <button type="button" class="btn-add" onclick="navigateToStep(2)">Next</button>
            </div>
        </div>

        <div id="step2-content">
            <div class="form-group">
                <label>Job Title <span class="required">*</span></label>
                <input type="text" name="job_title" placeholder="e.g. Software Engineer">
            </div>

            <div class="form-group">
                <label>Brief Bio <span class="required">*</span></label>
                <textarea name="brief_bio" placeholder="Describe your experience and goals..."></textarea>
            </div>

            <div class="form-group">
                <label>Key Skills</label>
                <div class="tag-container" id="tagsContainer">
                    <input type="text" class="tag-input" id="skillsInput" list="skill-options" placeholder="Type skill and press Enter">
                    <datalist id="skill-options">
                        <?php foreach ($skill_suggestions as $skill): ?>
                            <option value="<?= htmlspecialchars($skill['name']) ?>" data-id="<?= $skill['id'] ?>">
                        <?php endforeach; ?>
                    </datalist>
                </div>
                <input type="hidden" id="hiddenSkillsInput" name="skills">
            </div>  

            <div class="form-group" style="border: 2px solid var(--ink-color); padding: 15px; border-radius: 10px; background: #fff;">
                <label>Resume Upload</label>
                <input type="file" name="resume" accept=".pdf,.doc,.docx">
                <small style="display:block; margin-top:5px; color:#666;">Accepted formats: PDF, DOC, DOCX</small>
            </div>

            <div class="d-flex justify-between mt-20">
                <button type="button" class="btn-update" onclick="navigateToStep(1)">Back</button>
                <button type="submit" class="btn-add">Finish Registration</button>
            </div>
        </div>
    </form>
    </div>
</div>

<div id="successModal" class="modal-overlay" style="<?= $success_trigger ? 'display:flex' : 'display:none' ?>">
    <div class="modal-content">
        <div class="checkmark-circle">✓</div>
        <h2>Welcome to CareerBridge!</h2>
        <p>Your profile has been created successfully. We're excited to help you find your next opportunity!</p>
        
        <div class="form-row mt-20" style="justify-content: center; gap: 10px;">
            <button class="btn-add" onclick="window.location.href='profile.php'">Review Profile</button>
            <button class="btn-add" onclick="window.location.href='../job-applicant/job-search.php'">Start Browsing Jobs</button>
        </div>
    </div>
</div>

<script src="script.js"></script>

<?php include '../layouts/footer.php'; ?>
<?php include '../layouts/layout_end.php'; ?>