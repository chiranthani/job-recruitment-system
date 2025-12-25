<?php
include '../../config/database.php';
include '../../config/constants.php';

$page = max(1, (int)($_GET['page'] ?? 1));
$search = $_GET['search'] ?? '';
$postStatus = AppConstants::POST_PUBLISHED;

$limit = 10;
$offset = ($page - 1) * $limit;
$searchLike = "%$search%";

$where = " WHERE post_status = ? ";
$hasSearch = !empty($search);

if ($hasSearch) {
    $where .= " AND job_posts.title LIKE ? ";
}

/* COUNT */
$countSql = "SELECT COUNT(*) AS total FROM job_posts" . $where;
$stmt = $con_main->prepare($countSql);

if ($hasSearch) {
    $stmt->bind_param("ss", $postStatus, $searchLike);
} else {
    $stmt->bind_param("s", $postStatus);
}

$stmt->execute();
$totalJobs = (int)$stmt->get_result()->fetch_assoc()['total'];
$totalPages = max(1, ceil($totalJobs / $limit));

/* DATA */
$sql = "SELECT 
    job_posts.id,
    job_posts.title,
    COUNT(applications.id) AS total,
    COALESCE(SUM(applications.application_status='Applied'), 0) AS new,
    COALESCE(SUM(applications.application_status='In Review'), 0) AS reviewed,
    COALESCE(SUM(applications.application_status='Rejected'), 0) AS rejected,
    COALESCE(SUM(applications.application_status='Interview'), 0) AS interview,
    COALESCE(SUM(applications.application_status='Offer Made'), 0) AS offer,
    COALESCE(SUM(applications.application_status='Hired'), 0) AS hired
FROM job_posts
LEFT JOIN applications ON applications.job_id = job_posts.id
$where
GROUP BY job_posts.id
LIMIT ?, ?
";

$stmt = $con_main->prepare($sql);

if ($hasSearch) {
    $stmt->bind_param("ssii", $postStatus, $searchLike, $offset, $limit);
} else {
    $stmt->bind_param("sii", $postStatus, $offset, $limit);
}

$stmt->execute();
$jobs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

echo json_encode([
    'jobs' => $jobs,
    'totalPages' => $totalPages
]);
