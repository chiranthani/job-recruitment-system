<?php
include '../../config/database.php';
include '../../config/constants.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$company = isset($_GET['company']) ? trim($_GET['company']) : '';
$work_type = isset($_GET['work_type']) ? trim($_GET['work_type']) : '';
$categories = isset($_GET['categories']) ? $_GET['categories'] : [];
$page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;

$limit = 8;
$offset = ($page - 1) * $limit;

$query = "SELECT
    job_posts.id,
    job_posts.title,
    job_posts.category_id,
    job_posts.company_id,
    job_posts.work_type,
    job_posts.job_type,
    job_posts.description,
    job_posts.expiry_date,
    companies.name AS company_name,
    locations.name AS location_name,
    job_categories.name AS category_name
FROM
    `job_posts`
INNER JOIN companies ON companies.id = job_posts.company_id
INNER JOIN job_categories ON job_categories.id = job_posts.category_id
INNER JOIN locations ON locations.id = job_posts.location_id
WHERE
    job_posts.active_status = ".AppConstants::ACTIVE_STATUS." AND job_posts.is_deleted = ".AppConstants::INACTIVE_STATUS." AND 
    job_posts.expiry_date >= CURRENT_DATE()";

if ($search !== '') {
    $query .= " AND job_posts.title LIKE '%" . mysqli_real_escape_string($con_main, $search) . "%'";
}

if ($company != 'all' && $company != '') {
    $query .= " AND job_posts.company_id = '" . mysqli_real_escape_string($con_main, $company) . "'";
}
if ($work_type != 'all' && $work_type != '') {
    $query .= " AND job_posts.work_type = '" . mysqli_real_escape_string($con_main, $work_type) . "'";
}

if (!empty($categories)) {
    $categories_sql = "'" . implode("','", array_map(fn($v) => mysqli_real_escape_string($con_main, $v), $categories)) . "'";
    $query .= " AND job_posts.category_id IN ($categories_sql)";
}


$query .= " ORDER BY job_posts.id DESC";

$total_result = mysqli_query($con_main, $query);
$total_records = mysqli_num_rows($total_result);
$total_pages = ceil($total_records / $limit);

// pagination add and fetch
$query .= " LIMIT $offset, $limit";
$final_result = mysqli_query($con_main, $query);
$jobs = [];

while ($row = mysqli_fetch_assoc($final_result)) {
    $jobs[] = $row;
}

echo json_encode([
    'jobs' => $jobs,
    'page' => $page,
    'total_pages' => $total_pages
]);
exit;
