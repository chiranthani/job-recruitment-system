<?php include '../config/database.php'; ?>
<?php include '../layouts/layout_start.php'; ?>

<title>User Registration (Admin)</title>
<link rel="stylesheet" href="style.css">

<?php include '../layouts/header.php'; ?>

<div class="container">
    <h1>User Registration</h1>

    <div style="position: absolute; top: 20px; right: 20px; text-align: center;">
        <div style="width: 60px; height: 60px; border: 3px solid var(--ink-color); border-radius: 50%; margin: 0 auto 5px; display: flex; justify-content: center; align-items: center; font-size: 30px;">👤</div>
        <div class="admin-actions">
            <button class="btn btn-sm">Add/Change</button>
            <button class="btn btn-sm">Remove</button>
        </div>
    </div>


    <form style="margin-top: 60px;">
        <div class="form-row">
            <div class="form-group"><label>First Name <span class="required">*</span></label><input type="text"></div>
            <div class="form-group"><label>Town <span class="required">*</span></label><input type="text"></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Last Name <span class="required">*</span></label><input type="text"></div>
            <div class="form-group"><label>Region <span class="required">*</span></label><input type="text"></div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Gender <span class="required">*</span></label>
                <div>
                    <label style="display:inline;"><input type="radio" name="gender"> Male</label>
                    <label style="display:inline; margin-left: 10px;"><input type="radio" name="gender"> Female</label>
                    <label style="display:inline; margin-left: 10px;"><input type="radio" name="gender"> Other</label>
                </div>
            </div>
            <div class="form-group"><label>Postal code/zip</label><input type="text"></div>
        </div>

        <div class="form-row">
            <div class="form-group"><label>Date of Birth <span class="required">*</span></label><input type="date"></div>
            <div class="form-group"><label>Country <span class="required">*</span></label><input type="text"></div>
        </div>

        <div class="form-row">
            <div class="form-group"><label>Contact Number <span class="required">*</span></label><input type="text"></div>
            <div class="form-group"><label>User name <span class="required">*</span></label><input type="text"></div>
        </div>

        <div class="form-row">
            <div class="form-group"><label>Address <span class="required">*</span></label><textarea style="height: 60px;"></textarea></div>
            <div class="form-group"><label>Password <span class="required">*</span></label><input type="password"></div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Status <span class="required">*</span></label>
                <select>
                    <option>Active</option>
                    <option>Inactive</option>
                </select>
            </div>
            <div class="form-group"><label>Confirm Password <span class="required">*</span></label><input type="password"></div>
        </div>
        <div class="form-row">
            <div class="form-group"></div>
            <div class="form-group">
                <label>User Role <span class="required">*</span></label>
                <select>
                    <option value="">Select Role</option>
                    <option>JobSeeker</option>
                    <option>Admin</option>
                    <option>User</option>
                </select>
            </div>
        </div>

        <div class="mt-20">
            <button class="btn">Add</button>
            <button class="btn" style="margin-left: 20px;">Update</button>
        </div>
    </form>
</div>

<script src="script.js"></script>

<?php include '../layouts/footer.php'; ?>
<?php include '../layouts/layout_end.php'; ?>