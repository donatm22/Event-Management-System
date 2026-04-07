<?php
session_start();
include("../config/db.php");

$message = "";
$messageClass = "message message--error";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $rawPassword = $_POST["password"] ?? "";

    if ($name === "" || $email === "" || $rawPassword === "") {
        $message = "Please fill in all fields.";
    } else {
        $password = password_hash($rawPassword, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $password);

        if ($stmt->execute()) {
            header("Location: ../auth/login.php?message=" . urlencode("Registration complete. You can log in now."));
            exit;
        }

        $message = "Could not register user: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../assets/site.css">
</head>
<body>
    <div class="site-shell">
        <header class="topbar">
            <div class="brand">
                <span class="brand__eyebrow">Authentication</span>
                <span class="brand__title">Register</span>
            </div>
            <nav class="topbar__nav">
                <a class="nav-link" href="../index.php">Home</a>
                <a class="nav-link" href="../auth/login.php">Login</a>
                <a class="button button--secondary" href="../event/index.php">View Events</a>
            </nav>
        </header>

        <section class="form-card">
            <h2>Register a user</h2>
            <p>Use this page to create a user record in the database.</p>

            <?php if ($message !== ""): ?>
                <div class="<?php echo $messageClass; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <form class="form" method="POST">
                <div class="field">
                    <label for="name">Name</label>
                    <input class="input" id="name" name="name" type="text" required>
                </div>
                <div class="field">
                    <label for="email">Email</label>
                    <input class="input" id="email" name="email" type="email" required>
                </div>
                <div class="field">
                    <label for="password">Password</label>
                    <input class="input" id="password" name="password" type="password" required>
                </div>
                <button class="button" type="submit">Register</button>
            </form>
        </section>
    </div>
</body>
</html>
