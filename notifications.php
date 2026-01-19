<?php include 'layouts/layout_start.php'; ?>
<?php require 'permission-check.php'; ?>
<link rel="stylesheet" href="assets/css/application.css">

<?php include 'layouts/header.php'; ?>
<?php include 'job-applicant/backend/data-queries.php'; ?>

<?php
$userId = $_SESSION['user_id'] ?? 0;
$page = intval($_GET['page'] ?? 1);
$notifications = getNotificationsList($userId, $page, 10);
?>

<div class="main-container">
    <h2 class="page-title">My Notifications</h2>

    <?php if (empty($notifications['notifications'])): ?>
        <div style="margin-top: auto;text-align: center;padding: 40px 0;">No notifications found.</div>
    <?php else: ?>
        <?php foreach ($notifications['notifications'] as $n): ?>
            <div class="notification <?= $n['is_read'] == 0 ? 'unread' : ''; ?>" data-id="<?= $n['id']; ?>">
                <?= htmlspecialchars($n['message']); ?>
                <div class="notification-time"><?= date('d M Y, H:i', strtotime($n['created_at'])); ?></div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if ($notifications['last_page'] > 1): ?>
        <div class="pagination-wrapper">
            <div class="pagination-info">
                Page <strong><?= $page ?></strong> of <strong><?= $notifications['last_page'] ?></strong>
            </div>
            <div class="pagination">
                <?php for ($i = 1; $i <= $notifications['last_page']; $i++): ?>
                    <a href="?page=<?= $i; ?>" class="<?= $i == $page ? 'active' : ''; ?>"><?= $i; ?></a>
                <?php endfor; ?>
            </div>
        </div>
    <?php endif ?>

</div>

<?php include 'layouts/footer.php'; ?>
<?php include 'layouts/layout_end.php'; ?>