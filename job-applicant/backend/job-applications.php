<?php
include '../../config/database.php';
include '../../config/constants.php';
include '../../config/baseConfig.php';

$jobId   = (int)($_GET['job_id'] ?? 0);
$page    = max(1, (int)($_GET['page'] ?? 1));
$search  = $_GET['search'] ?? '';
$from    = $_GET['from'] ?? '';
$to      = $_GET['to'] ?? '';
$base_url = BaseConfig::$BASE_URL;
$limit  = 10;
$offset = ($page - 1) * $limit;

$where = " WHERE applications.job_id = ? ";
$params = [$jobId];
$types  = "i";

/* Search */
if ($search !== '') {
    $where .= " AND (applications.candidate_name LIKE ? OR applications.candidate_email LIKE ?) ";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $types .= "ss";
}

/* Date range */
if ($from && $to) {
    $where .= " AND DATE(applications.createdAt) BETWEEN ? AND ? ";
    $params[] = $from;
    $params[] = $to;
    $types .= "ss";
}

/* Count */
$countSql = "SELECT COUNT(*) total
    FROM applications
    INNER JOIN users ON users.id = applications.user_id
    $where
";
$stmt = $con_main->prepare($countSql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$total = (int)$stmt->get_result()->fetch_assoc()['total'];
$totalPages = ceil($total / $limit);

/* Data */
$sql = "SELECT
        applications.*,
        users.first_name,
        users.last_name
    FROM applications
    INNER JOIN users ON users.id = applications.user_id
    $where
    ORDER BY applications.createdAt DESC
    LIMIT ?, ?
";

$params[] = $offset;
$params[] = $limit;
$types .= "ii";

$stmt = $con_main->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();

$data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

echo json_encode([
    'data' => $data,
    'totalPages' => $totalPages,
    'base_url'=> $base_url
]);
