<?php include '../config/database.php'; ?>
<?php include '../layouts/layout_start.php'; ?>
<?php include '../permission-check.php'; ?>

<link rel="stylesheet" href="../assets/css/application.css">

<?php
include '../layouts/header.php';
include 'backend/data-queries.php';
$cardData = getApplicationOverview();

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$limit = 10;

$result = getJobPostStats($search, $page, $limit);
$jobs = $result['jobs'];
$totalPages = $result['totalPages'];
?>

<section>
    <div class="main-container">
        <h2 class="page-title">Applications Overview</h2>
        <p class="page-sub-title"></p>

        <div class="summary-cards">
            <div class="card">
                <div class="card-icon">🗓️</div>
                <div class="card-content">
                    <h3><?= $cardData['this_week_count'] ?></h3>
                    <p>This Week</p>
                </div>
            </div>
            <div class="card">
                <div class="card-icon">⏳</div>
                <div class="card-content">
                    <h3><?= $cardData['applied_count'] ?></h3>
                    <p>Pending Review</p>
                </div>
            </div>
            <div class="card">
                <div class="card-icon">📅</div>
                <div class="card-content">
                    <h3><?= $cardData['interview_count'] ?></h3>
                    <p>Interview Scheduled</p>
                </div>
            </div>
             <div class="card">
                <div class="card-icon">📨</div>
                <div class="card-content">
                    <h3><?= $cardData['offered_count'] ?></h3>
                    <p>Pending Candidate Decision</p>
                </div>
            </div>
        </div>


        <div class="container">
        <h3 class="section-title">Summary</h3>

        <form method="GET" id="searchForm" class="my-application-filter-bar">
            <div class="search-box">
                <input
                    type="search"
                    name="search"
                    id="searchInput"
                    value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                    placeholder="Search job title"
                    onsearch="this.form.submit()"
                >

            </div>
        </form>


        <table class="responsive-table">
            <thead>
                <tr>
                    <th>Job Title</th>
                    <th>Total</th>
                    <th>New</th>
                    <th>Reviewed</th>
                    <th>Rejected</th>
                    <th>Interview</th>
                    <th>Offer</th>
                    <th>Offer Responses</th>
                    <th>Hired</th>
                </tr>
            </thead>
            <tbody style="text-align:center;">
                <?php if (empty($jobs) || count($jobs) == 0): ?>
                    <tr>
                        <td colspan="8">No records found</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($jobs as $job): ?>
                        <tr>
                            <td data-label="Title">
                                <a href="applied-candidates.php?job_id=<?= $job['id'] ?>" class="job-link">
                                    <?= htmlspecialchars($job['title']) ?>
                                    <span  class="status" style="color: gray;" ><?= $job['is_deleted'] ? '(Deleted)' : '' ?></span>
                                </a>
                                
                            </td>
                            <td data-label="Total"><?= $job['total'] ?></td>
                            <td data-label="New">
                                <span class="badge <?= $job['new'] > 0 ? 'status rejected' : '' ?>">
                                    <?= $job['new'] ?>
                                </span>
                            </td>
                            <td data-label="Reviewed">
                                <span class="badge <?= $job['reviewed'] > 0 ? 'status in-review' : '' ?>">
                                    <?= $job['reviewed'] ?>
                                </span>
                            </td>
                            <td data-label="Rejected">
                                <?= $job['rejected'] ?>
                            </td>
                            <td data-label="Interview">
                                <span class="badge <?= $job['interview'] > 0 ? 'status light' : '' ?>">
                                    <?= $job['interview'] ?>
                                </span>
                            </td>
                            <td data-label="Offer">
                                 <span class="badge <?= $job['offer'] > 0 ? 'status light' : '' ?>">
                                    <?= $job['offer'] ?>
                                </span>
                            </td>
                            <td data-label="Offer Responses">
                               <span class="badge <?= $job['offer_responses'] > 0 ? 'status offer-accepted' : '' ?>">
                                    <?= $job['offer_responses'] ?>
                                </span>    
                            </td>
                            <td data-label="Hired"><?= $job['hired'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
         <?php if ($totalPages >= 1): ?>
         <div class="pagination-wrapper">
            <div class="pagination-info">
                Page <strong><?= $page ?></strong> of <strong><?= $totalPages ?></strong>
            </div>
            <div class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a
                        href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"
                        class="<?= $i == $page ? 'active' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </div>
         </div>
         <?php endif ?>
    </div>
        </div>
</section>

<script>
    let typingTimer;
    const delay = 500;

    const form = document.getElementById('searchForm');
    const searchInput = document.getElementById('searchInput');

    // text search
    searchInput.addEventListener('input', () => {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(() => {
            form.submit();
        }, delay);
    });

</script>
<?php include '../layouts/footer.php'; ?>
<?php include '../layouts/layout_end.php'; ?>