<?php include '../config/database.php'; ?>
<?php include '../layouts/layout_start.php'; ?>

<title>User List View - Admin Panel</title>
<link rel="stylesheet" href="style.css">

<?php include '../layouts/header.php'; ?>


<div class="container" style="max-width: 1000px;">
    <h1 style="text-align: left;">User List View - Admin Panel</h1>
    <h2 style="text-align: center;">View User List</h2>

    <div class="d-flex justify-between mt-20" style="border-bottom: 2px dashed var(--ink-color); padding-bottom: 20px; margin-bottom: 20px;">
        <div style="flex: 2; margin-right: 20px; display: flex; align-items: center; border: 2px solid var(--ink-color); padding: 5px; border-radius: 10px;">
            <span style="font-size: 1.5rem; margin-right: 10px;">🔍</span>
            <input type="text" placeholder="Search by Name, Email or ID" style="border: none; flex: 1;">
        </div>
        <button class="btn">+ Add New User</button>
    </div>

    <div class="d-flex justify-between" style="align-items: flex-end;">
        <div>
            <button class="btn" style="background: #eee;">All Users</button>
            <button class="btn">Job Seekers</button>
        </div>
        <div class="d-flex" style="gap: 20px;">
            <div class="form-group" style="margin-bottom: 0;">
                <label>Status</label>
                <select style="border: 2px solid var(--ink-color);">
                    <option>Active</option>
                </select>
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label>Date Range</label>
                <select style="border: 2px solid var(--ink-color);">
                    <option>Last 30 Days</option>
                </select>
            </div>
        </div>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>User ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>00001</td>
                <td>Gihan Udayanga</td>
                <td>Gihan@gmail.com</td>
                <td>Admin</td>
                <td>Active</td>
                <td class="admin-actions">
                    <button class="btn">View</button>
                    <button class="btn">Update</button>
                    <button class="btn">Delete</button>
                </td>
            </tr>
            <tr>
                <td>00002</td>
                <td>Malika Subasinghe</td>
                <td>Mali@gmail.com</td>
                <td>Jobseeker</td>
                <td>Pending</td>
                <td class="admin-actions">
                    <button class="btn">View</button>
                    <button class="btn">Update</button>
                    <button class="btn">Delete</button>
                </td>
            </tr>
            <tr>
                <td>00003</td>
                <td>Kamal Perera</td>
                <td>Kamal@gmail.com</td>
                <td>User</td>
                <td>Deactive</td>
                <td class="admin-actions">
                    <button class="btn">View</button>
                    <button class="btn">Update</button>
                    <button class="btn">Delete</button>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="d-flex mt-20" style="justify-content: flex-end; gap: 10px;">
        <button class="btn">Previous</button>
        <button class="btn">Next</button>
    </div>

</div>

<script src="script.js"></script>

<?php include '../layouts/footer.php'; ?>
<?php include '../layouts/layout_end.php'; ?>