# job-recruitment-system

A simple and efficient job recruitment web application built using **Pure PHP, MySQL, HTML, CSS & JavaScript** without any frameworks.  
This system allows employers to post and manage job listings, track applicants, and manage company profiles, while job seekers can browse and apply for jobs easily.

---

## Technologies 
- **Frontend:** HTML, CSS, JavaScript
- **Backend:** Core PHP (No Frameworks)
- **Database:** MySQL

---

## Modules

1. User Management Module – Handles job seeker and employer accounts

2. Employer Profile & Verification Module – Company approval workflow

3. Job Post Management Module – Create, edit, and publish jobs

4. Applicant Management Module – View and manage applications, update status

---

## Features
# For Job Seekers:

Register and create a personal profile

Search and filter jobs by category, company, and work type

Apply to jobs with CV upload

Track application status (Applied, In Review, Interview, Offered, Hired, ...)



# For Employers:

Company registration and profile management

Verification workflow for approval by admin

Create, edit, and publish job postings

Dashboard with job and application statistics

Manage applicants, schedule interviews, and update status

Track applications and download CVs


# Admin Features:

Dashboard with company statistics and quick actions for system management

Manage user account statuses

Approve/reject company registrations


## Installation Guide

### Requirements
- XAMPP / WAMP / LAMP
- PHP ≥ 7.4
- MySQL ≥ 5.7

### Setup Steps
```bash
git clone https://github.com/chiranthani/job-recruitment-system.git
cd job-recruitment-system
```
Start Apache and MySQL using your local server (XAMPP/WAMP/LAMP)

Create a new MySQL database and import the SQL file from the /sql folder.

Configure database connection in /config/database.php with your credentials:
```bash
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'your_database_name';
```

Open your browser and run: [http://localhost/job-recruitment-system](http://localhost/job-recruitment-system)

You can now register as a Job Seeker or Employer and start using the system.


