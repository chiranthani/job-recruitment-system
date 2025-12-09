<?php include '../config/database.php'; ?>
<?php include '../layouts/layout_start.php'; ?>
<?php include '../permission-check.php'; ?>

<link rel="stylesheet" href="application.css">

<?php include '../layouts/header.php'; ?>

<section>
<div class="main-container">
     <a href="#" class="back-link">← Back to Overview</a>
    <h2 class="page-title">Candidate Applications</h2>

    <!-- Job Card -->
    <div class="job-header-card">
        <div class="job-left">
            <div class="job-icon">📄</div>

            <div>
                <h3 class="job-title">Senior Developer</h3>
                <div class="job-meta">
                    <span class="job-category">IT/Software</span>
                    <small class="published-date">Published on 22/11/2025</small>
                </div>
            </div>
        </div>

        <div class="job-right">
            <span class="job-type">Full-Time</span>
            <span class="job-location"> Colombo</span>
        </div>
    </div>

    <table class="responsive-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Contact No</th>
                <th>Experience</th>
                <th>Current Role</th>
                <th>Notice Time</th>
                <th>Applied Date</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
           <tr>
            <td data-label="Name">John Doe</td>
            <td data-label="Email">john@example.com</td>
            <td data-label="Contact No">0712345678</td>
            <td data-label="Experience">3 Years</td>
            <td data-label="Current Role">Frontend Dev</td>
            <td data-label="Notice Time">2 Weeks</td>
            <td data-label="Applied Date">1/12/2025</td>
            <td data-label="Status"><span class="status-pill rejected">Interview</span></td>
            <td data-label="">
                <a href="<?php echo BaseConfig::$BASE_URL ?>uploads/resume_john_doe.pdf" target="_blank" class="btn btn-info ">👁️ CV</a>
                
                <button class="btn btn-view" onclick="openStatusModal(101,'Interview')">Change Status</button>
            </td>
        </tr>
        
        </tbody>
    </table>
</div>
</section>
<?php include 'modals/application_status_change.php'; ?>
<script>
    function openStatusModal(id,status){
        selectedCandidateId = id;
        document.getElementById('statusModal').style.display = 'flex';
        document.getElementById('statusSelect').value = status;
    }

    function toggleInterviewDate(){
        let sel = document.getElementById('statusSelect').value;
        document.getElementById('interviewBox').style.display = (sel === 'Interview') ? 'block' : 'none';
    }
    function closeModal(){
        document.getElementById('statusModal').style.display = 'none';
    }

</script>
<?php include '../layouts/footer.php'; ?>
<?php include '../layouts/layout_end.php'; ?>