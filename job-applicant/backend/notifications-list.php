<?php
session_start();
include '../../config/database.php';
include 'data-queries.php';

$userId = $_SESSION['user_id'] ?? 0;

$notifications = getNotificationsList($userId,1,5);

header('Content-Type: application/json');
echo json_encode($notifications);
