<?php
include("../includes/auth_check.php");
include("../config/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $desc = $_POST["description"];
    $location = $_POST["location"];
    $capacity = $_POST["capacity"];
    $user_id = $_SESSION["user_id"];

    $stmt = $conn->prepare("INSERT INTO events (title, description, location, capacity, organizer_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssii", $title, $desc, $location, $capacity, $user_id);
    $stmt->execute();

    echo "Event created!";
}
?>

<form method="POST">
    <input name="title" placeholder="Title"><br>
    <input name="description" placeholder="Description"><br>
    <input name="location" placeholder="Location"><br>
    <input name="capacity" type="number" placeholder="Capacity"><br>
    <button type="submit">Create Event</button>
</form>