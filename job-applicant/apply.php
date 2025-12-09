<?php include '../config/database.php'; ?>
<?php include '../layouts/layout_start.php'; ?>

<link rel="stylesheet" href="application.css">

<?php include '../layouts/header.php'; ?>

<section>
    <div class="job-apply-container">
        <a onclick=" window.history.back()" class="back-link">← Back to Job Details</a>
        <h2>Apply for Senior Frontend Developer</h2>
        <p class="job-company">at ABC Solutions</p>

        <form method="POST" enctype="multipart/form-data">

            <h3 class="form-title">Personal Information</h3>

            <div class="row">
                <div class="col">
                    <label class="required">First Name</label>
                    <input type="text" name="first_name" placeholder="John" required>
                </div>
                <div class="col">
                    <label class="required">Last Name</label>
                    <input type="text" name="last_name" placeholder="Doe" required>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label class="required">Email</label>
                    <input type="email" name="email" placeholder="john.doe@example.com" required>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label class="required">Contact No</label>
                    <input type="text" name="phone" placeholder="0712345678" required>
                </div>
            </div>
            <h3 class="form-title">Professional Information</h3>
            <div class="row">
                <div class="col">
                    <label class="required">Years of Experience</label>
                    <select name="experience" required>
                        <option value="">Select Experience</option>
                        <?php foreach (AppConstants::EXPERIENCE_OPTIONS as $exp): ?>
                            <option value="<?= $exp ?>"><?= $exp ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label class="required">Current Role</label>
                    <input type="text" name="current_role" placeholder="Senior Software Engineer">
                </div>
            </div>

            <h3 class="form-title">CV Upload</h3>
            <div class="row">
                <div class="col">
                    <label class="file-box" id="resumeLabel">
                        <input type="file" name="resume" accept=".pdf, .doc, .docx" id="resumeInput" required>
                        <span id="resumeText">📄 Upload your resume<br><small>PDF, DOC, DOCX (Max 5MB)</small></span>
                        <button type="button" id="removeResumeBtn" style="display:none;">X</button>
                    </label>
                </div>
            </div>


            <h3 class="form-title">Additional Questions</h3>
            <div class="row">
                <div class="col">
                    <label class="required">When can you start?</label>
                    <select name="notice" required>
                        <option value="">Select Notice Period</option>
                        <?php foreach (AppConstants::NOTICE_PERIOD_OPTIONS as $notice): ?>
                            <option value="<?= $notice ?>"><?= $notice ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-footer-btns">
                <button type="button" class="btn btn-cancel">Cancel</button>
                <button type="submit" class="btn btn-submit">Submit Application</button>
            </div>

        </form>
    </div>
</section>
<script>
    const resumeInput = document.getElementById('resumeInput');
    const resumeText = document.getElementById('resumeText');
    const removeBtn = document.getElementById('removeResumeBtn');

    resumeInput.addEventListener('change', function() {
        if (this.files && this.files.length > 0) {
            const fileName = this.files[0].name;
            resumeText.textContent = `📄 ${fileName}`;
            removeBtn.style.display = 'inline-block';
        } else {
            resetResume();
        }
    });

    removeBtn.addEventListener('click', function() {
        resumeInput.value = '';
        resetResume();
    });

    function resetResume() {
        resumeText.innerHTML = `📄 Upload your resume<br><small>PDF, DOC, DOCX (Max 5MB)</small>`;
        removeBtn.style.display = 'none';
    }
</script>


<?php include '../layouts/footer.php'; ?>
<?php include '../layouts/layout_end.php'; ?>