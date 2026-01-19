<?php
session_start();
require_once '../../config/database.php';
require_once 'data-queries.php';

$userId = $_SESSION['user_id'] ?? 0;

$notifications = getNotificationsList($userId,1,5);

header('Content-Type: application/json');
echo json_encode($notifications);
