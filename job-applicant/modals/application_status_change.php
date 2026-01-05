<div class="modal-overlay" id="statusModal">
    <div class="modal-container">
        <div class="modal-header">Update Status</div>
        <form method="POST" action="backend/update-application-status.php">
        <div class="form-group">
            <label for="statusSelect">Select Status</label>
            <select id="status" name="status" onchange="toggleInterviewDate()">
                <?php foreach(AppConstants::APPLICATION_STATUS as $key => $label): ?>
                    <option value="<?= ($label); ?>"><?= $label; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group" id="interviewBox" style="display:none;">
            <label>Interview Date & Time</label>
            <input type="datetime-local" id="interview_date">
        </div>

        <div class="modal-footer">
            <input type="hidden" id="application_id" name="application_id" >
            <button type="button" class="btn btn-cancel" onclick="closeModal()">Cancel</button>
            <button type="submit" class="btn btn-submit">Save</button>
        </div>
        </form>
    </div>
</div>
