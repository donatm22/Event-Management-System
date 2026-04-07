<?php
session_start();
include("../config/db.php");

$message = $_GET["message"] ?? "";
$messageClass = $message !== "" ? "message message--info" : "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user["password"])) {
        $_SESSION["user_id"] = (int) $user["id"];
        $_SESSION["user_name"] = $user["name"] ?? "";

        header("Location: ../index.php?status=logged_in");
        exit;
    }

    $message = "Invalid email or password.";
    $messageClass = "message message--error";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../assets/site.css">
</head>
<body>
    <div class="site-shell">
        <header class="topbar">
            <div class="brand">
                <span class="brand__eyebrow">Authentication</span>
                <span class="brand__title">Login</span>
            </div>
            <nav class="topbar__nav">
                <a class="nav-link" href="../index.php">Home</a>
                <a class="nav-link" href="../auth/register.php">Register</a>
                <a class="button button--secondary" href="../event/index.php">View Events</a>
            </nav>
        </header>

        <section class="form-card">
            <h2>Login</h2>
            <p>Use this page to show the backend login and session flow.</p>

            <?php if ($message !== ""): ?>
                <div class="<?php echo $messageClass; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <form class="form" method="POST">
                <div class="field">
                    <label for="email">Email</label>
                    <input class="input" id="email" name="email" type="email" required>
                </div>
                <div class="field">
                    <label for="password">Password</label>
                    <input class="input" id="password" name="password" type="password" required>
                </div>
                <button class="button" type="submit">Login</button>
            </form>
        </section>
    </div>
</body>
</html>
