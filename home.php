<!-- start page common elements -->
<?php include 'config/database.php'; ?>
<?php include 'layouts/layout_start.php'; ?>
<link rel="stylesheet" href="assets/css/home.css">
<?php include 'layouts/header.php'; ?>
<?php include 'job-applicant/backend/data-queries.php'; ?>
<!-- end page common elements-->

<!-- start page main content -->
<section class="home-banner">
    <div class="banner-content">
        <h1>Your dream job awaits!</h1>
        <p>Explore exciting opportunities and apply to start your journey towards a rewarding career.</p>

        <form class="search-bar" method="GET" action="job-applicant/job-search.php">
            <input type="text" name="search" placeholder="Search jobs (e.g., Designer, Developer ...)">
            <button type="submit">Search</button>
        </form>
    </div>
</section>

<section class="job-categories">
    <h2>Browse Job Categories</h2>
    <div class="category-list">
        <?php
        $get_active_categories = getActiveCategoriesWithJobsCount();
       
        foreach ($get_active_categories as $res) {
        ?>
            <a href="job-applicant/job-search.php?categories[]=<?= $res['id']; ?>">
                <div class="category-card">
                    <img src="assets/images/category_icons/it.png" alt="category image">
                    <h3><?php echo $res['name']; ?></h3>
                    <p><?php echo $res['post_count']; ?> Jobs</p>
                </div>
            </a>
        <?php } ?>
    </div>
</section>
<!-- end page main content -->


<?php include 'layouts/footer.php'; ?>
<?php include 'layouts/layout_end.php'; ?>