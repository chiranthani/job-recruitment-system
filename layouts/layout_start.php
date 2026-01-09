<?php
session_start();
include_once __DIR__ . '/../config/baseConfig.php';
include_once __DIR__ . '/../config/constants.php';
include_once __DIR__ . '/../config/roleBasedMenus.php';
?>
<!DOCTYPE html>

<head>
    <meta charset="utf-8">

    <title><?= AppConstants::APP_NAME ?> | Job Recruitment </title>

    <meta name="description" content="">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0">
    <link rel="shortcut icon" href="<?php echo BaseConfig::$BASE_URL; ?>assets/images/favicon.png">
    <link rel="stylesheet" href="<?php echo BaseConfig::$BASE_URL; ?>assets/css/header.css">

