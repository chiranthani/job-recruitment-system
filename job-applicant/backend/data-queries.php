<?php

/** get active categories with active job post count */
function getActiveCategoriesWithJobsCount()
{
    $sql = "SELECT 
                    job_categories.id,
                    job_categories.name,
                    job_categories.icon_path,
                    COUNT(IF(job_posts.expiry_date >= CURRENT_DATE() AND job_posts.active_status = 1 AND job_posts.is_deleted = 0, job_posts.id, NULL)) AS post_count
                FROM job_categories
                LEFT JOIN job_posts 
                    ON job_posts.category_id = job_categories.id 
                WHERE job_categories.status = ".AppConstants::ACTIVE_STATUS. "
                GROUP BY job_categories.id, job_categories.name, job_categories.icon_path;";

    return $sql;
}

/** get admin approved companies list */
function getApprovedCompanies(){

    $sql = "SELECT * FROM `companies` WHERE `admin_approval` = '" . AppConstants::COMPANY_APPROVED . "'";
    return $sql;
}


/** get user selected job post full content */
function getSelectedJobPostDetails($id){

    $sql = "SELECT
            job_posts.*,
            companies.name AS company_name,
            locations.name AS location_name,
            job_categories.name AS category_name
        FROM
            `job_posts`
        INNER JOIN companies ON companies.id = job_posts.company_id
        INNER JOIN job_categories ON job_categories.id = job_posts.category_id
        INNER JOIN locations ON locations.id = job_posts.location_id
        WHERE
            job_posts.id = ".$id;

    return $sql;
}

/** get selected post benefits */
function getSelectedJobPostBenefits($jobId){

        $sql = "SELECT
            job_post_benefits.*,
            benefits.name
        FROM
            `job_post_benefits`
        INNER JOIN benefits on benefits.id = job_post_benefits.benefit_id
        WHERE
            job_post_benefits.job_post_id=".$jobId;
            
    return $sql;
}
