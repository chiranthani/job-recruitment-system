<?php session_start(); ?>
<?php include 'layouts/layout_start.php'; ?>
<?php include 'layouts/header.php'; ?>

<div class="denied-container">
    <div class="denied-box">
        <h2>🚫 Access Denied</h2>
        <p>You don’t have permission to access this page.</p>
        <br />
        <button class="buttn buttn-outline" onclick="goBack()">
            << Go Back</button>
    </div>
</div>

<script>
    function goBack() {
        window.history.back();
    }
</script>
</body>

</html>