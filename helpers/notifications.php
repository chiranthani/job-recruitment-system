<?php

function createNotification($con, $userId, $senderId, $applicationId, $type, $message)
{
    $sql = "INSERT INTO notifications (user_id, sender_id, application_id, type, message)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $con->prepare($sql);
    if (!$stmt) {
        return false;
    }

    $stmt->bind_param(
        "iiiss",
        $userId,
        $senderId,
        $applicationId,
        $type,
        $message
    );

    return $stmt->execute();
}

?>