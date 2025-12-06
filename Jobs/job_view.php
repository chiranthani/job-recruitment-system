<!-- start page common elements -->
<?php include '../config/database.php'; ?>
<?php include '../layouts/layout_start.php'; ?>
<?php include '../layouts/header.php'; ?>
<!-- end page common elements -->

<?php
// Example job data (replace with DB query)
$job = [
    "status" => "Draft",
    "title" => "Developer I",
    "category" => "Full-Time",
    "type" => "IT",
    "deadline" => "2025-12-13",
    "description" => "Lorem Ipsum is simply dummy text of the printing and typesetting industry...",
    "requirements" => "The ideal candidate will have a Bachelor's degree in Computer Science...",
    "benefits" => [
        "salary" => false,
        "insurance" => false,
        "bonus" => false
    ],
    "location" => "Colombo"
];
?>

<!-- start page main content -->
<section style="padding: 20px; color:white;">

    <h2 style="margin-bottom:20px;">Job Post Edit</h2>

    <form method="post" action="update_job.php" style="width:80%; background:#1a1a1a; padding:20px; border:1px solid #333;">
        
        <!-- Row: Job Status + Deadline -->
        <div style="display:flex; justify-content:space-between;">
            <div style="width:48%;">
                <label>Job Status:</label>
                <input type="text" name="status" value="<?php echo $job['status']; ?>"
                       style="width:100%; padding:10px; background:#000; color:white; border:1px solid #555;">
            </div>

            <div style="width:48%;">
                <label>Application Deadline:</label>
                <input type="date" name="deadline" value="<?php echo $job['deadline']; ?>"
                       style="width:100%; padding:10px; background:#000; color:white; border:1px solid #555;">
            </div>
        </div>

        <label style="margin-top:15px;">Category:</label>
        <input type="text" name="category" value="<?php echo $job['category']; ?>"
               style="width:100%; padding:10px; background:#000; color:white; border:1px solid #555;">

        <label style="margin-top:15px;">Job Title:</label>
        <input type="text" name="title" value="<?php echo $job['title']; ?>"
               style="width:100%; padding:10px; background:#000; color:white; border:1px solid #555;">

        <label style="margin-top:15px;">Job Type:</label>
        <input type="text" name="type" value="<?php echo $job['type']; ?>"
               style="width:100%; padding:10px; background:#000; color:white; border:1px solid #555;">

        <label style="margin-top:15px;">Description:</label>
        <textarea name="description" rows="5"
                  style="width:100%; padding:10px; background:#000; color:white; border:1px solid #555;"><?php echo $job['description']; ?></textarea>

        <label style="margin-top:15px;">Requirements:</label>
        <textarea name="requirements" rows="5"
                  style="width:100%; padding:10px; background:#000; color:white; border:1px solid #555;"><?php echo $job['requirements']; ?></textarea>

        <label style="margin-top:15px;">Benefits:</label>
        <div style="margin-top:5px;">
            <label style="display:block; margin-bottom:8px;">
                <input type="checkbox" name="benefits_salary" <?php if($job['benefits']['salary']) echo "checked"; ?>>  
                Receive a competitive salary.
            </label>

            <label style="display:block; margin-bottom:8px;">
                <input type="checkbox" name="benefits_insurance" <?php if($job['benefits']['insurance']) echo "checked"; ?>>  
                Health insurance coverage.
            </label>

            <label style="display:block; margin-bottom:8px;">
                <input type="checkbox" name="benefits_bonus" <?php if($job['benefits']['bonus']) echo "checked"; ?>>  
                Performance-based bonuses.
            </label>
        </div>

        <label style="margin-top:15px;">Job Location:</label>
        <input type="text" name="location" value="<?php echo $job['location']; ?>"
               style="width:100%; padding:10px; background:#000; color:white; border:1px solid #555;">

        <button type="submit" 
                style="margin-top:20px; padding:10px 20px; background:#444; color:white; border:none; cursor:pointer;">
            Edit
        </button>

        <button type="button" onclick="window.location='job_list.php';"
                style="margin-top:20px; padding:10px 20px; background:#222; color:white; border:none; cursor:pointer; margin-left:10px;">
            Close
        </button>

    </form>

</section>
<!-- end page main content -->


<?php include '../layouts/footer.php'; ?>
<?php include '../layouts/layout_end.php'; ?>
