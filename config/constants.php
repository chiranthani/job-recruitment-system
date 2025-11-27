<?php

class AppConstants {

    const APP_NAME = "Job Recruitment System";

    // roles
    const ROLE_ADMIN = 3;
    const ROLE_EMPLOYER = 2;
    const ROLE_JOB_SEEKER = 1;

    // statuses
    const ACTIVE_STATUS = 1;
    const INACTIVE_STATUS = 0;

    // gender list 
    const gender_list = ['Male', 'Female', 'Other'];

    // work types
    const WORK_TYPES = ['On-site', 'Remote', 'Hybrid', ''];

    // job types
    const JOB_TYPES = ['Full-Time','Part-Time','Contract','Internship','Freelance'];

    // candidate job types
    const APPLIED_JOB = 'Applied Job';
    const SAVED_JOB = 'Saved Job';

    // application statues
    const APPLICATION_STATUS = [
        'APPLIED' => 'Applied Candidate',
        'INREVIEW' => 'In Review',
        'INTERVIEW'=> 'Interview',
        'REJECTED'=> 'Rejected Candidate',
        'OFFERED'=> 'Offer Made',
        'OFFER_RJECTED'=> 'Offer Rejected',
        'OFFER_ACCEPTED'=> 'Offer Accepted',
        'HIRED'=> 'Hired'
    ];

    // company approval statuses
    const COMPANY_APPROVED = 'APPROVED';
    const COMPANY_REJECTED = 'REJECTED';
    const COMPANY_PENDING = 'APPROVED';

    // company sizes
    const company_sizes = ['1-10 Employees','11-50 Employees','51-250 Employees','251 Employees and above'];

}
