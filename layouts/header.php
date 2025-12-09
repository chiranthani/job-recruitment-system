

</head>
<body>
<?php $currentPath = basename($_SERVER['PHP_SELF']); ?>

<header class="header">
    <div class="header-logo">
        <span class="logo-icon">[ ]</span>
        <span class="logo-text">Jobs XX</span>
    </div>

    <!-- after login menus handle with php login session [role based menu permission] -->
    <!-- Desktop -->
    <nav class="nav">
        <!-- candidate -->
        <a class="<?php echo ($currentPath == 'home.php') ? 'active' : ''; ?>" href="<?php echo BaseConfig::$BASE_URL; ?>home.php">Home</a>
        <a class="<?php echo ($currentPath == 'job-search.php') ? 'active' : ''; ?>" href="<?php echo BaseConfig::$BASE_URL; ?>job-applicant/job-search.php">Find A Job</a>
        <!-- after login session active -->
        <!-- <a class="<?php echo ($currentPath == 'my-jobs.php') ? 'active' : ''; ?>" href="<?php echo BaseConfig::$BASE_URL; ?>job-applicant/my-jobs.php">My Jobs</a> -->

        <!-- recruiter/employer -->
        <!-- <a href="#">Dashboard</a>
       <a class="<?php echo ($currentPath == 'job_list.php') ? 'active' : ''; ?>" href="<?php echo BaseConfig::$BASE_URL; ?>Jobs/job_list.php">Job Posts</a>
        <a href="#">Applications</a>
        <a href="#">Company</a> -->
       
        <!-- Admin -->
         <!-- <a href="#">A Dashboard</a>
         <a href="#">Users</a>
         <a href="#">Companies</a> -->

        <!-- common-->
        <a class="<?php echo ($currentPath == 'job-search.php') ? 'active' : ''; ?>" href="<?php echo BaseConfig::$BASE_URL; ?>job-applicant/job-search.php">Help</a>

    </nav>

    <!-- Desktop -->
    <div class="header-icons">
        <button class="buttn buttn-outline">Sign Up</button> 
        <button class="buttn buttn-sign-in">Sign In</button> 

        <!-- after login session active -->
        <!-- <span class="icon"><img src="<?php echo BaseConfig::$BASE_URL; ?>assets/images/default_profile_image.png" class="menu-icon" alt="profile" /></span>
        <span class="icon"><img src="<?php echo BaseConfig::$BASE_URL; ?>assets/images/logout.png" class="menu-icon" alt="logout" /></span> -->
    </div>

    <!-- Mobile -->
    <div class="hamburger" id="menuBtn" onclick="toggleMenu()">☰</div>
</header>

<!-- Mobile Sidebar Menu -->
<div class="mobile-menu" id="mobileMenu">
    <a href="#" class="menu-item active">Home</a>
    <a href="#" class="menu-item">My Jobs</a>
    <a href="#" class="menu-item">Help</a>
    <hr>
    <button class="buttn buttn-outline">Sign Up</button> 
    <button class="buttn buttn-sign-in">Sign In</button>
    <!-- after login session active --> 
    <!-- <a href="#" class="menu-item side-menu-row"><img src="<?php echo BaseConfig::$BASE_URL; ?>assets/images/default_profile_image.png" class="menu-icon" alt="profile" /> Profile</a>
    <a href="#" class="menu-item side-menu-row"><img src="<?php echo BaseConfig::$BASE_URL; ?>assets/images/logout.png" class="menu-icon" alt="logout" /> Logout</a> -->
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