<?php
include("../includes/auth_check.php");
include("../config/db.php");

$message = "";
$messageClass = "message message--error";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST["title"] ?? "");
    $desc = trim($_POST["description"] ?? "");
    $location = trim($_POST["location"] ?? "");
    $capacity = (int) ($_POST["capacity"] ?? 0);
    $user_id = (int) $_SESSION["user_id"];

    if ($title === "" || $desc === "" || $location === "" || $capacity < 1) {
        $message = "Please fill in all fields.";
    } else {
        $stmt = $conn->prepare("INSERT INTO events (title, description, location, capacity, organizer_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssii", $title, $desc, $location, $capacity, $user_id);

        if ($stmt->execute()) {
            header("Location: ../event/index.php?status=created");
            exit;
        }

        $message = "Could not create event: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event</title>
    <link rel="stylesheet" href="../assets/site.css">
</head>
<body>
    <div class="site-shell">
        <header class="topbar">
            <div class="brand">
                <span class="brand__eyebrow">Protected Page</span>
                <span class="brand__title">Create Event</span>
            </div>
            <nav class="topbar__nav">
                <a class="nav-link" href="../index.php">Home</a>
                <a class="nav-link" href="../event/index.php">View Events</a>
                <a class="button button--secondary" href="../auth/logout.php">Logout</a>
            </nav>
        </header>

        <section class="form-card">
            <h2>Create a new event</h2>
            <p>This form inserts a record into the `events` table.</p>

            <?php if ($message !== ""): ?>
                <div class="<?php echo $messageClass; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <form class="form" method="POST">
                <div class="field">
                    <label for="title">Title</label>
                    <input class="input" id="title" name="title" type="text" required>
                </div>
                <div class="field">
                    <label for="description">Description</label>
                    <textarea class="textarea" id="description" name="description" required></textarea>
                </div>
                <div class="field">
                    <label for="location">Location</label>
                    <input class="input" id="location" name="location" type="text" required>
                </div>
                <div class="field">
                    <label for="capacity">Capacity</label>
                    <input class="input" id="capacity" name="capacity" type="number" min="1" required>
                </div>
                <button class="button" type="submit">Create Event</button>
            </form>
        </section>
    </div>
</body>
</html>
