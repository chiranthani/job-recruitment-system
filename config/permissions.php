<?php

$ACCESS_RULES = [
    "/Jobs/create_post.php" => [AppConstants::ROLE_EMPLOYER],
    "/Jobs/job_list.php" => [AppConstants::ROLE_EMPLOYER],
    "/Jobs/job_edit.php" => [AppConstants::ROLE_EMPLOYER],
    "/job-applicant/apply.php" => [AppConstants::ROLE_JOB_SEEKER],
    "/job-applicant/my-jobs.php" => [AppConstants::ROLE_JOB_SEEKER],
    "/job-applicant/applied-candidates.php"  => [AppConstants::ROLE_EMPLOYER],
    "/job-applicant/application-overview.php" => [AppConstants::ROLE_EMPLOYER],
];
?>