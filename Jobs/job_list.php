<!-- start page common elements -->
<?php
include '../config/database.php';
include '../layouts/layout_start.php';
include '../layouts/header.php';
?>
<!-- end page common elements-->

<link rel="stylesheet" href="job-post.css">

<?php

/* =========================
   BUILD FILTER CONDITIONS
  ========================= */

/* Search by Job Title */
$where = [];

/* Search by Job Title */
if (!empty($_GET['search'])) {
    $search = $con_main->real_escape_string($_GET['search']);
    $where[] = "j.title LIKE '%$search%'";
}

/* Job Status filter */
if (!empty($_GET['job_status']) && $_GET['job_status'] !== 'all') {
    $job_status = $con_main->real_escape_string($_GET['job_status']);
    $where[] = "j.post_status = '$job_status'";
}

/* Category filter */
if (!empty($_GET['category']) && $_GET['category'] !== 'all') {
    $category = (int) $_GET['category'];
    $where[] = "j.category_id = $category";
}

/* Job Type filter */
if (!empty($_GET['job_type']) && $_GET['job_type'] !== 'all') {
    $job_type = $con_main->real_escape_string($_GET['job_type']);
    $where[] = "j.job_type = '$job_type'";
}

/* Final Query */
$sql = "SELECT 
        j.id,
        j.title,
        j.post_status,
        j.job_type,
        c.name AS category_name
    FROM job_posts j
    LEFT JOIN job_categories c ON j.category_id = c.id
    WHERE is_deleted = 0
";

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
?>

<section class="job-list-wrapper">

<?php if (isset($_SESSION['success'])) { ?>
    <div class="alert success">
        <?= $_SESSION['success']; ?>
    </div>
<?php unset($_SESSION['success']); } ?>

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
<form method="GET">
    
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
         class="new-job-btn <?php echo ($approval_status == 'APPROVED') ? '' : 'disabled'; ?>" 
         <?php echo ($approval_status == 'APPROVED') ? '' : 'onclick="return false;"'; ?>
          >New Job Post</a>
    </div>

    <!-- Filters -->
   
        <div class="filter-row">

            <div class="filter-item">
                <label>Job Status</label>
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
                    $cat_q = "SELECT * FROM job_categories WHERE status = 1";
                    $cat_r = $con_main->query($cat_q);
                    while ($cat = $cat_r->fetch_assoc()) {
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
                    <option value="Freelance" <?= (@$_GET['job_type']=='Freelance')?'selected':''; ?>>Freelance</option>
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
                <th>Publish</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
        <?php
        if ($result && $result->num_rows > 0) {
            $i = 1;
            while ($row = $result->fetch_assoc()) {
        ?>
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

                <td>
                    <label class="switch">
                         <input type="checkbox"
                                <?= ($row['post_status']=='published')?'checked':''; ?>
                                onchange="toggleStatus(<?= $row['id']; ?>, this)">
                    <span class="slider"></span>
                    </label>
                </td>

                <td class="action-buttons">
                    <a href="job_view.php?id=<?= $row['id']; ?>">👁</a>
                    <a href="job_edit.php?id=<?= $row['id']; ?>">✏️</a>
                </td>
            </tr>
        <?php
            }
        } else {
            echo "<tr><td colspan='6'>No job posts found</td></tr>";
        }
        ?>
        </tbody>

    </table>

</section>

<?php include '../layouts/footer.php'; ?>

<script>

/* ===============
   AUTO SEARCH 
================== */

let searchTimer;

document.getElementById('searchInput').addEventListener('keyup', function () {
    clearTimeout(searchTimer);

    searchTimer = setTimeout(() => {
        this.closest('form').submit();
    }, 500);
});


/* ===================
   TOGGLE JOB STATUS 
======================*/

function toggleStatus(jobId, checkbox) {
    let status = checkbox.checked ? 'published' : 'draft';

    fetch('toggle_job_status.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'job_id=' + jobId + '&status=' + status
    })
    .then(res => res.text())
    .then(result => {
        if (result !== 'success') {
            alert('Failed to update status');
            checkbox.checked = !checkbox.checked; 
        } else {
            location.reload();
        }
    });
}
</script>

<?php include '../layouts/layout_end.php'; ?>
