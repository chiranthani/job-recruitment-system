<div class="modal-overlay" id="statusModal">
    <div class="modal-container">
        <div class="modal-header">Update Status</div>

        <div class="form-group">
            <label for="statusSelect">Select Status</label>
            <select id="statusSelect" onchange="toggleInterviewDate()">
                <?php foreach(AppConstants::APPLICATION_STATUS as $key => $label): ?>
                    <option value="<?= ($label); ?>"><?= $label; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group" id="interviewBox" style="display:none;">
            <label>Interview Date & Time</label>
            <input type="datetime-local" id="interviewDate">
        </div>

        <div class="modal-footer">
            <input type="hidden" id="applicationId" name="applicationId" >
            <button class="btn btn-cancel" onclick="closeModal()">Cancel</button>
            <button class="btn btn-submit" onclick="saveStatus()">Save</button>
        </div>
    </div>
</div>
