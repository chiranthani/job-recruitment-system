<footer class="site-footer">
    <div class="footer-content">

        <div class="footer-brand">
            <a href="<?php echo BaseConfig::$BASE_URL; ?>home.php">
                <img src="<?php echo BaseConfig::$BASE_URL; ?>assets/images/logo_2.png" alt="JobBoard+ Logo" class="footer-logo" width="200px" />
            </a>
            <p>
                <?= AppConstants::APP_NAME ?> helps job seekers find the right opportunities and
                employers connect with top talent across Sri Lanka.
            </p>
        </div>
    <?php $is_logged_in = isset($_SESSION['user_id']) && isset($_SESSION['role_id']);?>

        <ul class="footer-links">
            <li><a href="<?php echo BaseConfig::$BASE_URL; ?>home.php">Home</a></li>
            <li><a href="<?php echo BaseConfig::$BASE_URL; ?>job-applicant/job-search.php">Find Jobs</a></li>
            <li><a href="<?php echo BaseConfig::$BASE_URL; ?>help.php">Help</a></li>
            <?php if(!$is_logged_in):?>
            <li><a href="<?php echo BaseConfig::$BASE_URL; ?>login.php">Sign In</a></li>
            <li><a href="<?php echo BaseConfig::$BASE_URL; ?>Employer/register.php">Employer Sign Up</a></li>
            <li><a href="<?php echo BaseConfig::$BASE_URL; ?>user-management/signup.php">Job Seeker Sign Up</a></li>
            <?php endif; ?>
            <li><a href="<?php echo BaseConfig::$BASE_URL; ?>terms.php">Terms & Conditions</a></li>
        </ul>

     
        <div class="footer-copy">
            © <?= date('Y') ?> <?= AppConstants::APP_NAME ?>. All rights reserved.
        </div>

    </div>
</footer>
