<?php

$ACCESS_RULES = [
    "/jobs/create_post.php" => [AppConstants::ROLE_EMPLOYER],
    "/jobs/job_list.php" => [AppConstants::ROLE_EMPLOYER],
    "/jobs/job_edit.php" => [AppConstants::ROLE_EMPLOYER],
    "/job-applicant/apply.php" => [AppConstants::ROLE_JOB_SEEKER],
    "/job-applicant/my-jobs.php" => [AppConstants::ROLE_JOB_SEEKER],
    "/job-applicant/applied-candidates.php"  => [AppConstants::ROLE_EMPLOYER],
    "/job-applicant/application-overview.php" => [AppConstants::ROLE_EMPLOYER],
    "notifications.php" => [AppConstants::ROLE_JOB_SEEKER,AppConstants::ROLE_EMPLOYER,AppConstants::ROLE_ADMIN]
];
?>