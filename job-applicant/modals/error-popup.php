<div id="errorPopup" class="modal-overlay" style="display:none;">
    <div class="modal-container" style="text-align:center">
        <h3 style="margin: 10px 0;">❌ Error</h3>
        <p id="errorMessage"></p>
        <div style="margin-top: 15px;">
                <button class="btn btn-submit" onclick="closeErrorPopup()">Close</button>
        </div>
    </div>
</div>
<script>
    function closeErrorPopup() {
        document.getElementById("errorPopup").style.display = "none";
        clearQueryParams();
    }

</script>
