<?php
session_start();
require_once '../../config/database.php';
require_once 'data-queries.php';

$userId = $_SESSION['user_id'] ?? 0;
$count = getUnreadNotificationCount($userId);

header('Content-Type: application/json');
echo json_encode(['count' => $count]);
