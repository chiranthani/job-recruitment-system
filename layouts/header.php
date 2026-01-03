<?php
$currentPath = basename($_SERVER['PHP_SELF']);
$is_logged_in = isset($_SESSION['user_id']);
$role_id = $_SESSION['role_id'] ?? 0;
$username = $_SESSION['username'] ?? 'Guest';
?>
</head>

<body>

    <header class="header">
        <div class="header-logo">
            <a href="<?php echo BaseConfig::$BASE_URL; ?>home.php">
            <h1 class="logo">
                <span class="logo-career">Career</span><span class="logo-bridge">Bridge</span>
            </h1>
            </a>
        </div>

        <!-- Desktop -->
        <nav class="nav">
            <?php RoleBasedMenus::render($role_id, BaseConfig::$BASE_URL, $currentPath, 'web') ?>
        </nav>

        <!-- Desktop -->
        <div class="header-icons">
            <?php if ($is_logged_in): ?>
                <span class="icon" title="<?php echo $username; ?>"><img src="<?php echo BaseConfig::$BASE_URL; ?>assets/images/default_profile_image.png" class="menu-icon" alt="profile" /></span>
                <a href="<?php echo BaseConfig::$BASE_URL; ?>logout.php"><span class="icon"><img src="<?php echo BaseConfig::$BASE_URL; ?>assets/images/logout.png" class="menu-icon" alt="logout" /></span></a>
            <?php else: ?>
                <button class="buttn buttn-outline" onclick="location.href='<?php echo BaseConfig::$BASE_URL; ?>Employer/register.php'">Register as Employer</button>
                <button class="buttn buttn-sign-in" onclick="location.href='<?php echo BaseConfig::$BASE_URL; ?>login.php'">Sign In</button>
            <?php endif; ?>
        </div>

        <!-- Mobile -->
        <div class="hamburger" id="menuBtn" onclick="toggleMenu()">☰</div>
    </header>

    <!-- Mobile Sidebar Menu -->
    <div class="mobile-menu" id="mobileMenu">
        <a href="<?php echo BaseConfig::$BASE_URL; ?>home.php" class="menu-item">Home</a>

        <?php RoleBasedMenus::render($role_id, BaseConfig::$BASE_URL, $currentPath, 'mobile') ?>

        <hr>

        <?php if ($is_logged_in): ?>
            <a href="#" class="menu-item side-menu-row"><img src="<?php echo BaseConfig::$BASE_URL; ?>assets/images/default_profile_image.png" class="menu-icon" alt="profile" /> <?php echo $username; ?></a>
            <a href="<?php echo BaseConfig::$BASE_URL; ?>logout.php" class="menu-item side-menu-row"><img src="<?php echo BaseConfig::$BASE_URL; ?>assets/images/logout.png" class="menu-icon" alt="logout" /> Logout</a>
        <?php else: ?>
            
            <button class="buttn buttn-outline" onclick="location.href='<?php echo BaseConfig::$BASE_URL; ?>Employer/register.php'">Register as Employer</button>
            <button class="buttn buttn-sign-in" onclick="location.href='<?php echo BaseConfig::$BASE_URL; ?>login.php'">Sign In</button>
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