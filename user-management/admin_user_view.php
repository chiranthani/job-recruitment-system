<?php 

/**
 * ADMIN USER VIEW MODULE
 * Role Mapping: 1 = Candidate, 2 = Recruiter, 3 = Admin
 */

// standard layout headers
include '../layouts/layout_start.php'; 

// 1. INPUT VALIDATION
// Capture ID from URL (e.g., admin_user_view.php?id=10) and cast to integer for security
$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$logged_user_role = $_SESSION['role_id'] ?? 0;
// If no valid ID is provided, prevent page load and redirect back to the list
if ($user_id <= 0) {
    header("Location: admin_user_list.php");
    exit();
}

/**
 * 2. DATA RETRIEVAL (The "Master Query")
 * Uses LEFT JOINs to fetch data from 4 related tables in one request.
 * - roles: gets the text name of the role.
 * - companies: gets company name if the user is a recruiter.
 * - candidates: gets professional bio/CV if the user is a candidate.
 */
$query = "SELECT u.*, r.name as role_name, c.name as company_name, 
                 cand.contact_no, cand.job_title, cand.bio, cand.cv_url, cand.country
          FROM users u 
          LEFT JOIN roles r ON u.role_id = r.id 
          LEFT JOIN companies c ON u.company_id = c.id 
          LEFT JOIN candidates cand ON u.id = cand.user_id
          WHERE u.id = $user_id";

$result = $con_main->query($query);
$user = $result->fetch_assoc();

$skills_query = "SELECT
    skills.name
FROM
    `user_skills`
   INNER JOIN skills On skills.id = user_skills.skill_id
WHERE
user_skills.user_id =$user_id";

$skills_result = $con_main->query($skills_query);

?>

<title>View Profile - <?php echo $user['username']; ?></title>
<link rel="stylesheet" href="../assets/css/user_management.css">

<style>
    /* Labels used above data fields */
    .view-label { font-weight: bold; color: #666; font-size: 0.8rem; text-transform: uppercase; margin-bottom: 4px; display: block; }
    
    /* Read-only data display boxes */
    .view-data { padding: 12px; background: #f9f9f9; border: 1px solid #eee; border-radius: 4px; margin-bottom: 15px; font-size: 1rem; color: #222; }
    
    /* Profile Image with circular mask */
    .profile-circle { width: 140px; height: 140px; border-radius: 50%; border: 5px solid #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.1); object-fit: cover; }
    
    /* Decorative headers for different profile sections */
    .section-header { border-bottom: 2px solid var(--ink-color); padding-bottom: 5px; margin: 30px 0 15px 0; font-weight: bold; color: var(--ink-color); font-size: 1.1rem; }
</style>

<?php include '../layouts/header.php'; ?>

<div class="container">
    <div class="user-card" style="max-width: 850px; margin: 40px auto; padding: 40px; background: #fff; border-radius: 10px; box-shadow: 0 8px 30px rgba(0,0,0,0.08);">
        
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 30px;">
            <div>
                <h1 style="margin:0; font-size: 1.8rem;"><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></h1>
                <p style="color: #888; margin: 5px 0;">Username: <strong>@<?php echo $user['username']; ?></strong></p>
            </div>
            <span style="background: <?php echo $user['status'] ? '#dcfce7' : '#fee2e2'; ?>; color: <?php echo $user['status'] ? '#166534' : '#991b1b'; ?>; padding: 8px 20px; border-radius: 50px; font-weight: bold; font-size: 0.85rem;">
                <?php echo $user['status'] ? 'ACTIVE ACCOUNT' : 'INACTIVE ACCOUNT'; ?>
            </span>
        </div>

        <div style="display: flex; gap: 40px;">
            <div style="flex: 0 0 160px; text-align: center;">
                <img src="../<?php echo !empty($user['profile_image']) ? $user['profile_image'] : 'assets/uploads/profile_pics/default.png'; ?>" class="profile-circle">
                <div style="margin-top: 20px; padding: 5px; background: #eee; border-radius: 4px; font-weight: bold; font-size: 0.9rem;">
                    <?php echo strtoupper($user['role_name']); ?>
                </div>
            </div>

            <div style="flex: 1;">
                
                <div class="section-header">Contact & Identity</div>
                <div style="display: flex; gap: 20px;">
                    <div style="flex: 1;">
                        <span class="view-label">Email Address</span>
                        <div class="view-data"><?php echo $user['email']; ?></div>
                    </div>
                    <div style="flex: 1;">
                        <span class="view-label">Gender</span>
                        <div class="view-data"><?php echo $user['gender']; ?></div>
                    </div>
                </div>

                <?php if($user['role_id'] == 1): ?>
                    <div class="section-header">Candidate Profile Details</div>
                    <div style="display: flex; gap: 20px;">
                        <div style="flex: 1;">
                            <span class="view-label">Contact Number</span>
                            <div class="view-data"><?php echo $user['contact_no'] ?? 'N/A'; ?></div>
                        </div>
                        <div style="flex: 1;">
                            <span class="view-label">Country</span>
                            <div class="view-data"><?php echo $user['country'] ?? 'N/A'; ?></div>
                        </div>
                    </div>

                    <span class="view-label">Professional Job Title</span>
                    <div class="view-data"><?php echo $user['job_title'] ?? 'N/A'; ?></div>

                    <span class="view-label">Personal Biography</span>
                    <div class="view-data" style="min-height: 100px; line-height: 1.6;"><?php echo nl2br($user['bio'] ?? 'No bio provided.'); ?></div>
                    
                    <!-- if logged user is recuriter, display candidate skills -->
                    <?php if($logged_user_role == 2): ?>
                    <span class="view-label">Skills</span>
                    <div>
                        <?php if ($skills_result && $skills_result->num_rows > 0): ?>
                            <?php while ($row = $skills_result->fetch_assoc()): ?>
                                <span class="skill-badge"><?= htmlspecialchars($row['name']) ?></span>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <span>No skills added</span>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if($user['role_id'] == 2): ?>
                    <div class="section-header">Corporate Assignment</div>
                    <span class="view-label">Associated Company</span>
                    <div class="view-data"><?php echo $user['company_name'] ?? 'Unassigned'; ?></div>
                <?php endif; ?>

                <?php if($user['role_id'] == 3): ?>
                    <div class="section-header">Administrator Access</div>
                    <div class="view-data" style="background: #fffbeb; border-color: #fde68a;">
                        This user is a System Administrator.
                    </div>
                <?php endif; ?>

            </div>
        </div>

        <div style="margin-top: 50px; text-align: right; border-top: 1px solid #eee; padding-top: 20px;">
            <button type="button" class="btn-update" onclick="window.history.back()" style="padding: 12px 30px; cursor: pointer;">Close View</button>
        </div>
    </div>
</div>

<?php 
// standard layout footers
include '../layouts/footer.php'; 
include '../layouts/layout_end.php'; 
?>