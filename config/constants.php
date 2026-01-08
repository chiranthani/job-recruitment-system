<?php

class AppConstants {

    const APP_NAME = "Job Recruitment System";

    // roles
    const ROLE_ADMIN = 3;
    const ROLE_EMPLOYER = 2;
    const ROLE_JOB_SEEKER = 1;
    const ROLE_GUEST = 0;

    // statuses
    const ACTIVE_STATUS = 1;
    const INACTIVE_STATUS = 0;

    // gender list 
    const gender_list = ['Male', 'Female', 'Other'];

    // work types
    const WORK_TYPES = ['On-site', 'Remote', 'Hybrid'];

    // job types
    const JOB_TYPES = ['Full-Time','Part-Time','Contract','Internship','Freelance'];

    // job post status
    const POST_DRAFT = 'draft';
    const POST_PUBLISHED = 'published';

    // candidate job types
    const APPLIED_JOB = 'Applied Job';
    const SAVED_JOB = 'Saved Job';

    // application statues constants
    const APPLICATION_STATUS = [
        'APPLIED' => 'Applied',
        'IN_REVIEW' => 'In Review',
        'INTERVIEW'=> 'Interview',
        'REJECTED'=> 'Rejected',
        'OFFERED'=> 'Offer Made',
        'OFFER_RJECTED'=> 'Offer Rejected',
        'OFFER_ACCEPTED'=> 'Offer Accepted',
        'HIRED'=> 'Hired'
    ];

    // company approval statuses
    const COMPANY_APPROVED = 'APPROVED';
    const COMPANY_REJECTED = 'REJECTED';
    const COMPANY_PENDING = 'APPROVED';


    const EXPERIENCE_OPTIONS = [
        '0-1 years',
        '1-2 years',
        '2-3 years',
        '3-5 years',
        '5+ years'
    ];

    const NOTICE_PERIOD_OPTIONS = [
        'Immediately',
        '1 Week',
        '2 Weeks',
        '1 Month',
        'More than 1 Month'
    ];

    const APPLICATION_STATUS_FLOW = ['Applied','In Review','Interview','Offer Made','Rejected','Offer Rejected','Offer Accepted','Hired'];

    const NOTIFICATION_TYPES = [
        'NEW_APPLICATION',
        'INTERVIEW_SCHEDULED',
        'OFFER_MADE',
        'OFFER_ACCEPTED',
        'OFFER_REJECTED'
    ];
}
