<?php
session_start();
include '../../config/database.php';
include '../../config/constants.php';
include 'data-queries.php';

header('Content-Type: application/json');

$applicationId = (int)($_POST['application_id'] ?? 0);
$status = trim($_POST['status']) ?? '';
$interviewDate = $_POST['interview_date'] ?? null;
$isCandidate = $_SESSION['role_id'] == AppConstants::ROLE_JOB_SEEKER ? true : false;

$applicationData = getAApplicationDetails($applicationId);

/* common validation */
if (!$applicationId || !in_array($status, AppConstants::APPLICATION_STATUS)) {
    echo json_encode(["status" => "error", "message" => "Invalid data"]);
    exit;
}

/** recuriter validations */
if(!$isCandidate){
    if ($status == AppConstants::APPLICATION_STATUS['INTERVIEW'] && empty($interviewDate)) {
        echo json_encode(["status" => "error", 'message' => 'Interview date required']);
        exit;
    }

    $newIndex = array_search($status, AppConstants::APPLICATION_STATUS_FLOW);

    /* new status reverse update block [to applied] */
    if (0 == $newIndex) {
        echo json_encode(["status" => "error",'message' => 'Reverse status [Applied] update is not allowed','newIndex'=>$newIndex]);
        exit;
    }
}else{
    /** candidate validation */
    if (!in_array($status, [AppConstants::APPLICATION_STATUS['OFFER_ACCEPTED'],AppConstants::APPLICATION_STATUS['OFFER_RJECTED']])) {
        echo json_encode(["status" => "error", "message" => "Invalid status"]);
        exit;
    }
}


/* update */
$sql = "UPDATE applications
    SET
        application_status = ?,
        interview_at = ?,
        updatedAt = NOW()
    WHERE id = ?
";

$stmt = $con_main->prepare($sql);
$stmt->bind_param(
    "ssi",
    $status,
    $interviewDate,
    $applicationId
);

if ($stmt->execute()) {
    echo json_encode(["status" => "success",'message'=>"Status updated successfully!"]);
} else {
    echo json_encode(["status" => "error",  'message' => 'Update error']);
}
