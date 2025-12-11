

<?php
$is_logged_in = isset($_SESSION['user_id']);
$role_id = $_SESSION['role_id'] ?? 0;
$username = $_SESSION['username'] ?? 'Guest';
?>
</head>
<body>

<header class="header">
    <div class="header-logo">
        <span class="logo-icon">[ ]</span>
        <span class="logo-text">Jobs XX</span>
    </div>

    <!-- Desktop -->
    <nav class="nav">
        <a href="<?php echo BaseConfig::$BASE_URL; ?>home.php">Home</a>
        
        <?php if ($is_logged_in): ?>
            <?php if ($role_id == 1): // Candidate ?>
                <a href="#">My Jobs</a>
            <?php elseif ($role_id == 2): // Employer ?>
                <a href="<?php echo BaseConfig::$BASE_URL; ?>Employer/dashboard.php">Dashboard</a>
                <a href="<?php echo BaseConfig::$BASE_URL; ?>Jobs/job_list.php">Job Posts</a>
                <a href="<?php echo BaseConfig::$BASE_URL; ?>Employer/profile.php">Company</a>
            <?php elseif ($role_id == 3): // Admin ?>
                <a href="<?php echo BaseConfig::$BASE_URL; ?>Admin/employer_verification.php">Companies</a>
            <?php endif; ?>
        <?php endif; ?>
        
        <a href="<?php echo BaseConfig::$BASE_URL; ?>help.php">Help</a>
    </nav>

    <!-- Desktop -->
    <div class="header-icons">
        <?php if ($is_logged_in): ?>
            <span class="icon" title="<?php echo $username; ?>"><img src="<?php echo BaseConfig::$BASE_URL; ?>assets/images/default_profile_image.png" class="menu-icon" alt="profile" /></span>
            <a href="<?php echo BaseConfig::$BASE_URL; ?>logout.php"><span class="icon"><img src="<?php echo BaseConfig::$BASE_URL; ?>assets/images/logout.png" class="menu-icon" alt="logout" /></span></a>
        <?php else: ?>
            <a href="<?php echo BaseConfig::$BASE_URL; ?>login.php" style="color: #333; text-decoration: none; padding: 8px 16px; background: #f0f0f0; border-radius: 4px;">Login</a>
        <?php endif; ?>
    </div>

    <!-- Mobile -->
    <div class="hamburger" id="menuBtn" onclick="toggleMenu()">☰</div>
</header>

<!-- Mobile Sidebar Menu -->
<div class="mobile-menu" id="mobileMenu">
    <a href="<?php echo BaseConfig::$BASE_URL; ?>home.php" class="menu-item">Home</a>
    
    <?php if ($is_logged_in): ?>
        <?php if ($role_id == 1): ?>
            <a href="#" class="menu-item">My Jobs</a>
        <?php elseif ($role_id == 2): ?>
            <a href="<?php echo BaseConfig::$BASE_URL; ?>Employer/dashboard.php" class="menu-item">Dashboard</a>
            <a href="<?php echo BaseConfig::$BASE_URL; ?>Jobs/job_list.php" class="menu-item">Job Posts</a>
            <a href="<?php echo BaseConfig::$BASE_URL; ?>Employer/profile.php" class="menu-item">Company</a>
        <?php elseif ($role_id == 3): ?>
            <a href="<?php echo BaseConfig::$BASE_URL; ?>Admin/employer_verification.php" class="menu-item">Companies</a>
        <?php endif; ?>
    <?php endif; ?>
    
    <a href="<?php echo BaseConfig::$BASE_URL; ?>help.php" class="menu-item">Help</a>
    <hr>
    
    <?php if ($is_logged_in): ?>
        <a href="#" class="menu-item side-menu-row"><img src="<?php echo BaseConfig::$BASE_URL; ?>assets/images/default_profile_image.png" class="menu-icon" alt="profile" /> <?php echo $username; ?></a>
        <a href="<?php echo BaseConfig::$BASE_URL; ?>logout.php" class="menu-item side-menu-row"><img src="<?php echo BaseConfig::$BASE_URL; ?>assets/images/logout.png" class="menu-icon" alt="logout" /> Logout</a>
    <?php else: ?>
        <a href="<?php echo BaseConfig::$BASE_URL; ?>login.php" class="menu-item">Login</a>
        <a href="<?php echo BaseConfig::$BASE_URL; ?>Employer/register.php" class="menu-item">Register as Employer</a>
    <?php endif; ?>
</div>

<script>
    function toggleMenu() {
        const menu = document.getElementById("mobileMenu");
        const btn = document.getElementById("menuBtn");

        menu.classList.toggle("show");

        if (menu.classList.contains("show")) {
            btn.innerHTML = "✖";
        } else {
            btn.innerHTML = "☰";
        }
    }
</script>