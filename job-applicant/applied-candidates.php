<?php include '../config/database.php'; ?>
<?php include '../layouts/layout_start.php'; ?>
<?php // include '../permission-check.php'; ?>

<link rel="stylesheet" href="application.css">

<?php include '../layouts/header.php'; ?>
<?php include 'backend/data-queries.php'; ?>

<?php $job = getSelectedJobPostDetails($_GET['job_id']); ?>
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
                        <span>📥 Total Applied: <?php echo $job['total_applications'] ?? 0; ?></span>
                        <span>✅ Shortlisted: <?php echo $job['shortlisted'] ?? 0; ?></span>
                        <span>    Hired: <?php echo $job['hired'] ?? 0; ?></span>
                        <span>❌ Rejected: <?php echo $job['rejected'] ?? 0; ?></span>
                    </div>
                </div>
            </div>

            <div class="job-right">
                <span class="status-pill selected"><?php echo $job['post_status']; ?></span>
                <span class="job-id">Job ID: #<?php echo $job['id']; ?></span>
            </div>
        </div>

        <div class="filter-bar">
            <div><label>From</label><input type="date" id="fromDate"></div>
            <div><label>To</label><input type="date" id="toDate"></div>
            <div><label>Search</label><input type="text" id="search" placeholder="Search candidate name/email"></div>
            <div><button class="btn btn-submit" style="margin-top: 22px;" onclick="loadApplications(1)">Filter</button></div>
        </div>
        <button class="btn btn-submit" onclick="markAsReviewed()">Mark as Reviewed</button>
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
            <tbody id="applicationTableBody"></tbody>
        </table>
        <div class="pagination"></div>
    </div>
</section>
<?php include 'modals/application_status_change.php'; ?>
<?php include 'modals/success-popup.php'; ?>
<?php include 'modals/error-popup.php'; ?>
<script>
    const paginationDiv = document.querySelector(".pagination");
    loadApplications();

    function openStatusModal(id, status,interview ='') {
        document.getElementById('statusModal').style.display = 'flex';
        document.getElementById('statusSelect').value = status;
        document.getElementById('interviewDate').value = interview;
        document.getElementById('applicationId').value = id;
        toggleInterviewDate();
    }

    function toggleInterviewDate() {
        let sel = document.getElementById('statusSelect').value;
        document.getElementById('interviewBox').style.display = (sel == 'Interview') ? 'block' : 'none';
    }

    function closeModal() {
        document.getElementById('statusModal').style.display = 'none';
    }


    function loadApplications(page = 1) {

        let from = document.getElementById('fromDate').value;
        let to = document.getElementById('toDate').value;
        let search = document.getElementById('search').value;
        let jobId = <?= (int)$_GET['job_id'] ?>;

        let url = `backend/job-applications.php?job_id=${jobId}&page=${page}&from=${from}&to=${to}&search=${search}`;
        let xhr = new XMLHttpRequest();
        xhr.open("GET", url);

        xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                let response = JSON.parse(this.responseText);
                let rows = '';

                response.data.forEach(r => {
                    var cv = response.base_url + 'assets/' + r.cv_url;

                    rows += `
                    <tr>
                        <td data-label="Select">
                            ${r.application_status == 'Applied' ? `<input type="checkbox" class="rowCheckbox" value="${r.id}">` : ''}
                        </td>
                        <td data-label="Name">${r.candidate_name}</td>
                        <td data-label="Email">${r.candidate_email}</td>
                        <td data-label="Contact">${r.contact_number ?? '-'}</td>
                        <td data-label="Experience">${r.experience ?? '-'} </td>
                        <td data-label="Role">${r.current_role ?? '-'}</td>
                        <td data-label="Notice">${r.notice_period ?? '-'}</td>
                        <td data-label="Applied">${r.applied_at}</td>
                        <td data-label="Status">
                            <span class="status-pill ${r.application_status.toLowerCase()}">
                                ${r.application_status}
                            </span>
                        </td>
                        <td>
                            <a href="${cv}" target="_blank" class="btn btn-info">👁 CV</a>
                            <button class="btn btn-view" onclick="openStatusModal(${r.id}, '${r.application_status}','${r.interview_at ?? ''}')">
                                Change Status
                            </button>
                        </td>
                    </tr>`;
                });

                document.getElementById('applicationTableBody').innerHTML =
                    rows || `<tr><td colspan="9" style="text-align:center;">No applications found</td></tr>`;

                renderPagination(response.totalPages, page);
            }
        };
        xhr.send();

    }

    function renderPagination(total, current) {
        let html = "";
        for (let i = 1; i <= total; i++) {
            html += `<a onclick="loadApplications(${i})" class="${i == current ? 'active' : ''}">${i}</a>`;
        }

        paginationDiv.innerHTML = html;

    }

    /** */
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

        xhr.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                let res = JSON.parse(this.responseText);
                if (res.status == "success") {
                    showSuccess("Update Successfully!",res.message);
                    loadApplications(); 
                } else {
                    showError(res.message);
                }
            }
        };

        xhr.send("ids=" + encodeURIComponent(selected.join(",")));
    }


    /** save status update */
    function saveStatus() {
        let applicationId = document.getElementById('applicationId').value;
        let status = document.getElementById('statusSelect').value;
        let interviewDate = document.getElementById('interviewDate').value;

        if (status == 'Interview' && interviewDate == '') {
            showError("Please select interview date & time");
            return;
        }

        let formData = new FormData();
        formData.append('application_id', applicationId);
        formData.append('status', status);
        formData.append('interview_date', interviewDate);

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "backend/update-application-status.php", true);

        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4) {
                if (xhr.status == 200) {
                    let res = JSON.parse(xhr.responseText);

                    if (res.status == "success") {
                        showSuccess("Update Successfully!",res.message);
                        closeModal();
                        loadApplications();
                    } else {
                        showError(res.message || 'Update failed');
                    }
                } else {
                    showError('Server error');
                }
            }
        };

        xhr.send(formData);
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
        closeModal();
    }


</script>
<?php include '../layouts/footer.php'; ?>
<?php include '../layouts/layout_end.php'; ?>