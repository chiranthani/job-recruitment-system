<?php include '../config/database.php'; ?>
<?php include '../layouts/layout_start.php'; ?>

<link rel="stylesheet" href="application.css">

<?php 
include '../layouts/header.php'; 
include 'backend/data-queries.php';
?>

<div class="main-container">

    <div class="search-top-filter">
        <input type="text" class="job-search-box" value="<?php echo ($_GET['search'] ?? "") ?>" placeholder="Search job title...">
            <select class="work-type-select">
            <option value="all">All work types</option>
            <?php 
            foreach (AppConstants::WORK_TYPES as $type) {
            ?>
                <option value="<?php echo $type; ?>"><?php echo $type; ?></option>
            <?php } ?>

        </select>
        <select class="company-select">
            <option value="all">All Companies</option>
            <?php
            $get_approved_companies = getApprovedCompanies();

            $sql = mysqli_query($con_main, $get_approved_companies);
            while ($res = mysqli_fetch_array($sql)) {
            ?>
                <option value="<?php echo $res['id']; ?>"><?php echo $res['name']; ?></option>

            <?php } ?>

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
                $get_active_categories = getActiveCategoriesWithJobsCount();

                $sql = mysqli_query($con_main, $get_active_categories);
                while ($res = mysqli_fetch_array($sql)) {
                ?>
                    <label class="filter-item"><input type="checkbox" value="<?php echo $res['id']; ?>">
                        <span><?php echo $res['name']; ?></span> <span class="count"><?php echo $res['post_count']; ?></span>
                    </label>

                <?php } ?>

            </div>
        </aside>


        <section class="jobs-list">
            <div id="jobs-list-cards">

            </div>


            <div class="pagination">
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

    const searchInput = document.querySelector(".job-search-box");
    const companySelect = document.querySelector(".company-select");
    const workTypeSelect = document.querySelector(".work-type-select");
    const categoryChecks = document.querySelectorAll(".filter-item input[type=checkbox]");
    const jobsList = document.getElementById("jobs-list-cards");
    const paginationDiv = document.querySelector(".pagination");

    const urlParams = new URLSearchParams(window.location.search);
    const initialCategory = urlParams.get("category") || "";

    if (initialCategory) {
        categoryChecks.forEach(c => {
            if (c.value == initialCategory) {
                c.checked = true;
            }
        });
    }

    //  event live listeners
    searchInput.addEventListener("keyup", fetchJobs);
    companySelect.addEventListener("change", fetchJobs);
    workTypeSelect.addEventListener("change", fetchJobs);
    categoryChecks.forEach(c => c.addEventListener("change", fetchJobs));

    fetchJobs();

    function fetchJobs(page = 1) {
        let search = searchInput.value;
        let company = companySelect.value;
        let work_type = workTypeSelect.value;

        let categories = [];
        categoryChecks.forEach(c => {
            if (c.checked) {
                categories.push(c.value);
            }
        });

        let url = `backend/search.php?page=${page}&search=${encodeURIComponent(search)}&company=${company}&work_type=${encodeURIComponent(work_type)}`;

        if (categories.length > 0) {
            categories.forEach(cat => {
                url += `&categories[]=${encodeURIComponent(cat)}`;
            });
        }

        let xhr = new XMLHttpRequest();
        xhr.open("GET", url);

        xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                let response = JSON.parse(this.responseText);
                console.log('response:',response);
                renderJobs(response.jobs);
                renderPagination(response.total_pages, response.page);
            }
        };
        xhr.send();
    }


    function renderJobs(jobs) {
        jobsList.innerHTML = "";
        if (jobs.length == 0) {
            jobsList.innerHTML = `<p style="margin:20px; color:red;">No matching jobs found.</p>`;
            return;
        }

        jobs.forEach(job => {
            jobsList.innerHTML += `
                <div class="job-card">
                    <div class="job-card-header">
                        <h4>${job.title}</h4>
                        <button class="apply-btn" onclick="window.location.href='apply.php?job=${job.id}'">Apply Now</button>
                    </div>
                    <p class="company">${job.company_name}</p>
                    <div class="job-card-about">
                        <div>${job.location_name}</div>
                        <div>${job.job_type}</div>
                        <div class="status job-type">${job.work_type}</div>
                    </div>
                    <div class="job-card-des">${job.description.substring(0,230)}...</div>
                    <div class="job-card-footer">Exp date: ${job.expiry_date}</div>
                </div>`;
        });
    }

    function renderPagination(total, current) {
        let html = "";
        for (let i = 1; i <= total; i++) {
            html += `<a onclick="fetchJobs(${i})" class="${i == current ? 'active' : ''}">${i}</a>`;
        }

        paginationDiv.innerHTML = html;
    }

</script>


<?php include '../layouts/footer.php'; ?>
<?php include '../layouts/layout_end.php'; ?>