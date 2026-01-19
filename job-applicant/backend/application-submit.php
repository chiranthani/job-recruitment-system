<?php
session_start();
require_once '../../config/database.php';
require_once '../../config/constants.php';
require_once 'data-queries.php';
require_once '../../helpers/notifications.php';


try {
    $job_id = intval($_POST['job_id']);

    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        $msg = "Invalid request";
        header("Location: ../apply.php?error=" . urlencode($msg) . "&job=" . urlencode($job_id));
        exit;
    }


    $first = htmlspecialchars(strip_tags($_POST['first_name']));
    $last = htmlspecialchars(strip_tags($_POST['last_name']));
    $email = htmlspecialchars(strip_tags($_POST['email']));
    $phone = htmlspecialchars(strip_tags($_POST['phone']));
    $exp = htmlspecialchars(strip_tags($_POST['experience']));
    $role = htmlspecialchars(strip_tags($_POST['current_role']));
    $notice = htmlspecialchars(strip_tags($_POST['notice']));
    $cvOption = $_POST['cv_option'] ?? 'new';
    $full_name = $first . ' ' . $last;
    $user_id = $_SESSION['user_id'] ?? 0;

    $type = AppConstants::APPLIED_JOB;

    // duplicate check
    $check_result = getACandidateJob(intval($user_id),$job_id,$type);

    if ($check_result) {
        $msg = "You have already applied for this job.";
        header("Location: ../apply.php?error=" . urlencode($msg) . "&job=" . urlencode($job_id));
        exit;
    }

    // Upload file
    $resumePath = null;

    if ($cvOption == 'existing') {

        $row = getCandidateDetails($user_id);
        $resumePath = $row['cv_url'];

    } else {
      
        if (!isset($_FILES['resume']) || empty($_FILES['resume']['name'])) {
            $msg = "Resume required";
            header("Location: ../apply.php?error=" . urlencode($msg) . "&job=" . urlencode($job_id));
            exit;

        }

        if (!empty($_FILES['resume']['name'])) {

            $fileName = time() . "_" . basename($_FILES['resume']['name']);
            $target = "../../assets/uploads/resumes/" . $fileName;

            if (!move_uploaded_file($_FILES['resume']['tmp_name'], $target)) {
                $msg = "Failed to upload resume";
                header("Location: ../apply.php?error=" . urlencode($msg) . "&job=" . urlencode($job_id));
                exit;
            }

            $resumePath = "assets/uploads/resumes/" . $fileName;
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
        $msg = "Failed to apply: " . $main_stmt->error;
        header("Location: ../apply.php?error=" . urlencode($msg) . "&job=" . urlencode($job_id));
        exit;
    }

    // mark as applied job for candidate

    $cj_sql = "INSERT INTO candidate_jobs (user_id, job_id, `type`) VALUES (?, ?, ?)";
    $cj_stmt = $con_main->prepare($cj_sql);


    $cj_stmt->bind_param("iis", $user_id, $job_id, $type);

    if (!$cj_stmt->execute()) {
        $con_main->rollback();
        $msg = "Failed to mark candidate job: " . $cj_stmt->error;
        header("Location: ../apply.php?error=" . urlencode($msg) . "&job=" . urlencode($job_id));
        exit;
    }

    $con_main->commit();

    // send notification
    $jobData = getAJobPostDetails($job_id);
    $employers = getCompanyEmployerUsers($jobData['company_id']);

    foreach ($employers as $employer) {
        createNotification($con_main,$employer['id'],$user_id,$main_stmt->insert_id,AppConstants::NOTIFICATION_TYPES['0'],
        "New application received for '{$jobData['title']}' from {$full_name}.");
    }

    $msg = "Your application has been sent successfully.";
    header("Location: ../apply.php?success=" . urlencode($msg) . "&job=" . urlencode($job_id));
    exit;


} catch (Exception $e) {
    $con_main->rollback();

    $msg = "Something went wrong! " . $e->getMessage();
    header("Location: ../apply.php?error=" . urlencode($msg) . "&job=" . urlencode($job_id));
    exit;
}
