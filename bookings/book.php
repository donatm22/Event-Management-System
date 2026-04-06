<?php
include("../includes/auth_check.php");
include("../config/db.php");

$user_id = $_SESSION["user_id"];
$event_id = $_GET["event_id"];

$stmt = $conn->prepare("INSERT INTO bookings (user_id, event_id) VALUES (?, ?)");
$stmt->bind_param("ii", $user_id, $event_id);
$stmt->execute();

echo "Booked!";
?>