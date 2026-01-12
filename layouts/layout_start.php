<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/baseConfig.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/roleBasedMenus.php';
?>
<!DOCTYPE html>

<head>
    <meta charset="utf-8">

    <title><?= AppConstants::APP_NAME ?> | Job Recruitment System & Online Job Portal</title>

    <meta name="description" content="JobBoard+ is an all-in-one job recruitment system for posting jobs, managing candidates, and simplifying the hiring process for employers and job seekers.">
    <meta name="keywords" content="job board, job recruitment system, online hiring platform, job portal, recruitment software, career platform, employer hiring, job search">
    <meta name="author" content="JobBoard+">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0">
    <link rel="icon"  type="image/png" href="<?php echo BaseConfig::$BASE_URL; ?>assets/images/favicon.png">
    <link rel="stylesheet" href="<?php echo BaseConfig::$BASE_URL; ?>assets/css/header.css">

