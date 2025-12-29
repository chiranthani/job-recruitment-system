<?php include '../config/database.php'; ?>
<?php include '../layouts/layout_start.php'; ?>

<link rel="stylesheet" href="application.css">

<?php 
include '../layouts/header.php'; 
include 'backend/data-queries.php'; 
$cardData = getApplicationOverview();

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$limit = 10;

$result = getJobPostStats($search,$page,$limit);
$jobs = $result['jobs'];
$totalPages = $result['totalPages'];
?>

<section>
    <div class="main-container">
        <h2 class="page-title">Applications Overview</h2>
        <p class="page-sub-title"></p>

        <div class="summary-cards">
            <div class="card">
                <div class="card-icon">📄</div>
                <div class="card-content">
                    <h3><?= $cardData['this_week_count'] ?></h3>
                    <p>This Week</p>
                </div>
            </div>
            <div class="card">
                <div class="card-icon">📅</div>
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
        </div>


        <h3 class="section-title">Summary</h3>

      <form method="GET" id="searchForm" class="my-application-filter-bar">
            <div class="search-box">
                <input 
                    type="text" 
                    id="searchInput"
                    name="search" 
                    value="<?= htmlspecialchars($search) ?>" 
                    placeholder="Search job title"
                >
                <button type="submit" class="btn btn-submit" style="height: fit-content;">Search</button>
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
                                </a>
                            </td>
                            <td data-label="Total"><?= $job['total'] ?></td>
                            <td data-label="New"><?= $job['new'] ?></td>
                            <td data-label="Reviewed"><?= $job['reviewed'] ?></td>
                            <td data-label="Rejected"><?= $job['rejected'] ?></td>
                            <td data-label="Interview"><?= $job['interview'] ?></td>
                            <td data-label="Offer"><?= $job['offer'] ?></td>
                            <td data-label="Hired"><?= $job['hired'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a 
                    href="?page=<?= $i ?>&search=<?= urlencode($search) ?>" 
                    class="<?= $i == $page ? 'active' : '' ?>"
                >
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>
    </div>

</section>


<?php include '../layouts/footer.php'; ?>
<?php include '../layouts/layout_end.php'; ?>