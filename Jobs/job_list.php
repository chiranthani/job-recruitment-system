<!-- start page common elements -->
<?php
include '../layouts/layout_start.php';
include '../layouts/header.php';
include '../permission-check.php';

/* ===============
   DELETE JOB 
================== */

if (isset($_POST['delete_job_id'])) {

    $job_id = (int) $_POST['delete_job_id'];

    $countSql = "SELECT COUNT(*) AS total FROM applications WHERE job_id = ?";
    $stmt = $con_main->prepare($countSql);
    $stmt->bind_param("i", $job_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    if ($result['total'] > 0) {
        $_SESSION['error'] = "Cannot delete this job. Applications already exist.";
    }else{
           if ($job_id > 0) {
        $stmt = $con_main->prepare(
            "UPDATE job_posts 
             SET is_deleted = 1, deletedAt = NOW() 
             WHERE id = ?"
        );
        $stmt->bind_param("i", $job_id);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Job post deleted successfully.";
        } else {
            $_SESSION['error'] = "Failed to delete job post.";
        }
    }

    }

}
?>
<!-- end page common elements -->

<link rel="stylesheet" href="../assets/css/job-post.css">

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
            j.expiry_date,
            c.name AS category_name
        FROM job_posts j
        LEFT JOIN job_categories c ON j.category_id = c.id
        WHERE j.is_deleted = 0
          AND j.company_id = $companyId";

if (!empty($where)) {
    $sql .= " AND " . implode(" AND ", $where);
}

$sql .= " ORDER BY j.id DESC";

$result = $con_main->query($sql);

$user_id = $_SESSION['user_id'] ?? 0;
// get company details
$query = "SELECT c.*, u.email, u.first_name, u.last_name, u.last_login 
          FROM companies c 
          JOIN users u ON u.company_id = c.id 
          WHERE u.id = ?";
$stmt = $con_main->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result2 = $stmt->get_result();
$data = $result2->fetch_assoc();
$approval_status = $data['admin_approval'];
$is_active = $data['status'] == 1;
?>

<section class="job-list-wrapper">

<?php if (isset($_SESSION['success'])) { ?>
    <div class="alert success"><?= $_SESSION['success']; ?></div>
<?php unset($_SESSION['success']); } ?>

<?php if (isset($_SESSION['error'])) { ?>
    <div class="alert error"><?= $_SESSION['error']; ?></div>
<?php unset($_SESSION['error']); } ?>

<h2>Job Posts</h2>
<p class="subtitle">Manage your job postings</p>
    <?php if ($approval_status == 'PENDING'): ?>
            <div class="alert error">
                <strong>⏳ Verification Pending</strong><br>
                Your company profile is awaiting admin approval. You'll be able to post jobs once your account is verified.
            </div>
        <?php elseif ($approval_status == 'REJECTED'): ?>
            <div class="alert error">
                <strong>❌ Verification Rejected</strong><br>
                Your company profile verification was rejected. Please contact support for more information.
            </div>
        <?php endif; ?>

   <?php if (!$is_active && $approval_status == 'APPROVED'): ?>
        <div class="alert error">
            <strong>⚠️ Account Inactive</strong><br>
            Your account has been deactivated. Please contact support to reactivate your account.
        </div>
    <?php endif; ?>
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

  <a href="create_post.php"
         class="new-job-btn <?php echo ($approval_status == 'APPROVED' && $is_active == 1)  ? '' : 'disabled'; ?>" 
         <?php echo ($approval_status == 'APPROVED' && $is_active == 1) ? '' : 'onclick="return false;"'; ?>
          >New Job Post</a>
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
    <th>Expiry date</th>
    <th>Publish</th>
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
     <td><?= htmlspecialchars($row['expiry_date']); ?></td>
     <td>
                    <form method="POST" action="toggle_job_status.php">

                        <input type="hidden" name="job_id" value="<?= $row['id']; ?>">

                        <input type="hidden" name="status"
                                value="<?= ($row['post_status']=='published') ? 'draft' : 'published'; ?>">

                        <label class="switch">
                           <input type="checkbox"
                            class="<?= ($approval_status == 'APPROVED' && $is_active == 1) ? '' : 'disabled'; ?>"
                            <?= ($row['post_status'] == 'published') ? 'checked' : ''; ?>
                            <?= ($approval_status == 'APPROVED' && $is_active == 1) 
                                ? 'onchange="this.form.submit()"' 
                                : 'disabled'; ?>
                        >
                        <span class="slider"></span>
                    </label>

                </form>
            </td>

    <td class="action-buttons">
        <a href="job_view.php?job=<?= $row['id']; ?>" title="View">👁</a>
        <a href="job_edit.php?id=<?= $row['id']; ?>" title="Edit"
         class="<?php echo ($approval_status == 'APPROVED' && $is_active == 1)  ? '' : 'disabled'; ?>" 
         <?php echo ($approval_status == 'APPROVED' && $is_active == 1) ? '' : 'onclick="return false;"'; ?>
          >✏️</a>
        <a href="../job-applicant/application-overview.php?search=<?= urlencode($row['title']); ?>" title="Applications">📄</a>

        <form method="POST" style="display:inline;"
              onsubmit="return confirm('Delete this job post?');">
            <input type="hidden" name="delete_job_id" value="<?= $row['id']; ?>">
            <button type="submit" class="delete-btn" title="Delete"> ❌ </button>
        </form>
    </td>
</tr>
<?php } } else { ?>
<tr><td colspan="7">No job posts found</td></tr>
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
