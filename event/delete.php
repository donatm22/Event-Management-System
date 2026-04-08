<?php 
    include("../Event_Management//includes/auth_check.php");
    include("../Event_Management/config/db.php");

    if(!isset($_GET['id'])){
        die("Nuk ka ID te eventit");
    }

    $event_id = $_GET['id'];

    $stmt = $conn->prepare("SELECT organizer_id FROM events WHERE id = ?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();

$event = $result->fetch_assoc();

if (!$event) {
    die("Eventi nuk u gjet!");
}

if ($event['organizer_id'] != $_SESSION['user_id']) {
    die("Unauthorized");
}

$stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
$stmt->bind_param("i", $event_id);

if ($stmt->execute()) {
    echo "Event u fshi me sukses! <br>";
    echo '<a href="index.php">Back to events</a>';
} else {
    echo "Problem gjat fshirjes se eventit";
}


?>