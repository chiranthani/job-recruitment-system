<!-- start page common elements -->
<?php
include '../config/database.php';
include '../layouts/layout_start.php';
include '../layouts/header.php';
include '../permission-check.php';

/* =========================
   DELETE JOB (SAME FILE)
========================= */

if (isset($_POST['delete_job_id'])) {

    $job_id = (int) $_POST['delete_job_id'];

    if ($job_id > 0) {
        $stmt = $con_main->prepare(
            "UPDATE job_posts 
             SET is_deleted = 1, active_status = 0 
             WHERE id = ?"
        );
        $stmt->bind_param("i", $job_id);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Job post deleted successfully.";
        } else {
            $_SESSION['error'] = "Failed to delete job post.";
        }
    }

    header("Location: job_list.php");
    exit;
}
?>
<!-- end page common elements -->

<link rel="stylesheet" href="job-post.css">

<?php
/* =========================
   BUILD FILTER CONDITIONS
========================= */

$where = [];

/* Search */
if (!empty($_GET['search'])) {
    $search = $con_main->real_escape_string($_GET['search']);
    $where[] = "j.title LIKE '%$search%'";
}

/* Job Status */
if (!empty($_GET['job_status']) && $_GET['job_status'] !== 'all') {
    $status = $con_main->real_escape_string($_GET['job_status']);
    $where[] = "j.post_status = '$status'";
}

/* Category */
if (!empty($_GET['category']) && $_GET['category'] !== 'all') {
    $category = (int) $_GET['category'];
    $where[] = "j.category_id = $category";
}

/* Job Type */
if (!empty($_GET['job_type']) && $_GET['job_type'] !== 'all') {
    $type = $con_main->real_escape_string($_GET['job_type']);
    $where[] = "j.job_type = '$type'";
}

$companyId = $_SESSION['company_id'] ?? 0;

/* =========================
   FINAL QUERY
========================= */

$sql = "SELECT 
            j.id,
            j.title,
            j.post_status,
            j.job_type,
            c.name AS category_name
        FROM job_posts j
        LEFT JOIN job_categories c ON j.category_id = c.id
        WHERE j.is_deleted = 0
          AND j.active_status = 1
          AND j.company_id = $companyId";

if (!empty($where)) {
    $sql .= " AND " . implode(" AND ", $where);
}

$sql .= " ORDER BY j.id DESC";

$result = $con_main->query($sql);
?>

<section class="job-list-wrapper">

<?php if (isset($_SESSION['success'])) { ?>
    <div class="alert success"><?= $_SESSION['success']; ?></div>
<?php unset($_SESSION['success']); } ?>

<h2>Job Posts</h2>
<p class="subtitle">Manage your job postings</p>

<form method="GET" id="filterForm">

<!-- Top Bar -->
<div class="top-bar">

    <div class="search-wrapper">
        <input type="text"
               id="searchInput"
               name="search"
               class="search-box"
               placeholder="Search by job title"
               value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        <span class="search-icon">🔍</span>

        <?php if (!empty($_GET['search'])) { ?>
            <a href="job_list.php" class="clear-search-btn">✖</a>
        <?php } ?>
    </div>

    <a href="create_post.php" class="new-job-btn">New Job Post</a>
</div>

<!-- Filters -->
<div class="filter-row">

    <div class="filter-item">
        <label>Status</label>
        <select name="job_status" class="filter-select" onchange="this.form.submit()">
            <option value="all">All</option>
            <option value="draft" <?= (@$_GET['job_status']=='draft')?'selected':''; ?>>Draft</option>
            <option value="published" <?= (@$_GET['job_status']=='published')?'selected':''; ?>>Published</option>
        </select>
    </div>

    <div class="filter-item">
        <label>Category</label>
        <select name="category" class="filter-select" onchange="this.form.submit()">
            <option value="all">All</option>
            <?php
            $cats = $con_main->query("SELECT * FROM job_categories WHERE status=1");
            while ($cat = $cats->fetch_assoc()) {
            ?>
                <option value="<?= $cat['id']; ?>"
                    <?= (@$_GET['category']==$cat['id'])?'selected':''; ?>>
                    <?= $cat['name']; ?>
                </option>
            <?php } ?>
        </select>
    </div>

    <div class="filter-item">
        <label>Job Type</label>
        <select name="job_type" class="filter-select" onchange="this.form.submit()">
            <option value="all">All</option>
            <option value="Full-Time" <?= (@$_GET['job_type']=='Full-Time')?'selected':''; ?>>Full Time</option>
            <option value="Part-Time" <?= (@$_GET['job_type']=='Part-Time')?'selected':''; ?>>Part Time</option>
            <option value="Internship" <?= (@$_GET['job_type']=='Internship')?'selected':''; ?>>Intern</option>
            <option value="Contract" <?= (@$_GET['job_type']=='Contract')?'selected':''; ?>>Contract</option>
        </select>
    </div>

</div>
</form>

<!-- Job Table -->
<table class="job-table">
<thead>
<tr>
    <th>#</th>
    <th>Title</th>
    <th>Category</th>
    <th>Type</th>
    <th>Status</th>
    <th>Actions</th>
</tr>
</thead>

<tbody>
<?php if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) { ?>
<tr>
    <td><?= $row['id']; ?></td>
    <td><?= htmlspecialchars($row['title']); ?></td>
    <td><?= htmlspecialchars($row['category_name']); ?></td>
    <td><?= htmlspecialchars($row['job_type']); ?></td>
    <td>
        <span class="status-badge <?= $row['post_status']; ?>">
            <?= ucfirst($row['post_status']); ?>
        </span>
    </td>

    <td class="action-buttons">
        <a href="job_view.php?job=<?= $row['id']; ?>" title="View">👁</a>
        <a href="job_edit.php?id=<?= $row['id']; ?>" title="Edit">✏️</a>
        <a href="../job-applicant/application-overview.php?search=<?= urlencode($row['title']); ?>" title="Applications">📄</a>

        <form method="POST" style="display:inline;"
              onsubmit="return confirm('Delete this job post?');">
            <input type="hidden" name="delete_job_id" value="<?= $row['id']; ?>">
            <button type="submit" class="delete-btn" title="Delete"> ❌ </button>
        </form>
    </td>
</tr>
<?php } } else { ?>
<tr><td colspan="6">No job posts found</td></tr>
<?php } ?>
</tbody>
</table>

</section>

<?php include '../layouts/footer.php'; ?>

<script>

/* AUTO SEARCH (NO ENTER KEY) */
let searchTimer = null;
const searchInput = document.getElementById('searchInput');
const filterForm  = document.getElementById('filterForm');

if (searchInput && filterForm) {
    searchInput.addEventListener('input', function () {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => {
            filterForm.submit();
        }, 400);
    });
}
</script>

<?php include '../layouts/layout_end.php'; ?>
