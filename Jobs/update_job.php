<?php
session_start();
include '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: job_list.php");
    exit;
}

/* =========================
   GET FORM DATA
========================= */

$job_id       = (int) $_POST['job_id'];
$job_status   = $_POST['job_status'];
$deadline     = $_POST['deadline'];
$job_title    = trim($_POST['job_title']);
$category_id  = (int) $_POST['category'];
$job_type     = $_POST['job_type'];
$work_type    = $_POST['work_type'];
$description  = trim($_POST['description']);
$requirements = trim($_POST['requirements']);
$location_id  = (int) $_POST['location_id'];
$benefits     = $_POST['benefits'] ?? [];

/* =========================
   BASIC VALIDATION
========================= */

if (
    !$job_id ||
    empty($job_title) ||
    empty($job_status) ||
    empty($category_id) ||
    empty($job_type) ||
    empty($work_type) ||
    empty($description) ||
    empty($requirements) ||
    empty($location_id)
) {
    $_SESSION['error'] = "Please fill all required fields.";
    header("Location: job_edit.php?id=$job_id");
    exit;
}

/* =========================
   UPDATE JOB POST
========================= */

$sql = "UPDATE job_posts SET
            post_status = ?,
            expiry_date = ?,
            title = ?,
            category_id = ?,
            job_type = ?,
            work_type = ?,
            description = ?,
            requirements = ?,
            location_id = ?
        WHERE id = ?";

$stmt = $con_main->prepare($sql);
$stmt->bind_param(
    "sssissssii",
    $job_status,
    $deadline,
    $job_title,
    $category_id,
    $job_type,
    $work_type,
    $description,
    $requirements,
    $location_id,
    $job_id
);

if (!$stmt->execute()) {
    $_SESSION['error'] = "Failed to update job post.";
    header("Location: job_edit.php?id=$job_id");
    exit;
}

/* =========================
   UPDATE BENEFITS
========================= */

/* Delete old benefits */
$del = $con_main->prepare("DELETE FROM job_post_benefits WHERE job_post_id = ?");
$del->bind_param("i", $job_id);
$del->execute();

/* Insert new benefits */
if (!empty($benefits)) {
    $ins = $con_main->prepare(
        "INSERT INTO job_post_benefits (job_post_id, benefit_id) VALUES (?, ?)"
    );
    foreach ($benefits as $benefit_id) {
        $benefit_id = (int) $benefit_id;
        $ins->bind_param("ii", $job_id, $benefit_id);
        $ins->execute();
    }
}

/* =========================
   SUCCESS
========================= */

$_SESSION['success'] = "Job post updated successfully.";
header("Location: job_list.php");
exit;
