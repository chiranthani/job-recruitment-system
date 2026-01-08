<?php include 'config/database.php'; ?>
<?php include 'layouts/layout_start.php'; ?>
<?php include 'permission-check.php'; ?>
<link rel="stylesheet" href="job-applicant/application.css">

<?php include 'layouts/header.php'; ?>
<?php include 'job-applicant/backend/data-queries.php'; ?>

<?php
    $userId = $_SESSION['user_id'] ?? 0;
    $page = intval($_GET['page'] ?? 1);
    $notifications = getNotificationsList($userId,$page,10);
?>

<div class="main-container">
    <h2 class="page-title">My Notifications</h2>

    <?php if (empty($notifications['notifications'])): ?>
        <p>No notifications found.</p>
    <?php else: ?>
        <?php foreach ($notifications['notifications'] as $n): ?>
            <div class="notification <?php echo $n['is_read'] == 0 ? 'unread' : ''; ?>" data-id="<?php echo $n['id']; ?>">
                <?php echo $n['message']; ?>
                <div class="notification-time"><?php echo date('d M Y, H:i', strtotime($n['created_at'])); ?></div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>


    <div class="pagination">
        <?php for ($p=1; $p<=$notifications['last_page']; $p++): ?>
            <a href="?page=<?php echo $p; ?>" class="<?php echo $p==$page?'active':''; ?>"><?php echo $p; ?></a>
        <?php endfor; ?>
    </div>
</div>

<?php include 'layouts/footer.php'; ?>
<?php include 'layouts/layout_end.php'; ?>