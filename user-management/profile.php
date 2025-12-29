<?php include '../config/database.php'; ?>
<?php include '../layouts/layout_start.php'; ?>

<title>Job Seeker Profile - Multi-Step</title>
<link rel="stylesheet" href="style.css">

<style>
    /* Extra style to ensure Step 2 is hidden initially */
    #step2-content {
        display: none;
    }
</style>

<?php include '../layouts/header.php'; ?>

<div class="container">
    <h1>Job Seeker Profile</h1>

    <div class="tabs">
        <a href="javascript:void(0)" class="tab-link active" onclick="navigateToStep(1)">About You</a>
        <a href="javascript:void(0)" class="tab-link" onclick="navigateToStep(2)">Professional Info</a>
    </div>

    <form id="multiStepProfileForm">

        <div id="step1-content">
            <p>Fill in the following information so companies know who you are and what you are looking for...</p>

            <div class="form-row">
                <div class="form-group">
                    <label>First Name <span class="required">*</span></label>
                    <input type="text" name="first_name">
                </div>
                <div class="form-group">
                    <label>Last Name <span class="required">*</span></label>
                    <input type="text" name="last_name">
                </div>
                <div class="form-group">
                    <label>Contact No</label>
                    <input type="text" name="contact_no">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Country / Region <span class="required">*</span></label>
                    <input type="text" name="country">
                </div>
                <div class="form-group">
                    <label>City / Town <span class="required">*</span></label>
                    <input type="text" name="city">
                </div>
                <div class="form-group">
                    <label>Postal Code</label>
                    <input type="text" name="postal_code">
                </div>
            </div>

            <div class="mt-20">
                <button type="button" class="btn" onclick="navigateToStep(2)">Next</button>
            </div>
        </div>

        <div id="step2-content">
            <div class="form-group">
                <label>Job Title <span class="required">*</span></label>
                <input type="text" name="job_title">
            </div>

            <div class="form-group">
                <label>Brief Bio <span class="required">*</span></label>
                <textarea name="brief_bio"></textarea>
            </div>

            <div class="form-group">
                <label>Key Skills <span class="required">*</span></label>
                <div style="border: 2px solid var(--ink-color); padding: 5px; display: flex; gap: 5px; align-items: center;">
                    <span style="border:1px solid #000; padding: 2px 8px; border-radius: 10px;">Java x</span>
                    <span style="border:1px solid #000; padding: 2px 8px; border-radius: 10px;">PHP x</span>
                    <span style="border:1px solid #000; padding: 2px 8px; border-radius: 10px;">C++ x</span>
                    <input type="text" style="border:none; width: auto; flex:1;">
                </div>
            </div>

            <div class="form-group" style="border: 2px solid var(--ink-color); padding: 15px; border-radius: 10px;">
                <label>Resume Upload <span class="required">*</span></label>
                <div style="display: flex; align-items: center; justify-content: center; cursor: pointer;">
                    <span style="font-size: 2em; margin-right: 10px;">☁️</span>
                    <span>Upload Resume/CV</span>
                    <input type="file" name="resume" style="opacity: 0; position: absolute; width: 100px;">
                </div>
            </div>

            <div class="d-flex justify-between mt-20">
                <button type="button" class="btn" onclick="navigateToStep(1)">Back</button>
                <button type="button" class="btn" onclick="showPopup('successModal')">Finish Registration</button>
            </div>
        </div>

    </form>
</div>

<div id="successModal" class="modal-overlay">
    <div class="modal-content">
        <div class="checkmark-circle">✓</div>
        <h2>Welcome to Jobxx....!</h2>
        <p>Your account has successfully Created we're excited to help find your next opportunity...!</p>

        <div class="form-row mt-20" style="justify-content: center; gap: 10px;">
            <button class="btn" onclick="closePopup('successModal')">Complete your Profile</button>
            <button class="btn" onclick="closePopup('successModal')">Start Browsing Jobs</button>
        </div>
    </div>
</div>

<script src="script.js"></script>

<?php include '../layouts/footer.php'; ?>
<?php include '../layouts/layout_end.php'; ?>