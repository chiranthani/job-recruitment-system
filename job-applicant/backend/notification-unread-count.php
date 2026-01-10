<?php
session_start();
include '../../config/database.php';
include 'data-queries.php';

$userId = $_SESSION['user_id'] ?? 0;
$count = getUnreadNotificationCount($userId);

header('Content-Type: application/json');
echo json_encode(['count' => $count]);
