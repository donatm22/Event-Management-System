<?php
include("../config/db.php");
session_start();

$isLoggedIn = isset($_SESSION["user_id"]);
$status = $_GET["status"] ?? "";
$message = "";
$messageClass = "message message--info";

if ($status === "created") {
    $message = "Event created successfully.";
    $messageClass = "message message--success";
} elseif ($status === "booked") {
    $message = "Booking completed.";
    $messageClass = "message message--success";
} elseif ($status === "duplicate") {
    $message = "You already booked this event.";
} elseif ($status === "missing") {
    $message = "Event not found.";
    $messageClass = "message message--error";
}

$sql = "
    SELECT e.id, e.title, e.description, e.location, e.capacity, COALESCE(COUNT(b.id), 0) AS booked_total
    FROM events e
    LEFT JOIN bookings b ON b.event_id = e.id
    GROUP BY e.id, e.title, e.description, e.location, e.capacity
    ORDER BY e.id DESC
";

$result = $conn->query($sql);
$events = [];

if ($result instanceof mysqli_result) {
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events</title>
    <link rel="stylesheet" href="../assets/site.css">
</head>
<body>
    <div class="site-shell">
        <header class="topbar">
            <div class="brand">
                <span class="brand__eyebrow">Database Output</span>
                <span class="brand__title">Event List</span>
            </div>
            <nav class="topbar__nav">
                <a class="nav-link" href="../index.php">Home</a>
                <?php if ($isLoggedIn): ?>
                    <a class="nav-link" href="../event/create.php">Create Event</a>
                    <a class="button button--secondary" href="../auth/logout.php">Logout</a>
                <?php else: ?>
                    <a class="nav-link" href="../auth/register.php">Register</a>
                    <a class="button button--secondary" href="../auth/login.php">Login</a>
                <?php endif; ?>
            </nav>
        </header>

        <section class="form-card">
            <h2>Events from the backend</h2>
            <p>This page reads event records from the database and lets logged-in users book them.</p>

            <?php if ($message !== ""): ?>
                <div class="<?php echo $messageClass; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <?php if (count($events) === 0): ?>
                <p>No events found yet.</p>
            <?php else: ?>
                <div class="event-grid">
                    <?php foreach ($events as $event): ?>
                        <?php
                        $capacity = (int) $event["capacity"];
                        $bookedTotal = (int) $event["booked_total"];
                        $seatsLeft = max($capacity - $bookedTotal, 0);
                        ?>
                        <article class="event-card">
                            <div>
                                <span class="pill"><?php echo $seatsLeft; ?> seats left</span>
                                <h3><?php echo htmlspecialchars($event["title"]); ?></h3>
                                <p><?php echo htmlspecialchars($event["description"]); ?></p>
                            </div>
                            <ul class="meta-list">
                                <li>Location: <?php echo htmlspecialchars($event["location"]); ?></li>
                                <li>Capacity: <?php echo $capacity; ?></li>
                                <li>Booked: <?php echo $bookedTotal; ?></li>
                            </ul>
                            <div class="event-card__footer">
                                <?php if ($isLoggedIn): ?>
                                    <a class="button" href="../bookings/book.php?event_id=<?php echo (int) $event["id"]; ?>">Book Event</a>
                                <?php else: ?>
                                    <a class="button button--secondary" href="../auth/login.php">Login to Book</a>
                                <?php endif; ?>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </div>
</body>
</html>
