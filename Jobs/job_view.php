<!-- start page common elements -->
<?php
include '../layouts/layout_start.php';
include '../layouts/header.php';

/* ==========================
   GET JOB ID
============================ */


$job_id = (int) $_GET['job'] ?? 0;
$userRole = $_SESSION['role_id'] ?? 0;
$userId = $_SESSION['user_id'] ?? 0;
/* =========================
   FETCH JOB DETAILS
============================ */

$sql = "SELECT 
    j.*,
    c.name AS category_name,
    l.name AS location_name,
    comp.name AS company_name,
    DATEDIFF(j.expiry_date, CURDATE()) AS days_left
FROM job_posts j
LEFT JOIN job_categories c ON j.category_id = c.id
LEFT JOIN locations l ON j.location_id = l.id
LEFT JOIN companies comp ON j.company_id = comp.id
WHERE j.id = ?
";

$stmt = $con_main->prepare($sql);
$stmt->bind_param("i", $job_id);
$stmt->execute();
$job = $stmt->get_result()->fetch_assoc();


/* =========================
   FETCH BENEFITS
========================= */

$benefits = [];
$benSql = "SELECT b.name 
FROM job_post_benefits jpb
JOIN benefits b ON jpb.benefit_id = b.id
WHERE jpb.job_post_id = ?
";
$benStmt = $con_main->prepare($benSql);
$benStmt->bind_param("i", $job_id);
$benStmt->execute();
$benRes = $benStmt->get_result();

while ($b = $benRes->fetch_assoc()) {
    $benefits[] = $b['name'];
}
?>
<!-- end page common elements -->

<link rel="stylesheet" href="../assets/css/job-post.css">

<section class="page-wrapper job-view-container">
<a onclick="window.history.back()" class="back-link">← Go Back</a>
    <div class="job-header">
        <div>
            <div class="job-title"><?= htmlspecialchars($job['title']); ?></div>
            <div class="job-company"><?= htmlspecialchars($job['company_name']); ?></div>

            <div class="job-meta">
                <div class="meta-item">📍 <?= htmlspecialchars($job['location_name']); ?></div>
                <div class="meta-item">🧰 <?= htmlspecialchars($job['job_type']); ?></div>
                  <div class="meta-item">📌 <?= htmlspecialchars($job['work_type']); ?></div>
            </div>
        </div>

        <div style="text-align:right; color:#005ec4;">
            ⏰ <?= max(0, (int)$job['days_left']); ?> days left
        </div>
    </div>

    <!-- Description -->
    <div class="section-title">Description:</div>
    <div class="job-description">
        <?= nl2br(htmlspecialchars($job['description'])); ?>
    </div>

    <!-- Requirements -->
    <div class="section-title">Requirements</div>
    <div class="job-requirements">
        <?= nl2br(htmlspecialchars($job['requirements'])); ?>
    </div>

    <!-- Benefits -->
    <div class="section-title">Benefits:</div>
    <div class="job-benefits">
        <?php if (!empty($benefits)) {
            foreach ($benefits as $ben) {
                echo "• " . htmlspecialchars($ben) . "<br>";
            }
        } else {
            echo "No benefits specified.";
        } ?>
    </div>

    <?php if ($userRole == 1): ?>
        <!-- Apply Section -->
        <!-- check applied or not -->
        <?php

        $sql = "SELECT
                COUNT(*) AS total
            FROM
                `candidate_jobs`
            WHERE
                `type` = 'Applied Job' AND job_id = ? AND user_id = ?";

        $stmt = $con_main->prepare($sql);
        $stmt->bind_param("ii", $job_id, $userId);
        $stmt->execute();
        $appliedCount = $stmt->get_result()->fetch_assoc();

        $is_applied = $appliedCount['total'] > 0 ? true : false;
              ?>
        <?php if (!$is_applied): ?>
            <div class="apply-box">
                PLEASE CLICK THE APPLY BUTTON TO SEND YOUR DETAILS VIA <?= htmlspecialchars($job['company_name']); ?>
                <br>
                <a href="../job-applicant/apply.php?job=<?= $_GET['job'] ?>" class="new-job-btn" style="margin-top: 5px;">Apply for Job</a>
            </div>
        <?php else : ?>
            <div class="alert success">
                Your are already applied for this job.
            </div>
        <?php endif ?>

    <?php elseif($userRole==1): ?>
        <div class="apply-box">
            Sign in as a candidate to apply
            <br />
            <a href="../login.php" class="new-job-btn" style="margin-top: 5px;">Sign in</a>
        </div>
    <?php endif ?>
</section>

<?php
include '../layouts/footer.php';
include '../layouts/layout_end.php';
?>