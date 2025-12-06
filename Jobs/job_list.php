<!-- start page main content -->
<section>
    <h2>Job Posts</h2>
    <p>Manage your job postings</p>

    <!-- Search -->
    <form method="GET">
        <input type="text" name="search" placeholder="Search by job title" style="padding:5px; width:200px; margin-bottom:10px;">
        <button type="submit">Search</button>
        <button type="button" style="float:right;">New Job Post</button>
    </form>

    <?php

    // Sample job posts array
    $jobs = [
        ['id' => 1, 'title' => 'Developer I', 'category' => 'IT', 'status' => 'Published', 'active' => true],
        ['id' => 2, 'title' => 'Designer', 'category' => 'Design', 'status' => 'Draft', 'active' => false],
        ['id' => 3, 'title' => 'Tester', 'category' => 'QA', 'status' => 'Published', 'active' => true],
    ];

    // Handle search
    $search = $_GET['search'] ?? '';
    if ($search !== '') {
        $jobs = array_filter($jobs, function($job) use ($search) {
            return stripos($job['title'], $search) !== false;
        });
    }
    ?>

    <!-- Job Table -->
    <table border="1" cellspacing="0" cellpadding="5" style="width:100%; margin-top:20px; color:#000; border-color:#000;">
        <tr style="background-color:#333; color:#fff;">
            <th>#</th>
            <th>Title</th>
            <th>Category</th>
            <th>Status</th>
            <th>Active/Deactivate</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($jobs as $job): ?>
        <tr>
            
            <td><?php echo $job['id']; ?></td>
            <td><?php echo $job['title']; ?></td>
            <td><?php echo $job['category']; ?></td>
            <td style="text-align:center; <?php echo $job['status'] == 'Published' ? 'background-color:#0d6efd; color:#fff;' : 'background-color:#6c757d; color:#fff;'; ?>">
                <?php echo $job['status']; ?>
            </td>
            <td style="text-align:center;">
                <form method="POST" style="display:inline;">
                    <button type="submit" name="toggle" value="<?php echo $job['id']; ?>">
                        <?php echo $job['active'] ? 'Active ✅' : 'Inactive ❌'; ?>
                    </button>
                </form>
            </td>
            <td style="text-align:center;">
                <span style="cursor:pointer;">👁️</span> <!-- view icon -->
                <span style="cursor:pointer;">✏️</span> <!-- edit icon -->
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <?php
    // Handle toggle action (demo, just reload page)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle'])) {
        $id = $_POST['toggle'];
        echo "<p>Toggle action clicked for Job ID: $id (In real app, update DB here)</p>";
    }
    ?>
</section>
<!-- end page main content -->
