<!-- start page common elements -->
<?php include '../config/database.php'; ?>
<?php include '../layouts/layout_start.php'; ?>
<?php include '../layouts/header.php'; ?>
<!-- end page common elements-->

<link rel="stylesheet" href="job-post.css">

<section class="job-wrapper">
<?php if (isset($_SESSION['error'])) { ?>
    <div class="alert error">
        <?= $_SESSION['error']; ?>
    </div>
<?php unset($_SESSION['error']); } ?>

    <h2>Job Post Creation</h2>

    <form method="POST" action="save_job.php" class="job-form">

        <!-- TWO COLUMN ROW: Job Status + Deadline -->
        <div class="form-row-2col">

            <div>
                <label>* Job Status:</label>
                <select name="job_status" class="input-box" required>
                    <option value="draft" selected>Draft</option>
                    <option value="published">Published</option>
                    
                </select>
            </div>

            <div>
                <label>Application Deadline:</label>
                <input type="date"
                        name="deadline"
                        class="input-box"
                        min="<?= date('Y-m-d'); ?>">
            </div>

        </div>

        <!-- Job Title -->
        <div class="form-row">
            <label>* Job Title:</label>
            <input type="text" name="job_title" class="input-box-full" required>
        </div>

        <!-- Category -->
        <div class="form-row">
            <label>* Category:</label>
            <select name="category" class="input-box-full" required>
                <option value="" selected disabled>Select Category</option>
                <?php
                $query = "SELECT * from job_categories where `status`=1";
                $result = $con_main->query($query);
                while ($job_categories = $result->fetch_assoc()) {
                ?>
                    <option value="<?php echo ($job_categories['id']) ?>"><?php echo ($job_categories['name'])  ?></option>
                <?php } ?>
            </select>
        </div>


        <div class="form-row-2col">
            <!-- Job Type -->
            <div class="form-row">
                <label>* Job Type:</label>
                <select name="job_type" class="input-box" required>
                    <option value="" selected disabled>Job Type</option>
                    <option value="Full-Time">Full Time</option>
                    <option value="Part-Time">Part Time</option>
                    <option value="Internship">Intern</option>
                    <option value="Contract">Contract</option>
                    <option value="Freelance">Freelance</option>
                </select>
            </div>
            <div>
                  <label>* Work Type:</label>
                <select name="work_type" class="input-box" required>
                    <option vlaue="" selected disabled>Job Type</option>
                    <option value="On-site">On site</option>
                    <option value="Remote">Remote</option>
                    <option value="Hybrid">Hybrid</option>
                </select>
            </div>
        </div>


        <!-- Description -->
        <div class="form-row">
            <label>* Description:</label>
            <textarea name="description" class="textarea" required></textarea>
        </div>

        <!-- Requirements -->
        <div class="form-row">
            <label>* Requirements:</label>
            <textarea name="requirements" class="textarea" required></textarea>
        </div>

        <!-- Benefits -->
        <div class="form-row" required>
            <label>* Benefits:</label>
            <div class="checkbox-group">
                <?php
                $query = "SELECT * from benefits";
                $result = $con_main->query($query);
                while ($benefit = $result->fetch_assoc()) {
                ?>
                    <label><input type="checkbox"  name="benefits[]" class="benefits-checkbox" value="<?php echo ($benefit['id']) ?>"> <?php echo ($benefit['name']); ?></label>
                <?php } ?>
            </div>
        </div>

        <!-- Job Location -->
        <div class="form-row" required>
            <label>* Job Location:</label>
            <select class="input-box"  name="location_id">
                <option value="" selected disabled>Job Location</option>
                <?php
                $query = "SELECT * from locations where `status`=1";
                $result = $con_main->query($query);
                while ($locations = $result->fetch_assoc()) {
                ?>
                    <option value="<?php echo ($locations['id']) ?>"><?php echo ($locations['name'])  ?></option>
                <?php } ?>
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