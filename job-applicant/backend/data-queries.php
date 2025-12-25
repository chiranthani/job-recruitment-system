<?php

/** get active categories with active job post count */
function getActiveCategoriesWithJobsCount()
{
    global $con_main;
    $sql = "SELECT 
                    job_categories.id,
                    job_categories.name,
                    job_categories.icon_path,
                    COUNT(IF(job_posts.expiry_date >= CURRENT_DATE() AND job_posts.active_status = 1 AND job_posts.is_deleted = 0, job_posts.id, NULL)) AS post_count
                FROM job_categories
                LEFT JOIN job_posts 
                    ON job_posts.category_id = job_categories.id 
                WHERE job_categories.status = ?
                GROUP BY job_categories.id, job_categories.name, job_categories.icon_path;";

    $stmt = $con_main->prepare($sql);
    if (!$stmt) return false;

    $status = AppConstants::ACTIVE_STATUS;
    $stmt->bind_param("i", $status);
    $stmt->execute();

    $result = $stmt->get_result();
    return ($result && $result->num_rows > 0) ? $result : false;
}

/** get admin approved companies list */
function getApprovedCompanies()
{
    global $con_main;

    $sql = "SELECT * FROM `companies` WHERE `admin_approval` = ?";
    $stmt = $con_main->prepare($sql);
    if (!$stmt) return false;

    $approval = AppConstants::COMPANY_APPROVED;
    $stmt->bind_param("s", $approval);
    $stmt->execute();

    $result = $stmt->get_result();
    return ($result && $result->num_rows > 0) ? $result : false;
}


/** get user selected job post full content */
function getSelectedJobPostDetails($id)
{
    global $con_main;
    $interviewStatus = AppConstants::APPLICATION_STATUS['INTERVIEW'];
    $rejectedStatus = AppConstants::APPLICATION_STATUS['REJECTED'];

    $sql = "SELECT
            job_posts.*,
            companies.name AS company_name,
            locations.name AS location_name,
            job_categories.name AS category_name,
            COUNT(applications.id) AS total_applications,
            SUM(applications.application_status = ?) AS shortlisted,
            SUM(applications.application_status = ?) AS rejected
        FROM
            `job_posts`
        INNER JOIN companies ON companies.id = job_posts.company_id
        INNER JOIN job_categories ON job_categories.id = job_posts.category_id
        INNER JOIN locations ON locations.id = job_posts.location_id
        LEFT JOIN applications ON applications.job_id = job_posts.id
        WHERE
            job_posts.id = ?";

    $stmt = $con_main->prepare($sql);
    if (!$stmt) return false;

    $stmt->bind_param("ssi", $interviewStatus,$rejectedStatus,$id);
    $stmt->execute();

    $result = $stmt->get_result();
    return ($result && $result->num_rows == 1) ? $result->fetch_assoc() : false;
}

/** get selected post benefits */
function getSelectedJobPostBenefits($jobId)
{
    global $con_main;

    $sql = "SELECT
            job_post_benefits.*,
            benefits.name
        FROM
            `job_post_benefits`
        INNER JOIN benefits on benefits.id = job_post_benefits.benefit_id
        WHERE
            job_post_benefits.job_post_id= ?";

    $stmt = $con_main->prepare($sql);
    if (!$stmt) return false;

    $stmt->bind_param("i", $jobId);
    $stmt->execute();

    $result = $stmt->get_result();
    return ($result && $result->num_rows > 0) ? $result : false;
}

/** get a candidate job table row */
function getACandidateJob($userId, $jobId, $type)
{
    global $con_main;

    $sql = "SELECT * FROM candidate_jobs WHERE job_id = ? AND user_id = ? AND `type` = ?
        LIMIT 1";
    $stmt = $con_main->prepare($sql);
    if (!$stmt) return false;

    $stmt->bind_param("iis", $jobId, $userId, $type);
    $stmt->execute();

    $result = $stmt->get_result();
    return ($result && $result->num_rows == 1) ? $result->fetch_assoc() : false;
}

/*** get candidate details */
function getCandidateDetails($userId)
{
    global $con_main;

    $sql = "SELECT candidates.*,users.first_name,users.last_name,users.email,users.profile_image 
    FROM candidates 
    INNER JOIN users ON users.id = candidates.user_id
    WHERE candidates.user_id=? ORDER BY users.id DESC LIMIT 1";

    $stmt = $con_main->prepare($sql);
    if (!$stmt) return false;

    $stmt->bind_param("i", $userId);
    $stmt->execute();

    $result = $stmt->get_result();
    return ($result && $result->num_rows == 1) ? $result->fetch_assoc() : false;
}

/*** get application overview card details */
function getApplicationOverview()
{
    global $con_main;

    $appliedStatus   = AppConstants::APPLICATION_STATUS['APPLIED'];
    $interviewStatus = AppConstants::APPLICATION_STATUS['INTERVIEW'];
    $activeStatus = AppConstants::ACTIVE_STATUS;

    $sql = "SELECT
            COUNT(*) AS total_applications,
            SUM(application_status = ?) AS applied_count,
            SUM(application_status = ?) AS interview_count,
            SUM(
                YEARWEEK(createdAt, 1) = YEARWEEK(CURDATE(), 1)
            ) AS this_week_count
        FROM applications
        WHERE status = ?
    ";

    $stmt = $con_main->prepare($sql);
    if (!$stmt) return false;

    $stmt->bind_param(
        "ssi",
        $appliedStatus,
        $interviewStatus,
        $activeStatus
    );

    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

/** get a application details */
function getAApplicationDetails($application)
{
    global $con_main;


    $sql = "SELECT * FROM applications WHERE id = ?";

    $stmt = $con_main->prepare($sql);
    if (!$stmt) return false;

    $stmt->bind_param("i",$application);

    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}
