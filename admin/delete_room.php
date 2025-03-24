<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include '../config/db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: manage_rooms.php");
    exit();
}

$room_id = $_GET['id'];

// Delete the room
$stmt = $conn->prepare("DELETE FROM rooms WHERE room_id = ?");
$stmt->bind_param("i", $room_id);

if ($stmt->execute()) {
    header("Location: manage_rooms.php");
    exit();
} else {
    echo "Error deleting room!";
}
?>
