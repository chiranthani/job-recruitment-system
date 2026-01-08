<?php
include '../config/database.php';
include '../layouts/layout_start.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: signup.php");
    exit();
}
$success_trigger = false;

// 1. Fetch Locations (Cities) from database
$location_query = "SELECT id, name FROM locations WHERE status = 1 ORDER BY name ASC";
$locations_result = $con_main->query($location_query);

// 2. Fetch Skill Suggestions from database
$skills_query = "SELECT id,name FROM skills WHERE status = 1";
$skills_result = $con_main->query($skills_query);
$skill_suggestions = [];
while($row = $skills_result->fetch_assoc()) {
    $skill_suggestions[] = $row;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
// Update 'candidates' table
    $contact = trim($_POST['contact_no']);
    $loc_id = (int)$_POST['location_id'];
    $p_code = (int)$_POST['postal_code'];
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $country = trim($_POST['country']);
    $job = trim($_POST['job_title']);
    $bio = trim($_POST['brief_bio']);
    $user_id = $_SESSION['user_id'];
    $cv_url = '';

    // cv upload
    if (!empty($_FILES['resume']['name'])) {

            $fileName = time() . "_" . basename($_FILES['resume']['name']);
            $target = "../assets/uploads/resumes/" . $fileName;

            if (!move_uploaded_file($_FILES['resume']['tmp_name'], $target)) {
                $messge = "Failed to upload resume";
            }

            $cv_url = "assets/uploads/resumes/" . $fileName;
    }

    // check candidate availability
        $check_sql = "SELECT id FROM candidates WHERE user_id = '$user_id' LIMIT 1";
        $result = mysqli_query($con_main, $check_sql);
     
        // if already have candidate need to be update, otherwise insert
        if (mysqli_num_rows($result) > 0) {
            $query = "UPDATE candidates SET 
            contact_no = '$contact',location_id = '$loc_id',postal_code = '$p_code',job_title = '$job',bio  = '$bio',cv_url = '$cv_url',status = 1
                WHERE user_id = '$user_id'";
        } else {
            $query = "INSERT INTO candidates (user_id,contact_no,country,location_id,postal_code,job_title,bio,cv_url,status)
                VALUES ('$user_id','$contact','$country','$loc_id','$p_code','$job','$bio','$cv_url',1)";
        }
       
        if ($con_main->query($query)) {
            // update user name
            $user_query = "UPDATE users SET first_name = '$first_name',last_name = '$last_name', last_login = NOW(), login_count = login_count + 1 WHERE id = '$user_id'";
            mysqli_query($con_main, $user_query);

            // fetch updated user info
            $user_result = mysqli_query($con_main, "SELECT username, role_id FROM users WHERE id = '$user_id' LIMIT 1");

            if ($row = mysqli_fetch_assoc($user_result)) {
                $_SESSION['username'] = $row['username'];
                $_SESSION['role_id']  = $row['role_id'];

                $success_trigger = true;
            }

            // add skills
            if ($success_trigger && !empty($_POST['skills'])) {

                $skills = explode(',', $_POST['skills']);

                // remove old skills (if avaible)
                mysqli_query($con_main, "DELETE FROM user_skills WHERE user_id = '$user_id'");

                foreach ($skills as $skill) {
                    $skill = mysqli_real_escape_string($con_main, trim($skill));
                    mysqli_query(
                        $con_main,"INSERT INTO user_skills (user_id, skill_id) VALUES ('$user_id', '$skill')"
                    );
                }
            }
        }
    }
?>

<title>Job Seeker Profile</title>
<link rel="stylesheet" href="style.css">

<style>
    /* Extra style to ensure Step 2 is hidden initially */
    #step2-content {
        display: none;
    }
</style>

<?php include '../layouts/header.php'; ?>

<div class="container">
        <h1>Job Seeker Profile</h1>

        <div class="tabs">
            <a href="javascript:void(0)" class="tab-link active" onclick="navigateToStep(1)">About You</a>
            <a href="javascript:void(0)" class="tab-link" onclick="navigateToStep(2)">Professional Info</a>
        </div>

        <form id="multiStepProfileForm" method="POST" enctype="multipart/form-data">
            
            <div id="step1-content">
                <p>Fill in the following information so companies know who you are and what you are looking for...</p>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>First Name <span class="required">*</span></label>
                        <input type="text" name="first_name">
                    </div>
                    <div class="form-group">
                        <label>Last Name <span class="required">*</span></label>
                        <input type="text" name="last_name">
                    </div>
                    <div class="form-group">
                        <label>Contact No</label>
                        <input type="text" name="contact_no">
                    </div>
                </div>

                    <div class="form-row">
                        <div class="form-group">
                        <label>Country <span class="required">*</span></label>
                        <select name="country" id="country" onchange="updateRegions()" required>
                        <option value="Sri Lanka" selected>Sri Lanka</option>
                        </select>
                        <small style="color: #656363ff;">Service currently only available in Sri Lanka.</small>
                    </div>
                    <div class="form-group">
                        <label>City<span class="required">*</span></label>
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
                    <input type="text" name="job_title">
                </div>

                <div class="form-group">
                    <label>Brief Bio <span class="required">*</span></label>
                    <textarea name="brief_bio"></textarea>
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
                    <button type="submit" class="btn-add" >Finish Registration</button>
                </div>
            </div>

        </form>
    </div>

    <div id="successModal" class="modal-overlay" style="<?= $success_trigger ? 'display:flex' : 'display:none' ?>">
        <div class="modal-content">
            <div class="checkmark-circle">✓</div>
            <h2>Welcome to CareerBridge....!</h2>
            <p>Your account has successfully Created we're excited to help find your next opportunity...!</p>
            
            <div class="form-row mt-20" style="justify-content: center; gap: 10px;">
            <button class="btn-add" onclick="window.location.href='profile.php'">Complete your Profile</button>
            <button class="btn-add" onclick="window.location.href='../job-applicant/job-search.php'">Start Browsing Jobs</button>
            </div>
        </div>
    </div>

<script src="script.js"></script>

<?php include '../layouts/footer.php'; ?>
<?php include '../layouts/layout_end.php'; ?>