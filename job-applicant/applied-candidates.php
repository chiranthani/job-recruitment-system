<?php include '../config/database.php'; ?>
<?php include '../layouts/layout_start.php'; ?>
<?php include '../permission-check.php'; ?>

<link rel="stylesheet" href="application.css">

<?php include '../layouts/header.php'; ?>
<?php include 'backend/data-queries.php'; ?>

<?php
$jobId = (int)$_GET['job_id'];
$fromDate = $_GET['from'] ?? '';
$toDate = $_GET['to'] ?? '';
$search = $_GET['search'] ?? '';
$page = max(1, (int)($_GET['page'] ?? 1));
$base_url = BaseConfig::$BASE_URL;

$job = getSelectedJobPostDetails($jobId);

$results = getJobApplications($jobId, $fromDate, $toDate, $search, $page);

$applications = $results['data'];
$totalPages = $results['totalPages'];
$totalApplications = $results['total'];

?>
<section>
    <div class="main-container">
        <a onclick="window.history.back()" class="back-link">← Back to Overview</a>
        <h2 class="page-title">Candidate Applications</h2>

        <!-- Job Card -->
        <div class="job-header-card">
            <div class="job-left">
                <div class="job-icon">📄</div>

                <div class="job-info">
                    <h3 class="job-title"><?php echo $job['title']; ?></h3>

                    <div class="job-meta">
                        <span class="job-category"><?php echo $job['category_name']; ?></span>
                        <span class="job-type"><?php echo $job['job_type']; ?></span>
                        <span class="job-location"><?php echo $job['location_name']; ?></span>
                    </div>

                    <div class="job-extra">
                        <span class="published-date">📅 Published: <?php echo $job['published_date']; ?></span>
                    </div>
                    <div class="job-stats">
                        <span>📥 Total Applied: <?= $job['total_applications'] ?? 0 ?></span>
                        <span>⭐ Shortlisted: <?= $job['shortlisted'] ?? 0 ?></span>
                        <span>👔 Hired: <?= $job['hired'] ?? 0 ?></span>
                        <span>❌ Rejected: <?= $job['rejected'] ?? 0 ?></span>
                    </div>
                </div>
            </div>

            <div class="job-right">
                <span class="status-pill selected"><?php echo $job['post_status']; ?></span>
                <span class="job-id">Job ID: #<?php echo $job['id']; ?></span>
            </div>
        </div>
        <form method="GET">
            <div class="filter-bar">

                <input type="hidden" name="job_id" value="<?= $jobId ?>">
                <div><label>From</label><input type="date" name="from" value="<?= htmlspecialchars($fromDate) ?>"></div>
                <div><label>To</label><input type="date" name="to" value="<?= htmlspecialchars($toDate) ?>"></div>
                <div><label>Search</label><input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search candidate name/email"></div>
                <div><button type="submit" class="btn btn-submit" style="margin-top: 22px;">Filter</button></div>

            </div>
        </form>
        <div style="display: flex; justify-content:space-between;gap:10px">
            <button class="btn btn-submit" onclick="markAsReviewed()">Mark as Reviewed</button>
            <span><strong><?= $totalApplications ?></strong> Application(s) Found</span>
        </div>

        <table class="responsive-table">
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll" onclick="toggleSelectAll(this)"></th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Contact No</th>
                    <th>Experience</th>
                    <th>Current Role</th>
                    <th>Notice Time</th>
                    <th>Applied Date</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($applications) == 0): ?>
                    <tr>
                        <td colspan="10" style="text-align:center;">No applications found</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($applications as $r):
                        $statusClass = strtolower(str_replace(' ', '-', $r['application_status']));
                        $cv = $r['cv_url'] ? $base_url . 'assets/' . $r['cv_url'] : '#';
                    ?>
                        <tr>
                            <td data-label="Select"><?= $r['application_status'] == AppConstants::APPLICATION_STATUS['APPLIED'] ? '<input type="checkbox" class="rowCheckbox" value="' . $r['id'] . '">' : '' ?></td>
                            <td data-label="Name"><?= htmlspecialchars($r['candidate_name']) ?></td>
                            <td data-label="Email"><?= htmlspecialchars($r['candidate_email']) ?></td>
                            <td data-label="Contact Number"><?= $r['contact_number'] ?? '-' ?></td>
                            <td data-label="Experience"><?= $r['experience'] ?? '-' ?></td>
                            <td data-label="Current Role"><?= $r['current_role'] ?? '-' ?></td>
                            <td data-label="Notice Period"><?= $r['notice_period'] ?? '-' ?></td>
                            <td data-label="Applied At"><?= $r['applied_at'] ?></td>
                            <td data-label="Status"><span class="status-pill <?= $statusClass ?>"><?= $r['application_status'] ?></span></td>
                            <td>
                                <a href="<?= $cv ?>" target="_blank" class="btn btn-info">👁 CV</a>
                                <button class="btn btn-view" onclick="openStatusModal(<?= $r['id'] ?>,'<?= $r['application_status'] ?>','<?= $r['interview_at'] ?? '' ?>')">Change Status</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?job_id=<?= $jobId ?>&from=<?= $fromDate ?>&to=<?= $toDate ?>&search=<?= urlencode($search) ?>&page=<?= $i ?>" class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
        </div>
    </div>
</section>
<?php include 'modals/application_status_change.php'; ?>
<?php include 'modals/success-popup.php'; ?>
<?php include 'modals/error-popup.php'; ?>

<?php if (isset($_GET['success'])): ?>
<script>
document.addEventListener("DOMContentLoaded", function () {
    showSuccess("Success",<?= json_encode($_GET['success']) ?>);
});
</script>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
<script>
document.addEventListener("DOMContentLoaded", function () {
    showError(<?= json_encode($_GET['error']) ?>);
});
</script>
<?php endif; ?>

<script>
    function openStatusModal(id, status, interview = '') {
        document.getElementById('statusModal').style.display = 'flex';
        document.getElementById('status').value = status;
        document.getElementById('interview_date').value = interview;
        document.getElementById('application_id').value = id;
        toggleInterviewDate();
    }

    function toggleInterviewDate() {
        let sel = document.getElementById('statusSelect').value;
        document.getElementById('interviewBox').style.display = (sel == 'Interview') ? 'block' : 'none';
    }

    function closeModal() {
        document.getElementById('statusModal').style.display = 'none';
    }


    function toggleSelectAll(masterCheckbox) {
        const checkboxes = document.querySelectorAll(".rowCheckbox");
        checkboxes.forEach(cb => cb.checked = masterCheckbox.checked);
    }

    /** mark as reviewed - bullk */
    function markAsReviewed() {
        let selected = [];
        document.querySelectorAll(".rowCheckbox:checked").forEach(cb => {
            selected.push(cb.value);
        });

        if (selected.length == 0) {
            showError("Please select at least one application.");
            return;
        }

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "backend/mark-reviewed.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                let res = JSON.parse(this.responseText);
                if (res.status == "success") {
                    showSuccess("Update Successfully!", res.message);

                } else {
                    showError(res.message);
                }
            }
        };

        xhr.send("ids=" + encodeURIComponent(selected.join(",")));
    }


    // Popup handling
    function showError(msg) {
        document.getElementById("errorMessage").textContent = msg;
        document.getElementById("errorPopup").style.display = "flex";
    }

    function showSuccess(title, msg) {
        document.getElementById("popup-title").textContent = title;
        document.getElementById("popup-message").textContent = msg;
        document.getElementById("successPopup").style.display = "flex";
    }

    function closeSuccessPopup() {
        document.getElementById("successPopup").style.display = "none";
        closeModal();
        clearQueryParams();
        location.reload();
    }

    function clearQueryParams() {
        const url = new URL(window.location.href);
    
        url.searchParams.delete('success');
        url.searchParams.delete('error');

        window.history.replaceState({}, document.title, url.pathname + url.search);
    }

</script>
<?php include '../layouts/footer.php'; ?>
<?php include '../layouts/layout_end.php'; ?>