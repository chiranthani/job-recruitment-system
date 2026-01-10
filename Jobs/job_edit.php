<?php
include '../config/database.php';
include '../layouts/layout_start.php';
include '../layouts/header.php';

/* =========================
   GET JOB ID
========================= */
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "Invalid job post.";
    header("Location: job_list.php");
    exit;
}

$job_id = (int) $_GET['id'];

/* =========================
   FETCH JOB DETAILS
========================= */
$sql = "SELECT * FROM job_posts WHERE id = ?";
$stmt = $con_main->prepare($sql);
$stmt->bind_param("i", $job_id);
$stmt->execute();
$job = $stmt->get_result()->fetch_assoc();

if (!$job) {
    $_SESSION['error'] = "Job not found.";
    header("Location: job_list.php");
    exit;
}

/* =========================
   FETCH SELECTED BENEFITS
========================= */
$selectedBenefits = [];
$benefitSql = "SELECT benefit_id FROM job_post_benefits WHERE job_post_id = ?";
$benefitStmt = $con_main->prepare($benefitSql);
$benefitStmt->bind_param("i", $job_id);
$benefitStmt->execute();
$benefitResult = $benefitStmt->get_result();

while ($row = $benefitResult->fetch_assoc()) {
    $selectedBenefits[] = $row['benefit_id'];
}
?>

<link rel="stylesheet" href="job-post.css">

<section class="job-wrapper">

<?php if (isset($_SESSION['error'])) { ?>
    <div class="alert error">
        <?= $_SESSION['error']; ?>
    </div>
<?php unset($_SESSION['error']); } ?>

<h2>Job Post Edit - <?= htmlspecialchars($job['title']); ?></h2>

<form method="POST" action="update_job.php" class="job-form">

<input type="hidden" name="job_id" value="<?= $job_id; ?>">

<!-- Job Status + Deadline -->
<div class="form-row-2col">

    <div>
        <label>* Job Status:</label>
        <select name="job_status" class="input-box" required>
            <option value="draft" <?= ($job['post_status']=='draft')?'selected':''; ?>>Draft</option>
            <option value="published" <?= ($job['post_status']=='published')?'selected':''; ?>>Published</option>
        </select>
    </div>

    <div>
        <label>Application Deadline:</label>
        <input type="date" name="deadline" class="input-box"
               value="<?= $job['expiry_date']; ?>">
    </div>

</div>

<!-- Job Title -->
<div class="form-row">
    <label>* Job Title:</label>
    <input type="text" name="job_title" class="input-box-full" required
           value="<?= htmlspecialchars($job['title']); ?>">
</div>

<!-- Category -->
<div class="form-row">
    <label>* Category:</label>
    <select name="category" class="input-box-full" required>
        <option disabled>Select Category</option>
        <?php
        $catQ = "SELECT * FROM job_categories WHERE status=1";
        $catR = $con_main->query($catQ);
        while ($cat = $catR->fetch_assoc()) {
        ?>
            <option value="<?= $cat['id']; ?>"
                <?= ($job['category_id']==$cat['id'])?'selected':''; ?>>
                <?= $cat['name']; ?>
            </option>
        <?php } ?>
    </select>
</div>

<!-- Job Type + Work Type -->
<div class="form-row-2col">

    <div>
        <label>* Job Type:</label>
        <select name="job_type" class="input-box" required>
            <?php
            $types = ['Full-Time','Part-Time','Internship','Contract','Freelance'];
            foreach ($types as $type) {
            ?>
                <option value="<?= $type; ?>"
                    <?= ($job['job_type']==$type)?'selected':''; ?>>
                    <?= $type; ?>
                </option>
            <?php } ?>
        </select>
    </div>

    <div>
        <label>* Work Type:</label>
        <select name="work_type" class="input-box" required>
            <?php
            $workTypes = ['On-site','Remote','Hybrid'];
            foreach ($workTypes as $wt) {
            ?>
                <option value="<?= $wt; ?>"
                    <?= ($job['work_type']==$wt)?'selected':''; ?>>
                    <?= $wt; ?>
                </option>
            <?php } ?>
        </select>
    </div>

</div>

<!-- Description -->
<div class="form-row">
    <label>* Description:</label>
    <textarea name="description" class="textarea" required><?= htmlspecialchars($job['description']); ?></textarea>
</div>

<!-- Requirements -->
<div class="form-row">
    <label>* Requirements:</label>
    <textarea name="requirements" class="textarea" required><?= htmlspecialchars($job['requirements']); ?></textarea>
</div>

<!-- Benefits -->
<div class="form-row">
    <label>* Benefits:</label>
    <div class="checkbox-group">
        <?php
        $benQ = "SELECT * FROM benefits";
        $benR = $con_main->query($benQ);
        while ($b = $benR->fetch_assoc()) {
        ?>
            <span class="benefits-text">
                <input type="checkbox"
                       name="benefits[]"
                       class="benefits-checkbox"
                       value="<?= $b['id']; ?>"
                       <?= in_array($b['id'],$selectedBenefits)?'checked':''; ?>>
                <?= $b['name']; ?>
            </span>
        <?php } ?>
    </div>
</div>

<!-- Location -->
<div class="form-row">
    <label>* Job Location:</label>
    <select name="location_id" class="input-box" required>
        <?php
        $locQ = "SELECT * FROM locations WHERE status=1";
        $locR = $con_main->query($locQ);
        while ($loc = $locR->fetch_assoc()) {
        ?>
            <option value="<?= $loc['id']; ?>"
                <?= ($job['location_id']==$loc['id'])?'selected':''; ?>>
                <?= $loc['name']; ?>
            </option>
        <?php } ?>
    </select>
</div>

<!-- Buttons -->
<div class="form-actions">
    <button type="submit" class="save-btn">Update</button>
    <button type="button" class="close-btn" onclick="history.back()">Cancel</button>
</div>

</form>
</section>

<?php
include '../layouts/footer.php';
include '../layouts/layout_end.php';
?>
