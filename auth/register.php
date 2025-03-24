<?php
include '../config/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Secure password hashing

    // Check if email already exists
    $check_email = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    $result = $check_email->get_result();

    if ($result->num_rows > 0) {
        echo "Email already registered!";
    } else {
        // Insert new user
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, status) VALUES (?, ?, ?, 'active')");
        $stmt->bind_param("sss", $name, $email, $password);

        if ($stmt->execute()) {
            echo ("<SCRIPT LANGUAGE='JavaScript'>            
            window.location.href='login.php';
                   </SCRIPT>");
        } else {
            echo "Error: " . $conn->error;
        }
    }
}
?>
<link rel="stylesheet" href="../assets/css/auth.css">
<h1>Register</h1>
<form method="post">
    
    <input type="text" name="name" placeholder="Full Name" required>
    <input type="email" name="email" placeholder="Email Address" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Register</button>
    <p>Already have an account? <a href="login.php">Login</a></p><br>
    <h3><a href="../index.php">&#8592; Back to home</a></h3>
</form>
