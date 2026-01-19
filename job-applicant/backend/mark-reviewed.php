<?php
session_start();
require_once '../../config/database.php';
require_once '../../config/constants.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ids = $_POST['application_ids'] ?? [];
    $job_id = intval($_POST['job_id']) ?? 0;

    if (empty($ids)) {
        $msg = "Please select at least one application.";
        header("Location: ../applied-candidates.php?error=" . urlencode($msg) . "&job_id=" . urlencode($job_id));
        exit;
    }

    // sanitize IDs
    $idArray = array_map('intval', $ids);
    $placeholders = implode(',', array_fill(0, count($idArray), '?'));

    $status = AppConstants::APPLICATION_STATUS['IN_REVIEW'];
    $types = str_repeat('i', count($idArray)); // all ids are integers

    $sql = "UPDATE applications SET application_status = ? WHERE id IN ($placeholders)";
    $stmt = $con_main->prepare($sql);

    if (!$stmt) {
        $msg = "Database error: " . $con_main->error;
        header("Location: ../applied-candidates.php?error=" . urlencode($msg) . "&job_id=" . urlencode($job_id));
        exit;
    }

    // parameter bind dynamically
    $params = array_merge([$status], $idArray);
    $refs = [];
    foreach ($params as $key => $value) {
        $refs[$key] = &$params[$key];
    }

    $stmt->bind_param('s' . $types, ...$refs);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $msg = "Selected applications marked as Reviewed.";
        header("Location: ../applied-candidates.php?success=" . urlencode($msg) . "&job_id=" . urlencode($job_id));
    } else {
        $msg = "No applications were updated.";
        header("Location: ../applied-candidates.php?error=" . urlencode($msg) . "&job_id=" . urlencode($job_id));
    }

    $stmt->close();
    exit;

}
?>
