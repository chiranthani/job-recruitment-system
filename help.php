<!-- start page common elements -->
<?php include 'config/database.php'; ?>
<?php include 'layouts/layout_start.php'; ?>
<link rel="stylesheet" href="assets/css/main.css">
<?php include 'layouts/header.php'; ?>
<!-- end page common elements-->

<!-- start page main content -->
<section class="help-container">
    <div class="help-card">
        <div class="help-header">
            <h1>Help & User Guide</h1>
            <p>Learn how to use the Job Recruitment System effectively</p>
        </div>

        <!-- For Job Seekers -->
        <div class="help-section">
            <h2>For Job Seekers (Candidates)</h2>
            
            <h3>1. Creating an Account</h3>
            <ul>
                <li>Click on "Sign Up" or "Register" button</li>
                <li>Select "Job Seeker" as your account type</li>
                <li>Fill in your personal details (name, email, password)</li>
                <li>Verify your email address</li>
            </ul>

            <h3>2. Searching for Jobs</h3>
            <ul>
                <li>Use the search bar on the home page to find jobs by keywords</li>
                <li>Browse jobs by category</li>
                <li>Filter jobs by location, job type, and work type</li>
                <li>Click on a job to view full details</li>
            </ul>

            <h3>3. Applying for Jobs</h3>
            <ul>
                <li>Click "Apply Now" on the job posting</li>
                <li>Fill in the application form with your details</li>
                <li>Upload your CV/Resume (PDF format recommended)</li>
                <li>Submit your application</li>
                <li>Track your application status in "My Jobs"</li>
            </ul>

            <h3>4. Managing Your Profile</h3>
            <ul>
                <li>Update your personal information</li>
                <li>Add your skills and experience</li>
                <li>Upload or update your CV</li>
                <li>Keep your contact information current</li>
            </ul>
        </div>

        <!-- For Employers -->
        <div class="help-section">
            <h2>For Employers (Recruiters)</h2>
            
            <h3>1. Company Registration</h3>
            <ul>
                <li>Click on "Sign Up" and select "Employer"</li>
                <li>Provide your company details and registration number</li>
                <li>Submit required verification documents</li>
                <li>Wait for admin approval (usually 1-2 business days)</li>
            </ul>

            <h3>2. Managing Company Profile</h3>
            <ul>
                <li>Go to "Company" in the navigation menu</li>
                <li>Click "Edit Profile" to update company information</li>
                <li>Add company description, address, and website</li>
                <li>Save changes to update your profile</li>
            </ul>

            <div class="note-box">
                <strong>Note:</strong> Your company profile must be approved by an administrator before you can post jobs.
            </div>

            <h3>3. Posting Jobs</h3>
            <ul>
                <li>Navigate to "Job Posts" and click "Create New Job"</li>
                <li>Fill in job details (title, description, requirements)</li>
                <li>Select job category, type, and location</li>
                <li>Add benefits and other relevant information</li>
                <li>Publish the job posting</li>
            </ul>

            <h3>4. Managing Applications</h3>
            <ul>
                <li>View all applications in the "Applications" section</li>
                <li>Review candidate profiles and CVs</li>
                <li>Update application status (In Review, Interview, etc.)</li>
                <li>Schedule interviews and provide feedback</li>
            </ul>

            <h3>5. Verification Status</h3>
            <p>Your company profile can have the following statuses:</p>
            <ul>
                <li><strong>PENDING:</strong> Awaiting admin approval</li>
                <li><strong>APPROVED:</strong> Verified and can post jobs</li>
                <li><strong>REJECTED:</strong> Verification failed, contact admin</li>
            </ul>
        </div>

        <!-- For Administrators -->
        <!-- <div class="help-section">
            <h2>For Administrators</h2>
            
            <h3>1. Employer Verification</h3>
            <ul>
                <li>Access "Companies" or "Employer Verification" from admin panel</li>
                <li>Review pending employer registrations</li>
                <li>Verify company registration details</li>
                <li>Approve or reject employer accounts</li>
            </ul>

            <h3>2. Managing Employers</h3>
            <ul>
                <li>View all registered companies</li>
                <li>Activate or deactivate employer accounts</li>
                <li>Monitor employer activity</li>
                <li>Handle disputes and issues</li>
            </ul>

            <h3>3. System Management</h3>
            <ul>
                <li>Manage users (candidates and employers)</li>
                <li>Monitor job postings</li>
                <li>Review system analytics</li>
                <li>Maintain job categories and locations</li>
            </ul>
        </div> -->

        <!-- General Tips -->
        <div class="help-section">
            <h2>General Tips</h2>
            <ul>
                <li>Keep your profile information up to date</li>
                <li>Use a professional email address</li>
                <li>Upload clear and well-formatted documents</li>
                <li>Check your email regularly for notifications</li>
                <li>Contact support if you encounter any issues</li>
            </ul>
        </div>

        <!-- Contact Support -->
        <div class="help-section">
            <h2>Need More Help?</h2>
            <p>If you have questions or need assistance, please contact our support team:</p>
            <ul>
                <li>Email: support@jobsxx.com</li>
                <li>Phone: +94 11 234 5678</li>
                <li>Working Hours: Monday - Friday, 9:00 AM - 5:00 PM</li>
            </ul>
        </div>
    </div>
</section>
<!-- end page main content -->

<?php include 'layouts/footer.php'; ?>
<?php include 'layouts/layout_end.php'; ?>
