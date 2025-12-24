<?php
include '../../config/database.php';
include '../../config/constants.php';
include 'data-queries.php';

header("Content-Type: application/json");

try {
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        echo json_encode(["status" => "error", "message" => "Invalid request"]);
        exit;
    }

    $job_id = intval($_POST['job_id']);
    $first = htmlspecialchars(strip_tags($_POST['first_name']));
    $last = htmlspecialchars(strip_tags($_POST['last_name']));
    $email = htmlspecialchars(strip_tags($_POST['email']));
    $phone = htmlspecialchars(strip_tags($_POST['phone']));
    $exp = htmlspecialchars(strip_tags($_POST['experience']));
    $role = htmlspecialchars(strip_tags($_POST['current_role']));
    $notice = htmlspecialchars(strip_tags($_POST['notice']));
    $cvOption = $_POST['cv_option'] ?? 'new';
    $full_name = $first . ' ' . $last;
    $user_id = $_SESSION['user_id'] ?? 4;
    $type = AppConstants::APPLIED_JOB;

    // duplicate check
    $check_result = getACandidateJob(intval($user_id),$job_id,$type);

    if ($check_result) {
        echo json_encode([
            "status" => "error",
            "message" => "You have already applied for this job."
        ]);
        exit;
    }

    // Upload file
    $resumePath = null;

    if ($cvOption == 'existing') {

        $row = getCandidateDetails($user_id);
        $resumePath = $row['cv_url'];

    } else {
      
        if (!isset($_FILES['resume'])) {
            echo json_encode(["status"=>"error","message"=>"Resume required"]);
            exit;
        }

        if (!empty($_FILES['resume']['name'])) {

            $fileName = time() . "_" . basename($_FILES['resume']['name']);
            $target = "../../assets/uploads/resumes/" . $fileName;

            if (!move_uploaded_file($_FILES['resume']['tmp_name'], $target)) {
                echo json_encode(["status" => "error", "message" => "Failed to upload resume"]);
                exit;
            }

            $resumePath = "uploads/resumes/" . $fileName;
        }

    }

    $con_main->begin_transaction();

    // insert to main applications
    $main_sql = "INSERT INTO applications
              (`job_id`, `user_id`, `candidate_name`, `contact_number`, `candidate_email`,`experience`, `current_role`, `cv_url`, `notice_period`, `applied_at`) VALUES (?,?,?,?,?,?,?,?,?,NOW())";

    $main_stmt = $con_main->prepare($main_sql);

    $main_stmt->bind_param(
        "iisssssss", 
        $job_id,
        $user_id,
        $full_name,
        $phone,
        $email,
        $exp,
        $role,
        $resumePath,
        $notice
    );

    if (!$main_stmt->execute()) {
        $con_main->rollback();
        echo json_encode(["status" => "error", "message" => "Failed to apply", "data" => $main_stmt->error]);
        exit;
    }

    // mark as applied job for candidate

    $cj_sql = "INSERT INTO candidate_jobs (user_id, job_id, `type`) VALUES (?, ?, ?)";
    $cj_stmt = $con_main->prepare($cj_sql);


    $cj_stmt->bind_param("iis", $user_id, $job_id, $type);

    if (!$cj_stmt->execute()) {
        $con_main->rollback();
        echo json_encode(["status" => "error", "message" => "Failed to mark candidate job", "data" => $cj_stmt->error]);
        exit;
    }

    $con_main->commit();

    echo json_encode([
        "status" => "success",
        "message" => "Your application has been sent successfully."
    ]);
    exit;


} catch (Exception $e) {
    $con_main->rollback();

    echo json_encode(["status" => "error","message" =>"Something went wrong!", "data" => $e->getMessage()]);
    exit;
}
