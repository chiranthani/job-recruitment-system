<?php 
/**
 * User Management System - Admin User List View
 * Includes filtering by Role, Status, Search, and Date Range with Pagination.
 */

include '../config/database.php';

// --- 1. CAPTURE FILTER INPUTS FROM URL (GET REQUESTS) ---
// Capture User Role (e.g., Job Seekers = 1)
$role_filter   = isset($_GET['role']) ? (int)$_GET['role'] : 0;

// Capture Account Status (Active = 1, Inactive = 0)
$status_filter = isset($_GET['status']) ? $_GET['status'] : ''; 

// Capture Search Text and sanitize to prevent SQL Injection
$search_query  = isset($_GET['search']) ? mysqli_real_escape_string($con_main, $_GET['search']) : '';

// Capture Date Range (Last 7 or 30 days)
$date_range    = isset($_GET['date_range']) ? (int)$_GET['date_range'] : 0;


// --- 2. BUILD THE DYNAMIC SQL FILTER STRING ---
// Start with a base condition (only show non-deleted users)
$filter_sql = " WHERE is_deleted = 0";

// Append Role filter if selected
if ($role_filter > 0) {
    $filter_sql .= " AND role_id = $role_filter";
}

// Append Status filter if selected (check for empty string to allow '0' as a value)
if ($status_filter !== '') {
    $filter_sql .= " AND status = " . (int)$status_filter;
}

// Append Search filter (Searches across First Name, Last Name, and Email)
if ($search_query !== '') {
    $filter_sql .= " AND (first_name LIKE '%$search_query%' OR last_name LIKE '%$search_query%' OR email LIKE '%$search_query%')";
}

// Append Date Range filter (Uses reg_date column)
if ($date_range > 0) {
    $filter_sql .= " AND reg_date >= DATE_SUB(NOW(), INTERVAL $date_range DAY)";
}


// --- 3. PAGINATION LOGIC ---
$limit = 5; // Maximum records per table view
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit; // Calculate starting point for the SQL query


// --- 4. EXECUTE TOTAL COUNT QUERY ---
// Needed to calculate how many pages exist in total
$total_query = "SELECT COUNT(id) AS total FROM users $filter_sql";
$total_result = $con_main->query($total_query);

if (!$total_result) {
    die("Database Error: " . $con_main->error);
}

$total_rows = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit); // Round up to nearest whole page
if($total_pages < 1) $total_pages = 1;


// --- 5. FETCH FINAL DATASET ---
// Fetches only the 5 records needed for the current page
$query = "SELECT id, first_name, last_name, email, role_id, status FROM users $filter_sql LIMIT $limit OFFSET $offset";
$result = $con_main->query($query);

include '../layouts/layout_start.php'; 
?>

<title>User List View</title>
<link rel="stylesheet" href="style.css">

<?php include '../layouts/header.php'; ?>

<div class="container" style="max-width: 1000px;">
    <h1 style="text-align: left;">User List View</h1>

    <div class="d-flex justify-between mt-20" style="border-bottom: 2px dashed var(--ink-color); padding-bottom: 20px; margin-bottom: 20px;">
        <div style="flex: 2; margin-right: 20px; display: flex; align-items: center; border: 2px solid var(--ink-color); padding: 5px; border-radius: 10px;">
            <span style="font-size: 1.5rem; margin-right: 10px;">🔍</span>
            <input type="text" id="searchInput" placeholder="Search by Name, Email or ID" style="border: none; flex: 1;" 
                   value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" oninput="liveSearch()">
        </div>
        <a href="../admin_user_form.php" class="btn-add" style="text-decoration: none;">+ Add New User</a>
    </div>

    <div class="d-flex justify-between" style="align-items: flex-end;">
        <div>
            <a href="admin_user_list.php" class="btn-add" <?php echo ($role_filter == 0) ?>;>All Users</a>
            <a href="admin_user_list.php?role=1" class="btn-add" <?php echo ($role_filter == 1)?>;>Job Seekers</a>
        </div>

        <div class="d-flex" style="gap: 20px;">
            <div class="form-group" style="margin-bottom: 0;">
                <label>Status</label>
                <select id="statusFilter" name="status" onchange="filterByStatus()" style="border: 2px solid var(--ink-color); padding: 8px; border-radius: 5px; width: 100%;">
                    <option value="" <?php echo ($status_filter === '') ? 'selected' : ''; ?>>All Status</option>
                    <option value="1" <?php echo ($status_filter === '1') ? 'selected' : ''; ?>>Active</option>
                    <option value="0" <?php echo ($status_filter === '0') ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label>Date Range</label>
                <select id="dateFilter" onchange="filterByDate()" style="border: 2px solid var(--ink-color); padding: 8px; border-radius: 5px;">
                    <option value="" <?php echo ($date_range == 0) ? 'selected' : ''; ?>>All Time</option>
                    <option value="7" <?php echo ($date_range == 7) ? 'selected' : ''; ?>>Last 7 Days</option>
                    <option value="30" <?php echo ($date_range == 30) ? 'selected' : ''; ?>>Last 30 Days</option>
                </select>
            </div>
        </div>
    </div>
    
    <table class="admin-table">
        <thead>
            <tr>
                <th>User ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 12px;">#<?php echo $row['id']; ?></td>
                        <td style="padding: 12px;"><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
                        <td style="padding: 12px;"><?php echo $row['email']; ?></td>
                        <td style="padding: 12px;">
                            <?php 
                                if($row['role_id'] == 1) echo "Candidate";
                                elseif($row['role_id'] == 2) echo "Recruiter";
                                elseif($row['role_id'] == 3) echo "Admin";
                            ?>
                        </td>
                        <td style="padding: 12px;">
                            <?php echo ($row['status'] == 1) ? '<span style="color: green;">Active</span>' : '<span style="color: red;">Inactive</span>'; ?>
                        </td>
                        <td style="padding: 12px;">
                            <a href="admin_user_form.php?id=<?php echo $row['id']; ?>&mode=view" style="color: blue; text-decoration: none;">View</a> |  
                            <a href="admin_user_form.php?id=<?php echo $row['id']; ?>" style="color: orange; text-decoration: none;">Update</a> | 
                            <a href="admin_user_form.php?id=<?php echo $row['id']; ?>" style="color: red; text-decoration: none;" onclick="return confirm('Delete this user?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6" style="padding: 20px; text-align: center;">No users found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    
    <div class="d-flex mt-20" style="justify-content: flex-end; gap: 10px; align-items: center;">
        <span style="margin-right: 10px; font-size: 14px; color: #666;">
            Page <?php echo $page; ?> of <?php echo $total_pages; ?>
        </span>

        <?php if ($page > 1): ?>
            <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page - 1])); ?>" class="btn-update" style="text-decoration: none;">Previous</a>
        <?php else: ?>
            <button class="btn-update" style="opacity: 0.5; cursor: not-allowed;" disabled>Previous</button>
        <?php endif; ?>

        <?php if ($page < $total_pages): ?>
            <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page + 1])); ?>" class="btn-add" style="text-decoration: none;">Next</a>
        <?php else: ?>
            <button class="btn-add" style="opacity: 0.5; cursor: not-allowed;" disabled>Next</button>
        <?php endif; ?>
    </div>
    
</div>

<script src="script.js"></script>

<?php include '../layouts/footer.php'; ?>
<?php include '../layouts/layout_end.php'; ?>