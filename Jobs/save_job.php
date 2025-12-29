<?php
session_start();
include '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $job_status   = $_POST['job_status'];
    $deadline     = $_POST['deadline'];
    $job_title    = $_POST['job_title'];
    $category_id  = $_POST['category'];
    $job_type     = $_POST['job_type'];
    $work_type    = $_POST['work_type'];
    $description  = $_POST['description'];
    $requirements = $_POST['requirements'];
    $location_id  = $_POST['location_id'];
    $company_id = $_SESSION['company_id'] ?? 0;
    $user_id = $_SESSION['user_id'] ?? 0;
 
    $publishedDate = null;

    if($job_status ==  'published'){
        $publishedDate = date('Y-m-d');
    }
    //  Insert job post
    $sql = "INSERT INTO job_posts 
            (company_id, post_status, `expiry_date`, title, category_id, job_type, work_type, `description`, requirements, location_id, created_by,published_date)
            VALUES 
            (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $con_main->prepare($sql);
    $stmt->bind_param(
        "isssissssiis",
        $company_id,
        $job_status,
        $deadline,
        $job_title,
        $category_id,
        $job_type,
        $work_type,
        $description,
        $requirements,
        $location_id,
        $user_id,
        $publishedDate
    );

    if ($stmt->execute()) {

        $job_post_id = $stmt->insert_id;

        //  Insert benefits
        if (!empty($_POST['benefits'])) {
            foreach ($_POST['benefits'] as $benefit_id) {
                $sqlBenefit = "INSERT INTO job_post_benefits (job_post_id, benefit_id)
                               VALUES (?, ?)";
                $stmtBenefit = $con_main->prepare($sqlBenefit);
                $stmtBenefit->bind_param("ii", $job_post_id, $benefit_id);
                $stmtBenefit->execute();
            }
        }

        //  Redirect
        $_SESSION['success'] = "Job post created successfully.";
        header("Location: job_list.php");
        exit;

   } else {
    $_SESSION['error'] = "Error saving job post. Please try again.";
    header("Location: create_post.php");
    exit;
    }

}
