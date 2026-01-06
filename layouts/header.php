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
                <img src="<?php echo BaseConfig::$BASE_URL; ?>assets/images/logo.png" width="130px" />
            </a>
        </div>

        <!-- Desktop -->
        <nav class="nav">
            <?php RoleBasedMenus::render($role_id, BaseConfig::$BASE_URL, $currentPath, 'web') ?>
        </nav>

        <!-- Desktop -->
        <div class="header-icons">
            <?php if ($is_logged_in): ?>
                <div class="notification-wrapper">
                    <img src="<?php echo BaseConfig::$BASE_URL; ?>assets/images/bell.png" 
                        id="bellBtn" 
                        alt="Notifications" 
                        style="width:24px; height:24px; cursor:pointer;" />

                  
                    <span id="notificationCount" class="count-badge" style="display:none;">0</span>

                    <!-- dropdown -->
                    <div class="notification-dropdown" id="notificationDropdown">
                        <div class="notification-header">Notifications</div>
                        <ul id="notificationList">
                            <li class="loading">Loading...</li>
                        </ul>
                        <div class="notification-footer">
                            <a href="<?php echo BaseConfig::$BASE_URL; ?>notifications.php">View all</a>
                        </div>
                    </div>
                </div>

                <span class="icon" title="<?php echo $username; ?>"><img src="<?php echo BaseConfig::$BASE_URL; ?>assets/images/default_profile_image.png" class="menu-icon" alt="profile" /></span>
                <a href="<?php echo BaseConfig::$BASE_URL; ?>logout.php"><span class="icon"><img src="<?php echo BaseConfig::$BASE_URL; ?>assets/images/logout.png" class="menu-icon" alt="logout" /></span></a>
            <?php else: ?>
                                <button class="buttn buttn-outline" onclick="location.href='<?php echo BaseConfig::$BASE_URL; ?>user-management/signup.php'">Register as Job Seeker</button>
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

        var bell = document.getElementById('bellBtn');
        var dropdown = document.getElementById('notificationDropdown');

        bell.onclick = function (e) {
            e.stopPropagation();
            dropdown.style.display = dropdown.style.display == 'block' ? 'none' : 'block';

            if (dropdown.style.display == 'block') {
                loadNotifications();
            }
        };

        document.addEventListener('click', function () {
            dropdown.style.display = 'none';
        });

        function fetchNotificationCount() {
            var xhr = new XMLHttpRequest();
            xhr.open("GET","<?php echo BaseConfig::$BASE_URL; ?>job-applicant/backend/notification-unread-count.php",true);
            xhr.onload = function () {
                if (xhr.status == 200) {
                    var res = JSON.parse(xhr.responseText);
                    var badge = document.getElementById('notificationCount');

                    if (res.count > 0) {
                        badge.style.display = 'inline-block';
                        badge.innerText = res.count;
                    } else {
                        badge.style.display = 'none';
                    }
                }
            };
            xhr.send();
        }


        function loadNotifications() {
            var xhr = new XMLHttpRequest();
            xhr.open("GET","<?php echo BaseConfig::$BASE_URL; ?>job-applicant/backend/notifications-list.php",true);

            xhr.onload = function () {
                if (xhr.status == 200) {
                    var list = document.getElementById('notificationList');
                    var data = JSON.parse(xhr.responseText);

                    list.innerHTML = '';

                    if (!data.notifications.length) {
                        list.innerHTML = '<li>No notifications</li>';
                        return;
                    }

                    data.notifications.forEach(function (n) {
                        var li = document.createElement('li');
                        li.className = n.is_read == 0 ? 'unread' : '';
                        li.innerHTML = '<span>'+ n.message +'<span>';
                        list.appendChild(li);
                    });

                    notificationMarkAsRead();
                }
            };
            xhr.send();


        }

        function notificationMarkAsRead() {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "<?php echo BaseConfig::$BASE_URL; ?>job-applicant/backend/notifications-mark-as-read.php", true);
            xhr.send();
        }

        fetchNotificationCount();
        setInterval(fetchNotificationCount, 10000);
    </script>