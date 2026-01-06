<?php
session_start();
include '../../config/database.php';
include '../../config/constants.php';

$userId = $_SESSION['user_id'] ?? 0;
$active = AppConstants::ACTIVE_STATUS;

if ($userId) {
    $stmt = $con_main->prepare("UPDATE notifications SET is_read = ? WHERE user_id = ?");
    $stmt->bind_param("ii",$active,$userId);
    $stmt->execute();
}

header('Content-Type: application/json');
echo json_encode(['status' => 'success']);
