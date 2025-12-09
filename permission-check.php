<?php
session_start();

include 'config/permissions.php';

$currentPath = $_SERVER['PHP_SELF'];
$userRole    = $_SESSION["user_role"];
$allowed     = true;


foreach ($ACCESS_RULES as $path => $roles) {

    if (strpos($currentPath, $path) != false) {
        if (!in_array($userRole, $roles)) {
            $allowed = false;
        }
        break;
    }
}


if (!$allowed) {
    header("Location: " . BaseConfig::$BASE_URL . "access-denied.php");
    exit();
}
