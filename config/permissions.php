<?php

$ACCESS_RULES = [
    "/job-applicant/apply.php"   => [AppConstants::ROLE_JOB_SEEKER],
    "/job-applicant/my-jobs.php"   => [AppConstants::ROLE_JOB_SEEKER],
    "/job-applicant/applied-candidates.php"    => [AppConstants::ROLE_EMPLOYER],
];
?>