<?php
session_start();
include '../../config/database.php';
include '../../config/constants.php';
include 'data-queries.php';

// helper function
function redirectBack($path,$type,$message){
    header("Location: {$path}?{$type}=" . urlencode($message));
    exit;
}

$applicationId = filter_input(INPUT_POST, 'application_id', FILTER_VALIDATE_INT) ?? 0;
$status = trim($_POST['status'] ?? '');
$interviewDate = $_POST['interview_date'] ?? null;
$isCandidate = $_SESSION['role_id'] == AppConstants::ROLE_JOB_SEEKER ? true : false;

$redirectPath = $isCandidate ? '../my-jobs.php' : '../applied-candidates.php';

$applicationData = getAApplicationDetails($applicationId);

/* common validation */
if ($applicationId <= 0 || !in_array($status, AppConstants::APPLICATION_STATUS, true)) {
    redirectBack($redirectPath, 'error', 'Invalid request data');
}

if (!$applicationData) {
    redirectBack($redirectPath, 'error', 'Application not found');
}

/** recuriter validations */
if(!$isCandidate){
    if ($status == AppConstants::APPLICATION_STATUS['INTERVIEW'] && empty($interviewDate)) {
        redirectBack($redirectPath, 'error', 'Interview date is required');
    }

    $newIndex = array_search($status, AppConstants::APPLICATION_STATUS_FLOW);

    /* new status reverse update block [to applied] */
     if ($newIndex == false || $newIndex == 0) {
        redirectBack($redirectPath,'error','Reverse or invalid status update is not allowed');
    }
}else{
    /** candidate validation */
    $allowedCandidateStatuses = [AppConstants::APPLICATION_STATUS['OFFER_ACCEPTED'],AppConstants::APPLICATION_STATUS['OFFER_RJECTED']];

    if (!in_array($status, $allowedCandidateStatuses, true)) {
        redirectBack($redirectPath, 'error', 'You are not allowed to set this status');
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
if (!$stmt) {
    error_log('Prepare failed: ' . $con_main->error);
    redirectBack($redirectPath, 'error', 'System error occurred');
}

$stmt->bind_param(
    "ssi",
    $status,
    $interviewDate,
    $applicationId
);

if ($stmt->execute()) {
    redirectBack($redirectPath, 'success', 'Status updated successfully');
}

error_log('Execution failed: ' . $stmt->error);
redirectBack($redirectPath, 'error', 'Failed to update status');