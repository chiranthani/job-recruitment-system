<!-- start page common elements -->
<?php include '../config/database.php'; ?>
<?php include '../layouts/layout_start.php'; ?>
<?php include '../layouts/header.php'; ?>
<!-- end page common elements-->

<link rel="stylesheet" href="job-post.css">

<section class="page-wrapper job-view-container">

    <div class="job-header">
        <div>
            <div class="job-title">Developer 1</div>
            <div class="job-company">Jobs XX</div>

            <div class="job-meta">
                <div class="meta-item">📍 Colombo</div>
                <div class="meta-item">🧰 Full-Time</div>
            </div>
        </div>

        <div style="text-align:right; color:#005ec4;">
            ⏰ 5 days left
        </div>
    </div>

    <!-- Description -->
    <div class="section-title">Description:</div>
    <div class="job-description">
        Lorem Ipsum...
    </div>

    <!-- Requirements -->
    <div class="section-title">Requirements</div>
    <div class="job-requirements">
        ...
    </div>

    <!-- Benefits -->
    <div class="section-title">Benefits:</div>
    <div class="job-benefits">
        • Competitive salary <br>
        • Health insurance <br>
        • Performance-based bonuses
    </div>

    <!-- Apply Section -->
    <div class="apply-box">
        PLEASE CLICK THE APPLY BUTTON TO SEND YOUR DETAILS VIA JOBS XX
        <br>
        <button class="apply-button">Apply for Job</button>
    </div>


</section>

<?php include '../layouts/footer.php'; ?>
<?php include '../layouts/layout_end.php'; ?>
