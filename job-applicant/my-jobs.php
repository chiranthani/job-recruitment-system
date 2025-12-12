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
        <?php foreach(AppConstants::APPLICATION_STATUS as $key => $label): ?>
            <button class="tab" data-status="<?= ($key); ?>">
                <?= $label; ?>
            </button>
        <?php endforeach; ?>
    </div>

    <!-- Search Bar -->
    <div class="search-box">
        <input type="text" id="jobSearch" placeholder="Search job title or company...">
    </div>

</div>

    <div class="application-list">

        <div class="application-item">
            <div>
                <h4>Senior Frontend Developer</h4>
                <p class="company">TechCorp</p>
                <p class="date">Applied on 21/11/2025</p>
            </div>
            <div class="status-actions">
                <span class="status in-review">In Review</span>
                <button class="btn btn-view">View Details</button>
            </div>
        </div>
    </div>
</div>

</section>


<?php include '../layouts/footer.php'; ?>
<?php include '../layouts/layout_end.php'; ?>