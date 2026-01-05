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
    $offeredStatus = AppConstants::APPLICATION_STATUS['OFFERED'];
    $interviewStatus = AppConstants::APPLICATION_STATUS['INTERVIEW'];
    $rejectedStatus = AppConstants::APPLICATION_STATUS['REJECTED'];
    $hiredStatus = AppConstants::APPLICATION_STATUS['HIRED'];

    $sql = "SELECT
            job_posts.*,
            companies.name AS company_name,
            locations.name AS location_name,
            job_categories.name AS category_name,
            COUNT(applications.id) AS total_applications,
            SUM(applications.application_status = ? OR applications.application_status = ? OR applications.application_status = ?) AS shortlisted,
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

    $stmt->bind_param("sssssi", $interviewStatus, $hiredStatus,$offeredStatus,$rejectedStatus,$hiredStatus, $id);
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
    $offerStatus = AppConstants::APPLICATION_STATUS['OFFERED'];
    $activeStatus = AppConstants::ACTIVE_STATUS;

    $sql = "SELECT
            COUNT(*) AS total_applications,
            SUM(application_status = ?) AS applied_count,
            SUM(application_status = ?) AS interview_count,
            SUM(application_status = ?) AS offered_count,
            SUM(
                YEARWEEK(createdAt, 1) = YEARWEEK(CURDATE(), 1)
            ) AS this_week_count
        FROM applications
        WHERE status = ?
    ";

    $stmt = $db->prepare($sql);
    if (!$stmt) return false;

    $stmt->bind_param(
        "sssi",
        $appliedStatus,
        $interviewStatus,
        $offerStatus,
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
    $companyId = $_SESSION['company_id'] ?? 0;
    $offset = ($page - 1) * $limit;
    $searchLike = "%$search%";

    $where = " WHERE job_posts.company_id = ?";
    $hasSearch = !empty($search);

    if ($hasSearch) {
        $where .= " AND job_posts.title LIKE ? ";
    }

    $countSql = "SELECT COUNT(*) AS total FROM job_posts" . $where;
    $stmt = $db->prepare($countSql);

    if ($hasSearch) {
        $stmt->bind_param("is", $companyId, $searchLike);
    } else {
        $stmt->bind_param("i", $companyId);
    }

    $stmt->execute();
    $totalJobs = (int)$stmt->get_result()->fetch_assoc()['total'];
    $totalPages = max(1, ceil($totalJobs / $limit));

    $sql = "SELECT 
        job_posts.id,
        job_posts.title,
        job_posts.is_deleted,
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
    ORDER BY job_posts.is_deleted ASC
    LIMIT ?, ?
    ";

    $stmt = $db->prepare($sql);

    if ($hasSearch) {
        $stmt->bind_param("isii", $companyId, $searchLike, $offset, $limit);
    } else {
        $stmt->bind_param("iii", $companyId, $offset, $limit);
    }

    $stmt->execute();
    $jobs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    return [
        'jobs' => $jobs ?? [],
        'totalPages' => $totalPages ?? 0
    ];
}


/** get my jobs card details */
function getMyJobCardData() {
    $db = db();
    $userId = $_SESSION['user_id'] ?? 0;

    $interviewStatus = AppConstants::APPLICATION_STATUS['INTERVIEW'];
    $offeredStatus   = AppConstants::APPLICATION_STATUS['OFFERED'];
    $hiredStatus   = AppConstants::APPLICATION_STATUS['HIRED'];

    $query = "SELECT 
            COUNT(*) AS application_count,
            SUM(CASE WHEN application_status = ? THEN 1 ELSE 0 END) AS interview_count,
            SUM(CASE WHEN application_status = ? THEN 1 ELSE 0 END) AS pending_decision,
            SUM(CASE WHEN application_status = ? THEN 1 ELSE 0 END) AS hired_count
        FROM applications
        WHERE user_id = ?
    ";

    $stmt = $db->prepare($query);
    $stmt->bind_param("sssi", $interviewStatus, $offeredStatus,$hiredStatus, $userId);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    return [
        'application_count' => $result['application_count'] ?? 0,
        'interview_count' => $result['interview_count'] ?? 0,
        'pending_decision' => $result['pending_decision'] ?? 0,
        'hired_count' => $result['hired_count'] ?? 0,
    ];
}


/** get applied jobs */
function getAppliedJobs($status,$search=''){
    $db = db();
    $userId = $_SESSION['user_id'] ?? 0;
    $sql = "SELECT 
            a.id,
            jp.title,
            c.name AS company_name,
            a.job_id,
            a.application_status,
            a.applied_at
        FROM applications a
        INNER JOIN job_posts jp ON jp.id = a.job_id
        INNER JOIN companies c ON c.id = jp.company_id
        WHERE a.user_id = ?
    ";

    $params = [$userId];
    $types  = "i";

    if ($status !== 'ALL') {
        $sql .= " AND a.application_status = ?";
        $params[] = $status;
        $types .= "s";
    }

    if ($search !== '') {
        $sql .= " AND (jp.title LIKE ? OR c.name LIKE ?)";
        $searchTerm = "%{$search}%";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $types .= "ss";
    }

    $sql .= " ORDER BY a.createdAt DESC";

    $stmt = $db->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();

    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    
    return $data;
}


/** job search */
function searchJobs($filters){
    $db = db();

    $search = isset($filters['search']) ? trim($filters['search']) : '';
    $company = isset($filters['company']) ? trim($filters['company']) : '';
    $work_type = isset($filters['work_type']) ? trim($filters['work_type']) : '';
    $categories = isset($filters['categories']) ? $filters['categories'] : [];
    $page  = isset($filters['page']) ? (int)$filters['page'] : 1;

    $limit = 6;
    $offset = ($page - 1) * $limit;

    $query = "SELECT
        job_posts.id,
        job_posts.title,
        job_posts.category_id,
        job_posts.company_id,
        job_posts.work_type,
        job_posts.job_type,
        job_posts.description,
        job_posts.expiry_date,
        companies.name AS company_name,
        locations.name AS location_name,
        job_categories.name AS category_name,
        DATEDIFF(job_posts.expiry_date, CURDATE()) AS days_left
    FROM
        `job_posts`
    INNER JOIN companies ON companies.id = job_posts.company_id
    INNER JOIN job_categories ON job_categories.id = job_posts.category_id
    INNER JOIN locations ON locations.id = job_posts.location_id
    WHERE
        job_posts.active_status = ".AppConstants::ACTIVE_STATUS." AND job_posts.is_deleted = ".AppConstants::INACTIVE_STATUS." AND 
        job_posts.expiry_date >= CURRENT_DATE()";

    if ($search !== '') {
        $query .= " AND job_posts.title LIKE '%" . mysqli_real_escape_string($db, $search) . "%'";
    }

    if ($company != 'all' && $company != '') {
        $query .= " AND job_posts.company_id = '" . mysqli_real_escape_string($db, $company) . "'";
    }
    if ($work_type != 'all' && $work_type != '') {
        $query .= " AND job_posts.work_type = '" . mysqli_real_escape_string($db, $work_type) . "'";
    }

    if (!empty($categories)) {
        $categories_sql = "'" . implode("','", array_map(fn($v) => mysqli_real_escape_string($db, $v), $categories)) . "'";
        $query .= " AND job_posts.category_id IN ($categories_sql)";
    }


    $query .= " ORDER BY job_posts.id DESC";

    $total_result = mysqli_query($db, $query);
    $total_records = mysqli_num_rows($total_result);
    $total_pages = ceil($total_records / $limit);

    // pagination add and fetch
    $query .= " LIMIT $offset, $limit";
    $final_result = mysqli_query($db, $query);
    $jobs = [];

    while ($row = mysqli_fetch_assoc($final_result)) {
        $jobs[] = $row;
    }

    return [
        'jobs' => $jobs,
        'page' => $page,
        'total_records'=>$total_records,
        'total_pages' => $total_pages
    ];
}

/** get applications for a job post */
function getJobApplications($jobId, $from, $to, $search, $page = 1){
    $db = db();
    $limit  = 10;
    $offset = ($page - 1) * $limit;

    $where = " WHERE applications.job_id = ? ";
    $params = [$jobId];
    $types  = "i";

    /* Search */
    if ($search !== '') {
        $where .= " AND (applications.candidate_name LIKE ? OR applications.candidate_email LIKE ?) ";
        $params[] = "%$search%";
        $params[] = "%$search%";
        $types .= "ss";
    }

    /* Date range */
    if ($from && $to) {
        $where .= " AND DATE(applications.createdAt) BETWEEN ? AND ? ";
        $params[] = $from;
        $params[] = $to;
        $types .= "ss";
    }

    $countSql = "SELECT COUNT(*) total
        FROM applications
        INNER JOIN users ON users.id = applications.user_id
        $where
    ";
    $stmt = $db->prepare($countSql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $total = (int)$stmt->get_result()->fetch_assoc()['total'];
    $totalPages = ceil($total / $limit);


    $sql = "SELECT
            applications.*,
            users.first_name,
            users.last_name
        FROM applications
        INNER JOIN users ON users.id = applications.user_id
        $where
        ORDER BY applications.createdAt DESC
        LIMIT ?, ?
    ";

    $params[] = $offset;
    $params[] = $limit;
    $types .= "ii";

    $stmt = $db->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();

    $data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    return [
        'data' => $data,
        'total' => $total,
        'totalPages' => $totalPages
    ];
}

/** get company details */
function getCompanyDetails($companyId) {
    $db = db();
    $approved = AppConstants::COMPANY_APPROVED;

    $sql = "SELECT id, name, description, address, website_link
        FROM companies
        WHERE id = ? AND admin_approval = ?
    ";

    $stmt = $db->prepare($sql);
    $stmt->bind_param("is", $companyId,$approved);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}

/** get candidate jobs - applied or saved */
function getAppliedJobIds($userId,$type)
{
    $db = db();

    $stmt = $db->prepare("SELECT job_id 
        FROM candidate_jobs 
        WHERE user_id = ? AND `type`=?
    ");
    $stmt->bind_param("is", $userId,$type);
    $stmt->execute();

    $result = $stmt->get_result();
    $appliedJobs = [];

    while ($row = $result->fetch_assoc()) {
        $appliedJobs[] = $row['job_id'];
    }

    return $appliedJobs;
}
