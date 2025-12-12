<!-- start page common elements -->
<?php include '../config/database.php'; ?>
<?php include '../layouts/layout_start.php'; ?>
<?php include '../layouts/header.php'; ?>
<!-- end page common elements-->

<link rel="stylesheet" href="job-post.css">

<section class="job-wrapper">
    <h2>Job Post Creation</h2>

    <form method="POST" action="save_job.php" class="job-form">

        <!-- TWO COLUMN ROW: Job Status + Deadline -->
        <div class="form-row-2col">

            <div>
                <label>* Job Status:</label>
                <select name="job_status" class="input-box">
                    <option>Draft</option>
                    <option>Published</option>
                    <option>Archived</option>
                </select>
            </div>

            <div>
                <label>Application Deadline:</label>
                <input type="date" name="deadline" class="input-box">
            </div>

        </div>

        <!-- Category -->
        <div class="form-row">
            <label>* Category:</label>
            <select name="category" class="input-box-full">
                <option>Select Category</option>
                <option>IT</option>
                <option>HR</option>
                <option>Finance</option>
                <option>Marketing</option>
            </select>
        </div>

        <!-- Job Title -->
        <div class="form-row">
            <label>* Job Title:</label>
            <input type="text" name="job_title" class="input-box-full">
        </div>

        <!-- Job Type -->
        <div class="form-row">
            <label>* Job Type:</label>
            <select name="job_type" class="input-box">
                <option>Job Type</option>
                <option>Full Time</option>
                <option>Part Time</option>
                <option>Intern</option>
                <option>Contract</option>
            </select>
        </div>


        <!-- Description -->
        <div class="form-row">
            <label>* Description:</label>
            <textarea name="description" class="textarea"></textarea>
        </div>

        <!-- Requirements -->
        <div class="form-row">
            <label>* Requirements:</label>
            <textarea name="requirements" class="textarea"></textarea>
        </div>

        <!-- Benefits -->
        <div class="form-row">
            <label>* Benefits:</label>
            <div class="checkbox-group">
                <label><input type="checkbox"> Receive a competitive salary based on experience.</label>
                <label><input type="checkbox"> Comprehensive health insurance coverage.</label>
                <label><input type="checkbox"> Flexible hours & work-from-home options.</label>
                <label><input type="checkbox"> Performance-based bonuses & incentives.</label>
            </div>
        </div>

        <!-- Job Location -->
        <div class="form-row">
            <label>* Job Location:</label>
            <select class="input-box">
                <option>Job Location</option>
                <option>Colombo</option>
                <option>Gampaha</option>
                <option>Matara</option>
                <option>Kandy</option>
            </select>
        </div>

        <!-- Save + Close Buttons -->
        <div class="form-actions">
            <button type="submit" class="save-btn">Save</button>
            <button type="button" class="close-btn">Close</button>
        </div>

    </form>
</section>

<?php include '../layouts/footer.php'; ?>
<?php include '../layouts/layout_end.php'; ?>
