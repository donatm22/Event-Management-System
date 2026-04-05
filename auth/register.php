<?php 
    include("config/db.php");

    if($_SERVER["REQUEST METHOD"] == "POST"){
        $name = $_POST["name"];
        $email = $_POST["email"];
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
            
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $password);
        
        if($stmt->execute()){
            echo "Perdoruesi u regjistrua!";
        }else{
            echo "Error: " . $stmt->error;
        }
        }
?>
<form method="POST">
    <input name="name" placeholder="Name"><br>
    <input name="email" placeholder="Email"><br>
    <input name="password" placeholder="Password"><br>
    <button type="submit">Register</button>
</form>