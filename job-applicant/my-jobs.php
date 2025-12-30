<?php include '../config/database.php'; ?>
<?php include '../layouts/layout_start.php'; ?>
<?php include '../permission-check.php'; ?>

<link rel="stylesheet" href="application.css">

<?php include '../layouts/header.php'; 
include 'backend/data-queries.php';
$currentStatus = $_GET['status'] ?? 'ALL';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$applications = getAppliedJobs($currentStatus, $search);
$cardData = getMyJobCardData();
?>

<section>
    <div class="main-container">
        <h2 class="page-title">My Jobs</h2>
        <p class="page-sub-title">Track your applications and find new opportunities</p>

        <div class="summary-cards">
            <div class="card">
                <div class="card-icon">📄</div>
                <div class="card-content">
                    <h3><?= $cardData['application_count']; ?></h3>
                    <p>Applications</p>
                </div>
            </div>
            <div class="card">
                <div class="card-icon">📅</div>
                <div class="card-content">
                    <h3><?= $cardData['interview_count']; ?></h3>
                    <p>Interviews</p>
                </div>
            </div>
            <div class="card">
                <div class="card-icon">💼</div>
                <div class="card-content">
                    <h3><?= $cardData['pending_decision']; ?></h3>
                    <p>Pending Decision</p>
                </div>
            </div>
             <div class="card">
                <div class="card-icon">🏆</div>
                <div class="card-content">
                    <h3><?= $cardData['hired_count']; ?></h3>
                    <p>Hired</p>
                </div>
            </div>
        </div>


        <h3 class="section-title">My Applications</h3>

        <div class="my-application-filter-bar">
            <!-- Status Tabs -->
            <div class="status-tabs">
               <button class="tab <?= $currentStatus === 'ALL' ? 'active' : '' ?>" data-status="ALL">All</button>
                <?php foreach (AppConstants::APPLICATION_STATUS as $label): ?>
                    <button class="tab <?= $currentStatus === $label ? 'active' : '' ?>" data-status="<?= $label ?>">
                        <?= $label ?>
                    </button>
                <?php endforeach; ?>

            </div>

            <!-- Search Bar -->
            <div class="search-box">
                <input type="text" id="jobSearch" value="<?= $search ?>" placeholder="Search job title or company...">
            </div>

        </div>

    <div class="application-list">
        <?php if (empty($applications)): ?>
            <p>No applications found</p>
        <?php endif; ?>

        <?php foreach ($applications as $item): 
            $statusClass = strtolower(str_replace(' ', '-', $item['application_status']));
            $date = date('d/m/Y', strtotime($item['applied_at']));
        ?>
            <div class="application-item">
                <div>
                    <h4><?= htmlspecialchars($item['title']) ?></h4>
                    <p class="company"><?= htmlspecialchars($item['company_name']) ?></p>
                    <p class="date">Applied on <?= $date ?></p>
                </div>

                <div class="status-actions">
                    <span class="status <?= $statusClass ?>">
                        <?= $item['application_status'] ?>
                    </span>

                    <?php if ($item['application_status'] == AppConstants::APPLICATION_STATUS['OFFERED']): ?>
                        <button class="btn btn-success"
                            onclick="updateStatus(<?= $item['id'] ?>, '<?= AppConstants::APPLICATION_STATUS['OFFER_ACCEPTED'] ?>')">
                            Accept Offer
                        </button>

                        <button class="btn btn-danger"
                            onclick="updateStatus(<?= $item['id'] ?>, '<?= AppConstants::APPLICATION_STATUS['OFFER_RJECTED'] ?>')">
                            Reject Offer
                        </button>
                    <?php endif; ?>

                    <button class="btn btn-view"
                        onclick="window.location.href='../Jobs/job_view.php?job=<?= $item['job_id'] ?>'">
                        View Details
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

</section>
<?php include 'modals/success-popup.php'; ?>
<?php include 'modals/error-popup.php'; ?>

<script>
    let currentStatus = 'ALL';

    document.querySelectorAll('.status-tabs .tab').forEach(tab => {
        tab.addEventListener('click', function () {
            const params = new URLSearchParams(window.location.search);
            params.set('status', this.dataset.status);
            params.set('search', document.getElementById('jobSearch').value);
            window.location.href = '?' + params.toString();
        });
    });

    let searchTimer;

    document.getElementById('jobSearch').addEventListener('keyup', function () {
        clearTimeout(searchTimer);

        searchTimer = setTimeout(() => {
            const params = new URLSearchParams(window.location.search);
            params.set('search', this.value);
            params.set('status', '<?= $currentStatus ?>');
            window.location.href = '?' + params.toString();
        }, 400);
    });

    function updateStatus(applicationId, newStatus) {

        if (!confirm("Are you sure you want to set status as '" + newStatus + "'?")) {
            return;
        }
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "backend/update-application-status.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onload = function() {
            if (xhr.status == 200) {
                let res = JSON.parse(xhr.responseText);
                if (res.status == "success") {
                    showSuccess(newStatus+" Successfully!",res.message || "Successfully!");
            
                } else {
                    showError(res.message || "Something went wrong!");
                }                
            } else {
                showError("Server error! Try again later.");
            }
        };

        xhr.send(
            "application_id=" + encodeURIComponent(applicationId) +
            "&status=" + encodeURIComponent(newStatus)
        );
    }

    // Popup handling
    function showError(msg) {
        document.getElementById("errorMessage").textContent = msg;
        document.getElementById("errorPopup").style.display = "flex";
    }

    function showSuccess(title,msg) {
        document.getElementById("popup-title").textContent = title;
        document.getElementById("popup-message").textContent = msg;
        document.getElementById("successPopup").style.display = "flex";
    }
    
    function closeSuccessPopup() {
        document.getElementById("successPopup").style.display = "none";
        location.reload();
    }
</script>


<?php include '../layouts/footer.php'; ?>
<?php include '../layouts/layout_end.php'; ?>