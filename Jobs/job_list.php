<!-- start page common elements -->
<?php include '../config/database.php'; ?>
<?php include '../layouts/layout_start.php'; ?>
<?php include '../layouts/header.php'; ?>
<!-- end page common elements-->

<link rel="stylesheet" href="job-post.css">

<section class="job-list-wrapper">

    <h2>Job Posts</h2>
    <p class="subtitle">Manage your job postings</p>

    <!-- Top Row -->
    <div class="top-bar">

        <!-- Search -->
        <div class="search-wrapper">
            <input type="text" class="search-box" placeholder="Search by job title">
            <span class="search-icon">🔍</span>
        </div>

        <!-- New Job Post -->
        <a href="create_post.php" class="new-job-btn">New Job Post</a>
    </div>

    <!-- Filter Buttons -->
    <div class="filter-row">
        <button class="filter-btn">Category</button>
        <button class="filter-btn">Status</button>
        <button class="filter-btn">Job Type</button>
    </div>

    <!-- Job Table -->
    <table class="job-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Category</th>
                <th>Status</th>
                <th>Active/deactive</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>

            <!-- Example Row -->
            <tr>
                <td>1</td>
                <td>Developer I</td>
                <td>IT</td>

                <td>
                    <span class="status-badge published">Published</span>
                </td>

                <td>
                    <label class="switch">
                        <input type="checkbox" checked>
                        <span class="slider"></span>
                    </label>
                </td>

                <td class="action-buttons">
                    <a href="#" class="view-icon">👁</a>
                    <a href="#" class="edit-icon">✏️</a>
                </td>
            </tr>

        </tbody>
    </table>

</section>

<?php include '../layouts/footer.php'; ?>
<?php include '../layouts/layout_end.php'; ?>
