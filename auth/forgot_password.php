<?php
include '../config/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);

    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // In real case, send an email instead of showing a reset link
        echo "Reset link: <a href='reset_password.php?email=$email'>Click here</a>";
    } else {
        echo "Email not found!";
    }
}
?>

<form method="post">
    <input type="email" name="email" placeholder="Enter your email" required>
    <button type="submit">Reset Password</button>
</form>
