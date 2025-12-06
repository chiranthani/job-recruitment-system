<?php 
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "job-recruitment-system";

$con_main = new mysqli($servername, $username, $password, $dbname);

if ($con_main->connect_error) {
    die("Connection failed: " . $con_main->connect_error);
}
?>