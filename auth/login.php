<?php
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

$user = $result->fetch_assoc();

if($user && password_verify($password, $user["password"])){
    $_SESSION["user_id"] = $user["id"];
    echo "Login successful";
}else{
    echo "Kredencialet nuk jane valide";
}
?>
<form method="POST">
    <input name="email" placeholder="Email"><br>
    <input name="password" type="password" placeholder="Password"><br>
    <button type="submit">Login</button>
</form>