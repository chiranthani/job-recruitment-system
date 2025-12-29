<?php
session_start();
include '../../config/database.php';
include '../../config/constants.php';

header('Content-Type: application/json');

$userId = $_SESSION['user_id'] ?? 0;
$status = $_POST['status'] ?? 'ALL';
$search = trim($_POST['search'] ?? '');

$sql = "SELECT 
        a.id,
        jp.title,
        c.name AS company_name,
        a.job_id,
        a.application_status,
        a.applied_at
    FROM applications a
    INNER JOIN job_posts jp ON jp.id = a.job_id
    INNER JOIN companies c ON c.id = jp.company_id
    WHERE a.user_id = ?
";

$params = [$userId];
$types  = "i";

if ($status !== 'ALL') {
    $sql .= " AND a.application_status = ?";
    $params[] = $status;
    $types .= "s";
}

if ($search !== '') {
    $sql .= " AND (jp.title LIKE ? OR c.name LIKE ?)";
    $searchTerm = "%{$search}%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $types .= "ss";
}

$sql .= " ORDER BY a.createdAt DESC";

$stmt = $con_main->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();

$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode([
    'applications' => $data,
    'userId' => $userId
]);
