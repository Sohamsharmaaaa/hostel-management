<?php
session_start();
include '../config/db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: manage_bookings.php");
    exit();
}

$booking_id = $_GET['id'];

// Delete booking record
$stmt = $conn->prepare("DELETE FROM bookings WHERE booking_id = ?");
$stmt->bind_param("i", $booking_id);

if ($stmt->execute()) {
    $_SESSION['success'] = "Booking deleted successfully!";
} else {
    $_SESSION['error'] = "Error deleting booking.";
}

header("Location: manage_bookings.php");
exit();
?>
