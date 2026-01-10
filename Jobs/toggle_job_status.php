<?php
session_start();
include '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: job_list.php");
    exit;
}

$job_id = (int) $_POST['job_id'];
$status = $_POST['status'];

/* Validate status */
if (!in_array($status, ['draft', 'published'])) {
    $_SESSION['error'] = "Invalid job status.";
    header("Location: job_list.php");
    exit;
}

/* Set published date */
$publishedDate = ($status === 'published') ? date('Y-m-d') : null;

/* Update job status */
$sql = "UPDATE job_posts
        SET post_status = ?, published_date = ?
        WHERE id = ?";

$stmt = $con_main->prepare($sql);
$stmt->bind_param("ssi", $status, $publishedDate, $job_id);

if ($stmt->execute()) {
    $_SESSION['success'] = "Job status updated successfully.";
} else {
    $_SESSION['error'] = "Failed to update job status.";
}

/* Redirect back */
header("Location: job_list.php");
exit;
