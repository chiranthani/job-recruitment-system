<?php include '../config/database.php'; ?>
<?php include '../layouts/layout_start.php'; ?>
<?php include '../permission-check.php'; ?>

<link rel="stylesheet" href="application.css">

<?php include '../layouts/header.php'; 
include 'backend/data-queries.php';
    $userId = $_SESSION['user_id'] ?? 0;

    $candidate = getCandidateDetails($userId);

    $existingResume = $candidate['cv_url'] ?? null;
    $fname = $candidate['first_name'] ?? '';
    $lname = $candidate['last_name'] ?? '';
    $email = $candidate['email'] ?? '';
    $contact_no = $candidate['contact_no'] ?? '';
?>

<section>
    <div class="job-apply-container">
        <?php $job = getSelectedJobPostDetails($_GET['job']); ?>
           
        <a onclick="window.history.back()" class="back-link">← Back to Job Details</a>
        <h2>Apply for <?php echo $job['title'] ?></h2>
        <p class="job-company">at <?php echo $job['company_name'] ?></p>

        <form id="applyForm" enctype="multipart/form-data">
            <input type="hidden" name="job_id" value="<?php echo $job['id'] ?>">
            <h3 class="form-title">Personal Information</h3>

            <div class="row">
                <div class="col">
                    <label class="required">First Name</label>
                    <input type="text" name="first_name" value="<?php echo $fname ?>" placeholder="John" required>
                </div>
                <div class="col">
                    <label class="required">Last Name</label>
                    <input type="text" name="last_name" value="<?php echo $lname ?>" placeholder="Doe" required>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label class="required">Email</label>
                    <input type="email" name="email" value="<?php echo $email ?>" placeholder="john.doe@example.com" required>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label class="required">Contact No</label>
                    <input type="text" name="phone" value="<?php echo $contact_no ?>" placeholder="0712345678" required>
                </div>
            </div>
            <h3 class="form-title">Professional Information</h3>
            <div class="row">
                <div class="col">
                    <label class="required">Years of Experience</label>
                    <select name="experience" required>
                        <option value="" selected disabled>Select Experience</option>
                        <?php foreach (AppConstants::EXPERIENCE_OPTIONS as $exp): ?>
                            <option value="<?= $exp ?>"><?= $exp ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label class="required">Current Role</label>
                    <input type="text" name="current_role" placeholder="Enter current role">
                </div>
            </div>

            <h3 class="form-title">CV Upload</h3>

            <?php if ($existingResume): ?>
                <div class="cv-options">
                    <label>
                        <input type="radio" name="cv_option" value="existing" checked>
                        Use existing CV
                        <a href="<?php echo BaseConfig::$BASE_URL ?><?= $existingResume ?>" target="_blank">(View)</a>
                    </label>

                    <label>
                        <input type="radio" name="cv_option" value="new">
                        Upload new CV
                    </label>
                </div>
            <?php else: ?>
                <input type="hidden" name="cv_option" value="new">
            <?php endif; ?>

            <div class="row" id="newCvBox" style="<?= $existingResume ? 'display:none' : '' ?>">
                <div class="col">
                    <label class="file-box" id="resumeLabel">
                        <input type="file" name="resume" accept=".pdf, .doc, .docx" id="resumeInput">
                        <span id="resumeText">Upload your resume<br><small>PDF, DOC, DOCX (Max 5MB)</small></span>
                        <button type="button" id="removeResumeBtn" style="display:none;">X</button>
                    </label>
                </div>
            </div>


            <h3 class="form-title">Additional Questions</h3>
            <div class="row">
                <div class="col">
                    <label class="required">When can you start?</label>
                    <select name="notice" required>
                        <option value="" selected disabled>Select Notice Period</option>
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
<?php include 'modals/success-popup.php'; ?>
<?php include 'modals/error-popup.php'; ?>
<script>
    const resumeInput = document.getElementById('resumeInput');
    const resumeText = document.getElementById('resumeText');
    const removeBtn = document.getElementById('removeResumeBtn');
    const MAX_FILE_SIZE = 5 * 1024 * 1024;

    const cvRadios = document.querySelectorAll('input[name="cv_option"]');
    const newCvBox = document.getElementById('newCvBox');

    cvRadios.forEach(radio => {
        radio.addEventListener('change', function () {
            if (this.value == 'new') {
                newCvBox.style.display = 'block';
                resumeInput.required = true;
            } else {
                newCvBox.style.display = 'none';
                resumeInput.required = false;
                resumeInput.value = "";
                resetResume();
            }
        });
    });


    resumeInput.addEventListener('change', function() {
        if (this.files && this.files.length > 0) {

            if (this.files[0].size > MAX_FILE_SIZE) {
                showError("Resume file size must be less than 5MB");
                this.value = "";
                resetResume();
                return;
            }
            const fileName = this.files[0].name;
            resumeText.textContent = `${fileName}`;
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
        resumeText.innerHTML = `Upload your resume<br><small>PDF, DOC, DOCX (Max 5MB)</small>`;
        removeBtn.style.display = 'none';
    }


    document.getElementById("applyForm").addEventListener("submit", function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "backend/application-submit.php", true);

        xhr.onload = function() {
            if (xhr.status == 200) {
                let res = JSON.parse(xhr.responseText);

                if (res.status == "success") {
                    showSuccess("🎉 Application Submitted!",res.message || "Successfully!");
                } else {
                    showError(res.message || "Something went wrong!");
                }
            } else {
                showError("Server error! Try again.");
            }
        };

        xhr.send(formData);
    });

    // Popup handling
    function showError(msg) {
        document.getElementById("errorMessage").textContent = msg;
        document.getElementById("errorPopup").style.display = "flex";
    }

    function showSuccess(title,msg) {
        document.getElementById("popup-title").textContent = title;
        document.getElementById("popup-message").textContent = msg;
        document.getElementById("successPopup").style.display = "flex";
    }
    
    function closeSuccessPopup() {
        document.getElementById("successPopup").style.display = "none";
        window.location.replace('job-search.php');
    }

    function clearQueryParams() {
        const url = new URL(window.location.href);

        url.searchParams.delete('success');
        url.searchParams.delete('error');

        window.history.replaceState({}, document.title, url.pathname + url.search);
    }

</script>


<?php include '../layouts/footer.php'; ?>
<?php include '../layouts/layout_end.php'; ?>