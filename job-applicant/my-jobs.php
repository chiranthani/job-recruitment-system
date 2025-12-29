<?php include '../config/database.php'; ?>
<?php include '../layouts/layout_start.php'; ?>

<link rel="stylesheet" href="application.css">

<?php include '../layouts/header.php'; ?>

<section>
    <div class="main-container">
        <h2 class="page-title">My Jobs</h2>
        <p class="page-sub-title">Track your applications and find new opportunities</p>

        <div class="summary-cards">
            <div class="card">
                <div class="card-icon">📄</div>
                <div class="card-content">
                    <h3>3</h3>
                    <p>Applications</p>
                </div>
            </div>
            <div class="card">
                <div class="card-icon">📅</div>
                <div class="card-content">
                    <h3>1</h3>
                    <p>Interviews</p>
                </div>
            </div>
            <div class="card">
                <div class="card-icon">📅</div>
                <div class="card-content">
                    <h3>1</h3>
                    <p>Offers</p>
                </div>
            </div>
        </div>


        <h3 class="section-title">My Applications</h3>

        <div class="my-application-filter-bar">
            <!-- Status Tabs -->
            <div class="status-tabs">
                <button class="tab active" data-status="ALL">All</button>
                <?php foreach (AppConstants::APPLICATION_STATUS as $key => $label): ?>
                    <button class="tab" data-status="<?= ($label); ?>">
                        <?= $label; ?>
                    </button>
                <?php endforeach; ?>
            </div>

            <!-- Search Bar -->
            <div class="search-box">
                <input type="text" id="jobSearch" placeholder="Search job title or company...">
            </div>

        </div>

        <div class="application-list" id="applicationList">
        </div>
    </div>

</section>
<?php include 'modals/success-popup.php'; ?>
<?php include 'modals/error-popup.php'; ?>

<script>
    let currentStatus = 'ALL';

    function loadApplications() {
        const search = document.getElementById('jobSearch').value;
        const list = document.getElementById('applicationList');

        const xhr = new XMLHttpRequest();
        xhr.open("POST", "backend/get-applied-jobs.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onload = function() {
            if (this.status != 200) return;

            const data = JSON.parse(this.responseText);
            list.innerHTML = '';

            if (data.applications.length == 0) {
                list.innerHTML = `<p>No applications found</p>`;
                return;
            }

            data.applications.forEach(item => {
                list.innerHTML += createApplicationItem(item);
            });
        };

        xhr.send(
            "status=" + encodeURIComponent(currentStatus) +
            "&search=" + encodeURIComponent(search)
        );
    }


    function createApplicationItem(item) {
        const statusClass = item.application_status
            .toLowerCase()
            .replace(/\s+/g, '-');

        let actionButtons = '';

        if (item.application_status == 'Offer Made') {
            actionButtons = `
                <button class="btn btn-success"
                    onclick="updateStatus(${item.id}, 'Offer Accepted')">
                    Accept Offer
                </button>
                <button class="btn btn-danger"
                    onclick="updateStatus(${item.id}, 'Offer Rejected')">
                    Reject Offer
                </button>
            `;
        }

        const date = new Date(item.applied_at).toLocaleDateString('en-GB');

        return `
            <div class="application-item">
                <div>
                    <h4>${item.title}</h4>
                    <p class="company">${item.company_name}</p>
                    <p class="date">Applied on ${date}</p>
                </div>
                <div class="status-actions">
                    <span class="status ${statusClass}">
                        ${item.application_status}
                    </span>
                    ${actionButtons}
                    <button class="btn btn-view" onclick="window.location.href='../Jobs/job_view.php?job=${item.job_id}'">View Details</button>
                </div>
            </div>
        `;
    }


    document.querySelectorAll('.status-tabs .tab').forEach(tab => {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.status-tabs .tab')
                .forEach(t => t.classList.remove('active'));

            this.classList.add('active');
            currentStatus = this.dataset.status;
            loadApplications();
        });
    });

    document.getElementById('jobSearch').addEventListener('keyup', loadApplications);

    loadApplications();

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
                    loadApplications();
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
    }
</script>


<?php include '../layouts/footer.php'; ?>
<?php include '../layouts/layout_end.php'; ?>