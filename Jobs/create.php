<!-- start page common elements -->
<?php include '../config/database.php'; ?>
<?php include '../layouts/layout_start.php'; ?>
<?php include '../layouts/header.php'; ?>
<!-- end page common elements-->

<!-- start page main content -->
<section>

    <h2>Job Post Creation</h2>

    <form method="POST" action="save_job.php">

        <label>* Job Status:</label>
        <select name="job_status">
            <option value="Draft">Draft</option>
            <option value="Published">Published</option>
        </select>

        <label>Application Deadline:</label>
        <input type="date" name="deadline">

        <br><br>

        <label>* Category:</label>
        <select name="category">
            <option value="">Select Category</option>
            <option value="IT">IT</option>
            <option value="Finance">Finance</option>
            <option value="HR">HR</option>
            <option value="Marketing">Marketing</option>
        </select>

        <br><br>

        <label>* Job Title:</label><br>
        <input type="text" name="title" style="width:50%; height:30px;">

        <br><br>

        <label>* Job Type:</label>
        <select name="job_type">
            <option value="">Job Type</option>
            <option value="Full Time">Full Time</option>
            <option value="Intern">Intern</option>
            <option value="Contract">Contract</option>
            <option value="Part Time">Part Time</option>
        </select>

        <br><br>

        <label>* Description:</label><br>
        <textarea name="description" style="width:60%; height:120px;"></textarea>

        <br><br>

        <label>* Requirements:</label><br>
        <textarea name="requirements" style="width:60%; height:120px;"></textarea>

        <br><br>

        <label>* Benefits:</label><br>

        <label><input type="checkbox" name="benefits[]" value="Competitive Salary"> Receive a competitive salary based on experience and performance.</label><br>

        <label><input type="checkbox" name="benefits[]" value="Health Insurance"> Comprehensive health insurance coverage for you and your family.</label><br>

        <label><input type="checkbox" name="benefits[]" value="Work From Home"> Flexibility in work hours and the option to work from home.</label><br>

        <label><input type="checkbox" name="benefits[]" value="Performance Bonus"> Eligibility for performance-based bonuses and incentives.</label><br>

        <br>

        <label>* Job Location:</label>
        <select name="location">
            <option value="">Job Location</option>
            <option value="Colombo">Colombo</option>
            <option value="Gampaha">Gampaha</option>
            <option value="Matara">Matara</option>
            <option value="Kandy">Kandy</option>
            <option value="Galle">Galle</option>
            <option value="Jaffna">Jaffna</option>
            <option value="Negambo">Negambo</option>
            <option value="Homagama">Homagama</option>
            <option value="Kiribathgoda">Kiribathgoda</option>
        </select>

        <br><br>

        <button type="submit">Save</button>
        <button type="button" onclick="window.location.href='job_posts.php'">Close</button>

    </form>

</section>
<!-- end page main content -->

<?php include '../layouts/footer.php'; ?>
<?php include '../layouts/layout_end.php'; ?>
