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

}
