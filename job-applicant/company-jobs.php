<?php include '../config/database.php'; ?>
<?php include '../layouts/layout_start.php'; ?>
<link rel="stylesheet" href="application.css">
<?php include '../layouts/header.php'; ?>
<?php include 'backend/data-queries.php'; ?>

<?php
$companyId = (int)($_GET['company_id'] ?? 0);
$company = getCompanyDetails($companyId);
$jobslist = searchJobs(['company' => $companyId]);
$jobs = $jobslist['jobs'];
$totalPages = $jobslist['total_pages'];
$page = $jobslist['page'];
$total_records = $jobslist['total_records'];

if (!$company) {
    echo "<p style='margin:20px;color:red;'>Company not found.</p>";
    exit;
}
?>

<div class="main-container">

    <div class="company-layout">


        <aside class="company-sidebar">
            <div class="company-card">


                <h3><?= htmlspecialchars($company['name']) ?></h3>
                <p class="company-location">📍 <?= $company['address'] ?></p>
                <p class="company-description">
                    <?= htmlspecialchars($company['description']) ?>
                </p>

                <?php if (!empty($company['website_link'])): ?>
                    <a href="<?= $company['website_link'] ?>" target="_blank" class="company-website">
                        🌐 Visit Website
                    </a>
                <?php endif; ?>
            </div>
        </aside>


        <section class="company-jobs-list">
            <h2>Jobs at <?= htmlspecialchars($company['name']) ?></h2>

            <?php if (count($jobs) == 0): ?>
                <p style="color:red;">No active jobs available.</p>
            <?php endif; ?>

            <?php foreach ($jobs as $job): ?>
                <div class="job-card">
                    <div class="job-card-header" style="margin-bottom:10px">
                        <h4><?= htmlspecialchars($job['title']) ?></h4>
                        <a href="../Jobs/job_view.php?job=<?= $job['id'] ?>" class="buttn buttn-outline">View</a>
                    </div>

                    <div class="job-card-about">
                        <span><?= $job['location_name'] ?></span>
                        <span><?= $job['job_type'] ?></span>
                        <span class="status job-type"><?= $job['work_type'] ?></span>
                    </div>

                    <div class="job-card-footer">
                        Expiry: <?= $job['expiry_date'] ?>
                    </div>
                </div>
            <?php endforeach; ?>
             <div class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a
                        href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"
                        class="<?= ($i == $page) ? 'active' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </div>
        </section>

    </div>
</div>

<?php include '../layouts/footer.php'; ?>
<?php include '../layouts/layout_end.php'; ?>