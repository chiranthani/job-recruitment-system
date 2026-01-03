<?php include '../config/database.php'; ?>
<?php include '../layouts/layout_start.php'; ?>

<link rel="stylesheet" href="application.css">

<?php
include '../layouts/header.php';
include 'backend/data-queries.php';

$results = searchJobs($_GET);

$jobs = $results['jobs'];
$totalPages = $results['total_pages'];
$page = $results['page'];
$total_records = $results['total_records'];
?>

<div class="main-container">

    <div class="search-top-filter">
        <form method="GET" class="search-top-filter">

            <input
                type="text"
                name="search"
                class="job-search-box"
                value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                placeholder="Search job title...">

            <select name="work_type" class="work-type-select">
                <option value="all">All work types</option>
                <?php foreach (AppConstants::WORK_TYPES as $type): ?>
                    <option value="<?= $type ?>"
                        <?= (($_GET['work_type'] ?? '') == $type) ? 'selected' : '' ?>>
                        <?= $type ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="company" class="company-select">
                <option value="all">All Companies</option>
                <?php foreach (getApprovedCompanies() as $res): ?>
                    <option value="<?= $res['id'] ?>"
                        <?= (($_GET['company'] ?? '') == $res['id']) ? 'selected' : '' ?>>
                        <?= $res['name'] ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn">Search</button>
    </div>

    <div class="search-content-area">

        <aside class="search-sidebar">
            <h3 class="category-header">
                Categories 
                <span class="arrow"></span>
            </h3>
            <div class="filter-list">
                <?php
                $selectedCategories = $_GET['categories'] ?? [];
                foreach (getActiveCategoriesWithJobsCount() as $res):
                ?>
                    <label class="filter-item">
                        <input
                            type="checkbox"
                            name="categories[]"
                            value="<?= $res['id'] ?>"
                            <?= in_array($res['id'], $selectedCategories) ? 'checked' : '' ?>
                            onchange="this.form.submit()">
                        <span><?= $res['name'] ?></span>
                        <span class="count"><?= $res['post_count'] ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        </aside>

        </form>
        <section class="jobs-list">
            <div>
                <?php if ($total_records > 0): ?>
                    <p>Found <strong><?= $total_records ?></strong> job<?= $total_records > 1 ? 's' : '' ?> matching your criteria.</p>
                <?php else: ?>
                    <p style="color:red;">No matching jobs found.</p>
                <?php endif; ?>

            </div>
            <?php foreach ($jobs as $job): ?>
                <div class="job-card">
                    <div class="job-card-header">
                        <h4><?= htmlspecialchars($job['title']) ?></h4>
                        <a class="apply-btn" href="../Jobs/job_view.php?job=<?= $job['id'] ?>">View</a>
                    </div>

                    <a href="company-jobs.php?company_id=<?= $job['company_id'] ?>"><p class="company"><?= $job['company_name'] ?></p></a>

                    <div class="job-card-about">
                        <div><?= $job['location_name'] ?></div>
                        <div><?= $job['job_type'] ?></div>
                        <div class="status job-type"><?= $job['work_type'] ?></div>
                    </div>

                    <div class="job-card-des">
                        <?= substr(strip_tags($job['description']), 0, 230) ?>...
                    </div>

                    <div class="job-card-footer">
                        Exp date: <?= $job['expiry_date'] ?>
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
<script>

    // Mobile category toggle
    const categoryHeader = document.querySelector('.category-header');
    const filterList = document.querySelector('.filter-list');

    categoryHeader.addEventListener('click', () => {
        if (window.innerWidth <= 768) {
            categoryHeader.classList.toggle('active');
            filterList.classList.toggle('active');
        }
    });


</script>


<?php include '../layouts/footer.php'; ?>
<?php include '../layouts/layout_end.php'; ?>