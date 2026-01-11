<?php
include 'config/database.php';
include 'config/baseConfig.php';
include 'config/constants.php';
include 'layouts/layout_start.php';
include 'layouts/header.php';
?>
<style>
    .terms-container {
    max-width: 900px;
    margin: 40px auto;
    padding: 30px;
    line-height: 1.7;
    color: #333;
    background: var(--white);
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.terms-container h1 {
    font-size: 32px;
    margin-bottom: 10px;
}

.terms-container .updated {
    font-size: 14px;
    color: #777;
    margin-bottom: 30px;
}

.terms-container h2 {
    font-size: 20px;
    margin-top: 30px;
    margin-bottom: 10px;
}

.terms-container ul {
    padding-left: 20px;
}

.terms-container li {
    margin-bottom: 6px;
}

</style>
<div class="terms-container">
    <h1>Terms & Conditions</h1>
    <p class="updated">Last updated: <?php echo date('F d, Y'); ?></p>

    <section>
        <h2>1. Introduction</h2>
        <p>
            Welcome to <strong><?= AppConstants::APP_NAME ?></strong>. By accessing or using our website,
            you agree to comply with and be bound by these Terms & Conditions.
            If you do not agree, please do not use our services.
        </p>
    </section>

    <section>
        <h2>2. Eligibility</h2>
        <p>
            You must be at least 18 years old to use this platform.
            By registering, you confirm that the information you provide is accurate and complete.
        </p>
    </section>

    <section>
        <h2>3. User Accounts</h2>
        <p>
            You are responsible for maintaining the confidentiality of your login credentials.
            <?= AppConstants::APP_NAME ?> is not responsible for any unauthorized access to your account.
        </p>
    </section>

    <section>
        <h2>4. Job Seekers</h2>
        <ul>
            <li>Job seekers must provide truthful and accurate profile information.</li>
            <li>Uploading misleading or false documents is strictly prohibited.</li>
            <li><?= AppConstants::APP_NAME ?> does not guarantee job placement.</li>
        </ul>
    </section>

    <section>
        <h2>5. Employers</h2>
        <ul>
            <li>Employers must post genuine and lawful job opportunities.</li>
            <li>Discriminatory or misleading job postings are not allowed.</li>
            <li>Employers are solely responsible for hiring decisions.</li>
        </ul>
    </section>

    <section>
        <h2>6. Prohibited Activities</h2>
        <ul>
            <li>Submitting false or misleading information</li>
            <li>Using the platform for illegal purposes</li>
            <li>Attempting to gain unauthorized access to the system</li>
        </ul>
    </section>

    <section>
        <h2>7. Termination</h2>
        <p>
            <?= AppConstants::APP_NAME ?> reserves the right to suspend or terminate accounts
            that violate these Terms & Conditions without prior notice.
        </p>
    </section>

    <section>
        <h2>8. Limitation of Liability</h2>
        <p>
            <?= AppConstants::APP_NAME ?> is not liable for any damages arising from the use
            or inability to use the platform, including job outcomes.
        </p>
    </section>

    <section>
        <h2>9. Changes to Terms</h2>
        <p>
            We may update these Terms & Conditions at any time.
            Continued use of the platform indicates acceptance of the updated terms.
        </p>
    </section>

    <section>
        <h2>10. Contact Us</h2>
        <p>
            If you have any questions regarding these Terms & Conditions,
            please contact us at <strong>support@jobboardplus.click</strong>.
        </p>
    </section>
</div>

<?php
include 'layouts/footer.php';
include 'layouts/layout_end.php';
?>
