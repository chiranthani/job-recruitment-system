<div class="modal-overlay" id="statusModal">
    <div class="modal-container">
        <div class="modal-header">Update Status</div>

        <div class="form-group">
            <label for="statusSelect">Select Status</label>
            <select id="statusSelect" onchange="toggleInterviewDate()">
                <option value="Pending">Pending</option>
                <option value="Shortlisted">Shortlisted</option>
                <option value="Interview">Interview</option>
                <option value="Rejected">Rejected</option>
                <option value="Hired">Hired</option>
            </select>
        </div>

        <div class="form-group" id="interviewBox" style="display:none;">
            <label>Interview Date & Time</label>
            <input type="datetime-local" id="interviewDate">
        </div>

        <div class="modal-footer">
            <button onclick="closeModal()">Cancel</button>
            <button onclick="saveStatus()">Save</button>
        </div>
    </div>
</div>
