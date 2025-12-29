<?php

/** helper function */
function db(): mysqli
{
    global $con_main;

    if (!$con_main instanceof mysqli) {
        throw new Exception('Database connection not initialized');
    }

    return $con_main;
}

/** get active categories with active job post count */
function getActiveCategoriesWithJobsCount()
{
    $db = db();
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

    $stmt = $db->prepare($sql);
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
    $db = db();

    $sql = "SELECT * FROM `companies` WHERE `admin_approval` = ?";
    $stmt = $db->prepare($sql);
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
    $db = db();
    $interviewStatus = AppConstants::APPLICATION_STATUS['INTERVIEW'];
    $rejectedStatus = AppConstants::APPLICATION_STATUS['REJECTED'];
    $hiredStatus = AppConstants::APPLICATION_STATUS['HIRED'];

    $sql = "SELECT
            job_posts.*,
            companies.name AS company_name,
            locations.name AS location_name,
            job_categories.name AS category_name,
            COUNT(applications.id) AS total_applications,
            SUM(applications.application_status = ? OR applications.application_status = ?) AS shortlisted,
            SUM(applications.application_status = ?) AS rejected,
            SUM(applications.application_status = ?) AS hired
        FROM
            `job_posts`
        INNER JOIN companies ON companies.id = job_posts.company_id
        INNER JOIN job_categories ON job_categories.id = job_posts.category_id
        INNER JOIN locations ON locations.id = job_posts.location_id
        LEFT JOIN applications ON applications.job_id = job_posts.id
        WHERE
            job_posts.id = ?";

    $stmt = $db->prepare($sql);
    if (!$stmt) return false;

    $stmt->bind_param("ssssi", $interviewStatus, $hiredStatus,$rejectedStatus,$hiredStatus, $id);
    $stmt->execute();

    $result = $stmt->get_result();
    return ($result && $result->num_rows == 1) ? $result->fetch_assoc() : false;
}

/** get selected post benefits */
function getSelectedJobPostBenefits($jobId)
{
    $db = db();

    $sql = "SELECT
            job_post_benefits.*,
            benefits.name
        FROM
            `job_post_benefits`
        INNER JOIN benefits on benefits.id = job_post_benefits.benefit_id
        WHERE
            job_post_benefits.job_post_id= ?";

    $stmt = $db->prepare($sql);
    if (!$stmt) return false;

    $stmt->bind_param("i", $jobId);
    $stmt->execute();

    $result = $stmt->get_result();
    return ($result && $result->num_rows > 0) ? $result : false;
}

/** get a candidate job table row */
function getACandidateJob($userId, $jobId, $type)
{
    $db = db();

    $sql = "SELECT * FROM candidate_jobs WHERE job_id = ? AND user_id = ? AND `type` = ?
        LIMIT 1";
    $stmt = $db->prepare($sql);
    if (!$stmt) return false;

    $stmt->bind_param("iis", $jobId, $userId, $type);
    $stmt->execute();

    $result = $stmt->get_result();
    return ($result && $result->num_rows == 1) ? $result->fetch_assoc() : false;
}

/*** get candidate details */
function getCandidateDetails($userId)
{
    $db = db();

    $sql = "SELECT candidates.*,users.first_name,users.last_name,users.email,users.profile_image 
    FROM candidates 
    INNER JOIN users ON users.id = candidates.user_id
    WHERE candidates.user_id=? ORDER BY users.id DESC LIMIT 1";

    $stmt = $db->prepare($sql);
    if (!$stmt) return false;

    $stmt->bind_param("i", $userId);
    $stmt->execute();

    $result = $stmt->get_result();
    return ($result && $result->num_rows == 1) ? $result->fetch_assoc() : false;
}

/*** get application overview card details */
function getApplicationOverview()
{
    $db = db();

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

    $stmt = $db->prepare($sql);
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
    $db = db();


    $sql = "SELECT * FROM applications WHERE id = ?";

    $stmt = $db->prepare($sql);
    if (!$stmt) return false;

    $stmt->bind_param("i", $application);

    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

/** get post wise application stats count data */
function getJobPostStats($search, $page, $limit=10)
{
    $db = db();
    $postStatus = AppConstants::POST_PUBLISHED;

    $offset = ($page - 1) * $limit;
    $searchLike = "%$search%";

    $where = " WHERE post_status = ? ";
    $hasSearch = !empty($search);

    if ($hasSearch) {
        $where .= " AND job_posts.title LIKE ? ";
    }

    $countSql = "SELECT COUNT(*) AS total FROM job_posts" . $where;
    $stmt = $db->prepare($countSql);

    if ($hasSearch) {
        $stmt->bind_param("ss", $postStatus, $searchLike);
    } else {
        $stmt->bind_param("s", $postStatus);
    }

    $stmt->execute();
    $totalJobs = (int)$stmt->get_result()->fetch_assoc()['total'];
    $totalPages = max(1, ceil($totalJobs / $limit));

    $sql = "SELECT 
        job_posts.id,
        job_posts.title,
        COUNT(applications.id) AS total,
        COALESCE(SUM(applications.application_status='Applied'), 0) AS new,
        COALESCE(SUM(applications.application_status='In Review'), 0) AS reviewed,
        COALESCE(SUM(applications.application_status='Rejected'), 0) AS rejected,
        COALESCE(SUM(applications.application_status='Interview'), 0) AS interview,
        COALESCE(SUM(applications.application_status='Offer Made'), 0) AS offer,
        COALESCE(SUM(applications.application_status='Hired'), 0) AS hired
    FROM job_posts
    LEFT JOIN applications ON applications.job_id = job_posts.id
    $where
    GROUP BY job_posts.id
    LIMIT ?, ?
    ";

    $stmt = $db->prepare($sql);

    if ($hasSearch) {
        $stmt->bind_param("ssii", $postStatus, $searchLike, $offset, $limit);
    } else {
        $stmt->bind_param("sii", $postStatus, $offset, $limit);
    }

    $stmt->execute();
    $jobs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    return [
        'jobs' => $jobs ?? [],
        'totalPages' => $totalPages ?? 0
    ];
}

