<?php
session_start();
include '../config/db_connect.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Fetch current status
    $stmt = $conn->prepare("SELECT status FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($status);
    $stmt->fetch();
    $stmt->close();

    // Toggle status
    $new_status = ($status == 'active') ? 'inactive' : 'active';

    // Update status
    $stmt = $conn->prepare("UPDATE users SET status = ? WHERE user_id = ?");
    $stmt->bind_param("si", $new_status, $user_id);
    $stmt->execute();
    $stmt->close();

    $_SESSION['message'] = "User status updated successfully!";
    header("Location: manage_users.php");
    exit();
}
?>
