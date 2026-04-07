<?php
include("../includes/auth_check.php");
include("../config/db.php");

$user_id = (int) $_SESSION["user_id"];
$event_id = (int) ($_GET["event_id"] ?? 0);

$checkStmt = $conn->prepare("SELECT id FROM bookings WHERE user_id = ? AND event_id = ?");
$checkStmt->bind_param("ii", $user_id, $event_id);
$checkStmt->execute();
$existingBooking = $checkStmt->get_result()->fetch_assoc();

if ($existingBooking) {
    header("Location: ../event/index.php?status=duplicate");
    exit;
}

$stmt = $conn->prepare("INSERT INTO bookings (user_id, event_id) VALUES (?, ?)");
$stmt->bind_param("ii", $user_id, $event_id);

if ($stmt->execute()) {
    header("Location: ../event/index.php?status=booked");
    exit;
}

header("Location: ../event/index.php?status=missing");
exit;
?>
