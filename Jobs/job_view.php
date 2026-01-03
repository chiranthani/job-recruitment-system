<!-- start page common elements -->
<?php
include '../config/database.php';
include '../layouts/layout_start.php';
include '../layouts/header.php';

/* ==========================
   GET JOB ID
============================ */

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: job_list.php");
    exit;
}

$job_id = (int) $_GET['id'];
$userRole = $_SESSION['role_id'] ?? 0;

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

if (!$job) {
    header("Location: job_list.php");
    exit;
}

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

<link rel="stylesheet" href="job-post.css">

<section class="page-wrapper job-view-container">

    <div class="job-header">
        <div>
            <div class="job-title"><?= htmlspecialchars($job['title']); ?></div>
            <div class="job-company"><?= htmlspecialchars($job['company_name']); ?></div>

            <div class="job-meta">
                <div class="meta-item">📍 <?= htmlspecialchars($job['location_name']); ?></div>
                <div class="meta-item">🧰 <?= htmlspecialchars($job['job_type']); ?></div>
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

    <?php if($userRole == 1): ?>
    <!-- Apply Section -->
    <div class="apply-box">
        PLEASE CLICK THE APPLY BUTTON TO SEND YOUR DETAILS VIA <?= htmlspecialchars($job['company_name']); ?>
        <br>
        <button class="apply-button">Apply for Job</button>
    </div>
    <?php endif?>

</section>

<?php
include '../layouts/footer.php';
include '../layouts/layout_end.php';
?>
