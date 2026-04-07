<?php
session_start();

$isLoggedIn = isset($_SESSION["user_id"]);
$userName = trim($_SESSION["user_name"] ?? "");
$status = $_GET["status"] ?? "";
$message = "";
$messageClass = "message message--info";

if ($status === "logged_in") {
    $message = $userName !== "" ? "Logged in as " . $userName . "." : "Login successful.";
    $messageClass = "message message--success";
} elseif ($status === "logged_out") {
    $message = "You have been logged out.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management</title>
    <link rel="stylesheet" href="assets/site.css">
</head>
<body>
    <div class="site-shell">
        <header class="topbar">
            <div class="brand">
                <span class="brand__eyebrow">PHP Backend Demo</span>
                <span class="brand__title">Event Management System</span>
            </div>
            <nav class="topbar__nav">
                <a class="nav-link" href="event/index.php">View Events</a>
                <?php if ($isLoggedIn): ?>
                    <a class="nav-link" href="event/create.php">Create Event</a>
                    <a class="button button--secondary" href="auth/logout.php">Logout</a>
                <?php else: ?>
                    <a class="nav-link" href="auth/register.php">Register</a>
                    <a class="button button--secondary" href="auth/login.php">Login</a>
                <?php endif; ?>
            </nav>
        </header>

        <?php if ($message !== ""): ?>
            <div class="<?php echo $messageClass; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <section class="hero">
            <div class="hero__content">
                <span class="eyebrow">Simple Frontend</span>
                <h1>Show the backend features without extra complexity.</h1>
                <p>This small frontend is only here to demonstrate the main PHP flows: register, login, create an event, view events, and book an event.</p>
                <div class="actions">
                    <a class="button" href="event/index.php">Open Event List</a>
                    <?php if ($isLoggedIn): ?>
                        <a class="button button--ghost" href="event/create.php">Create Event</a>
                    <?php else: ?>
                        <a class="button button--ghost" href="auth/register.php">Register User</a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="mini-panel">
                <h3>Backend actions you can demo</h3>
                <ul class="feature-list">
                    <li>User registration</li>
                    <li>User login with session</li>
                    <li>Protected event creation</li>
                    <li>Database-driven event listing</li>
                    <li>Booking an event</li>
                </ul>
            </div>
        </section>

        <section class="section">
            <div class="card-grid">
                <article class="card">
                    <h3>1. Register</h3>
                    <p>Create a user account that gets stored in the database.</p>
                    <a class="button button--secondary" href="auth/register.php">Go to Register</a>
                </article>
                <article class="card">
                    <h3>2. Login</h3>
                    <p>Start a session so protected pages can be accessed.</p>
                    <a class="button button--secondary" href="auth/login.php">Go to Login</a>
                </article>
                <article class="card">
                    <h3>3. Create Event</h3>
                    <p>Add a new event from a simple form.</p>
                    <a class="button button--secondary" href="event/create.php">Go to Create Event</a>
                </article>
                <article class="card">
                    <h3>4. View and Book</h3>
                    <p>See events from the database and book one.</p>
                    <a class="button button--secondary" href="event/index.php">Go to Events</a>
                </article>
            </div>
        </section>
    </div>
</body>
</html>
