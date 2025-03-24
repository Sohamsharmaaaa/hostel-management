<?php
session_start();
include '../config/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = $_POST['role']; // Get role from the form

    // Prepare query to check role-based login
    $stmt = $conn->prepare("SELECT user_id, password, role FROM users WHERE email = ? AND status = 'active' AND role = ?");
    $stmt->bind_param("ss", $email, $role);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {
                header("Location: ../admin/dashboard.php");
            } else {
                header("Location: ../user/dashboard.php");
            }
            exit;
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "User not found or inactive!";
    }
}
?>
<link rel="stylesheet" href="../assets/css/auth.css">
<h1>Login</h1>
<form method="post">
    <input type="email" name="email" placeholder="Email Address" required>
    <input type="password" name="password" placeholder="Password" required>

    <div class="role-container">
    <p>Select Role:</p>
    <input type="radio" id="user" name="role" value="user" checked>
    <label for="user">User</label>

    <input type="radio" id="admin" name="role" value="admin">
    <label for="admin">Admin</label>
</div>

 

    <button type="submit">Login</button>
    <p>Don't have an account? <a href="register.php">Register</a></p><br>
    <h3><a href="../index.php">&#8592; Back to home</a></h3>

</form>
