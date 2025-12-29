<?php include '../config/database.php'; ?>
<?php include '../layouts/layout_start.php'; ?>

<title>Let's Get Started</title>
<link rel="stylesheet" href="style.css">

<?php include '../layouts/header.php'; ?>

<div class="container">
    <h1>Let's Get Started</h1>

    <form action="profile_step1.php" method="POST">
        <div class="form-group">
            <label for="email">Email Address <span class="required">*</span></label>
            <input type="email" id="email" name="email" value="example@gmail.com" required>
        </div>

        <div class="form-group">
            <label for="password">Password <span class="required">*</span></label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirm Password <span class="required">*</span></label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>

        <div class="form-group">
            <label style="display: flex; align-items: center;">
                <input type="checkbox" required style="width: auto; margin-right: 10px;">
                I Agree to the Terms and Conditions.
            </label>
        </div>

        <div class="text-center mt-20">
            <button type="submit" class="btn">Create Profile</button>
        </div>

        <p class="text-center mt-20">
            Already have an account? <a href="#" style="color: var(--ink-color); font-weight: bold;">Sign in</a>
        </p>
    </form>
</div>
<script src="script.js"></script>

<?php include '../layouts/footer.php'; ?>
<?php include '../layouts/layout_end.php'; ?>