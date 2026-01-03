<?php
include '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $job_id = (int) $_POST['job_id'];
    $status = $_POST['status'];

    if (!in_array($status, ['draft', 'published'])) {
        echo 'invalid';
        exit;
    }

    $sql = "UPDATE job_posts 
            SET post_status = ?,
                published_date = IF(? = 'published', NOW(), NULL)
            WHERE id = ?";

    $stmt = $con_main->prepare($sql);
    $stmt->bind_param("ssi", $status, $status, $job_id);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
}
