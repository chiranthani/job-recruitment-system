<?php include '../config/database.php'; ?>
<?php include '../layouts/layout_start.php'; ?>

<link rel="stylesheet" href="application.css">

<?php 
include '../layouts/header.php'; 
include 'backend/data-queries.php'; 
$cardData = getApplicationOverview();
?>

<section>
    <div class="main-container">
        <h2 class="page-title">Applications Overview</h2>
        <p class="page-sub-title"></p>

        <div class="summary-cards">
            <div class="card">
                <div class="card-icon">📄</div>
                <div class="card-content">
                    <h3><?php echo($cardData['this_week_count']); ?></h3>
                    <p>This Week</p>
                </div>
            </div>
            <div class="card">
                <div class="card-icon">📅</div>
                <div class="card-content">
                    <h3><?php echo($cardData['applied_count']); ?></h3>
                    <p>Pending Review</p>
                </div>
            </div>
            <div class="card">
                <div class="card-icon">📅</div>
                <div class="card-content">
                    <h3><?php echo($cardData['interview_count']); ?></h3>
                    <p>Interview Scheduled</p>
                </div>
            </div>
        </div>


        <h3 class="section-title">Summary</h3>

        <div class="my-application-filter-bar">

            <div class="search-box">
                <input type="text" id="search" placeholder="Search job title">
            </div>
        </div>

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
            <tbody id="jobTableBody" style="text-align: center;">

            </tbody>
        </table>
        <div class="pagination"></div>

    </div>

</section>
<script>
    let currentPage = 1;
    const paginationDiv = document.querySelector(".pagination");
    loadJobs();

    function loadJobs(page = 1) {
        currentPage = page;
        const search = document.getElementById('search').value;
        let url = `backend/job-post-stats.php?page=${page}&search=${encodeURIComponent(search)}`;

        let xhr = new XMLHttpRequest();
        xhr.open("GET", url);
        
        xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                let response = JSON.parse(this.responseText);
                let rows = '';
                response.jobs.forEach(job => {
                    rows += `
                    <tr>
                        <td data-label="Title">  
                            <a href="applied-candidates.php?job_id=${job.id}" class="job-link">
                            ${job.title}
                            </a>
                        </td>
                        <td data-label="Total">${job.total}</td>
                        <td data-label="New">
                            <span class="badge ${job.new > 0 ? 'status rejected' : ''}">
                                ${job.new}
                            </span>
                        </td>
                        <td data-label="Reviewed">
                            <span class="badge ${job.reviewed > 0 ? 'status in-review' : ''}">
                                ${job.reviewed}
                            </span>
                        </td>
                        <td data-label="Rejected"><span>${job.rejected}</span></td>
                        <td data-label="Interview">
                            <span class="badge ${job.interview > 0 ? 'status light' : ''}">
                                ${job.interview}
                            </span>
                        </td>
                        <td data-label="Offer"><span>${job.offer}</span></td>
                        <td data-label="Hired"><span>${job.hired}</span></td>
                    </tr>
                `;
                });

                document.getElementById('jobTableBody').innerHTML = rows;
                renderPagination(response.totalPages);
            }
        };
        xhr.send();

    }

    function renderPagination(totalPages) {

        let html = "";
        for (let i = 1; i <= totalPages; i++) {
            html += `<a onclick="loadJobs(${i})" class="${i == currentPage ? 'active' : ''}">${i}</a>`;
        }

        paginationDiv.innerHTML = html;
    }

    document.getElementById('search').addEventListener('keyup', () => loadJobs(1));


</script>


<?php include '../layouts/footer.php'; ?>
<?php include '../layouts/layout_end.php'; ?>