<?php
include '../../config/database.php';
include '../../config/constants.php';

$ids = $_POST['ids'] ?? '';
if (!$ids) {
    echo json_encode(["status" => "error", 'message' => 'No applications selected']);
    exit;
}

$idArray = explode(",", $ids);
$idPlaceholders = implode(",", array_fill(0, count($idArray), "?"));

$types = "s" . str_repeat("i", count($idArray)); 

$sql = "UPDATE applications SET application_status = ? WHERE id IN ($idPlaceholders)";
$stmt = $con_main->prepare($sql);

$status = AppConstants::APPLICATION_STATUS['IN_REVIEW']; 
$stmt->bind_param("{$types}", ...array_merge([$status], $idArray));
$stmt->execute();

echo json_encode(["status" => "success","message"=>"Selected applications marked as Reviewed."]);
