<?php include '../layouts/layout_start.php'; ?>

<link rel="stylesheet" href="../assets/css/application.css">

<?php
include '../layouts/header.php';
require 'backend/data-queries.php';

$results = searchJobs($_GET);

$jobs = $results['jobs'];
$totalPages = $results['total_pages'];
$page = $results['page'];
$total_records = $results['total_records'];

// get cadidate applied jobs
$userId = $_SESSION['user_id'] ?? 0;
$appliedType = AppConstants::APPLIED_JOB;
$appliedJobs = getAppliedJobIds($userId, $appliedType) ?? [];
?>

<div class="main-container">
    <h2 class="page-title">Search Jobs</h2>
    <p class="page-sub-title">Find new opportunities</p>
    <div>
        <form method="GET" id="jobSearchForm" class="search-top-filter">

            <input
                type="search"
                name="search"
                id="searchInput"
                class="job-search-box"
                value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                onsearch="this.form.submit()"
                placeholder="Search job title...">

            <select name="work_type" id="workType" class="work-type-select">
                <option value="all">All work types</option>
                <?php foreach (AppConstants::WORK_TYPES as $type): ?>
                    <option value="<?= $type ?>"
                        <?= (($_GET['work_type'] ?? '') == $type) ? 'selected' : '' ?>>
                        <?= $type ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="company" id="company" class="company-select">
                <option value="all">All Companies</option>
                <?php foreach (getApprovedCompanies() as $res): ?>
                    <option value="<?= $res['id'] ?>"
                        <?= (($_GET['company'] ?? '') == $res['id']) ? 'selected' : '' ?>>
                        <?= $res['name'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
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
            <div style="margin-bottom: 10px;">
                <?php if ($total_records > 0): ?>
                    <p>Found <strong><?= $total_records ?></strong> job<?= $total_records > 1 ? 's' : '' ?> matching your criteria.</p>
                <?php else: ?>
                    <p style="color:red;">No matching jobs found.</p>
                <?php endif; ?>

            </div>
            <?php foreach ($jobs as $job): ?>
                <div class="job-card">
                    <?php $hasMatch = ($job['skill_match_score'] ?? 0) > 0; ?>
                    <div class="job-card-header">
                        <h4><?= htmlspecialchars($job['title']) ?>
                            <?php if ($hasMatch): ?>
                                <span class="skill-badge">Skill Match</span>
                            <?php endif; ?>
                        </h4>
                        <div>
                            <?php if (in_array($job['id'], $appliedJobs)): ?>
                                <span class="applied-badge">✔ Applied </span>
                            <?php endif ?>
                            <a class="apply-btn" href="../Jobs/job_view.php?job=<?= $job['id'] ?>">View</a>
                        </div>
                    </div>

                    <a href="company-jobs.php?company_id=<?= $job['company_id'] ?>">
                        <p class="company"><?= htmlspecialchars($job['company_name']) ?> </p>
                    </a>

                    <div class="job-card-about">
                        <div><?= htmlspecialchars($job['location_name']) ?></div>
                        <div><?= htmlspecialchars($job['job_type']) ?></div>
                        <div class="status job-type"><?= htmlspecialchars($job['work_type']) ?></div>
                    </div>

                    <div class="job-card-des">
                        <?= substr(strip_tags($job['description']), 0, 230) ?>...
                    </div>

                    <div class="job-card-footer">
                        Exp date: <?= $job['expiry_date'] ?> (⏰ <?= max(0, (int)$job['days_left']); ?> days left)
                    </div>
                </div>
            <?php endforeach; ?>


            <?php if ($totalPages > 1): ?>
                <div class="pagination-wrapper">

                    <div class="pagination-info">
                        Page <strong><?= $page ?></strong> of <strong><?= $totalPages ?></strong>
                    </div>

                    <div class="pagination">
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <a
                                href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"
                                class="<?= ($i == $page) ? 'active' : '' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>
                    </div>

                </div>
            <?php endif; ?>


        </section>

    </div>
</div>
<script type="text/javascript">
    // Mobile category toggle
    const categoryHeader = document.querySelector('.category-header');
    const filterList = document.querySelector('.filter-list');

    categoryHeader.addEventListener('click', () => {
        if (window.innerWidth <= 768) {
            categoryHeader.classList.toggle('active');
            filterList.classList.toggle('active');
        }
    });


    let typingTimer;
    const delay = 500;

    const form = document.getElementById('jobSearchForm');
    const searchInput = document.getElementById('searchInput');
    const workType = document.getElementById('workType');
    const company = document.getElementById('company');

    // text search
    searchInput.addEventListener('input', () => {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(() => {
            form.submit();
        }, delay);
    });

    // dropdowns
    workType.addEventListener('change', () => form.submit());
    company.addEventListener('change', () => form.submit());
</script>


<?php include '../layouts/footer.php'; ?>
<?php include '../layouts/layout_end.php'; ?>