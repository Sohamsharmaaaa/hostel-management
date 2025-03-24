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

// Update payment_status to 'paid'
$stmt = $conn->prepare("UPDATE bookings SET payment_status = 'paid' WHERE booking_id = ?");
$stmt->bind_param("i", $booking_id);

if ($stmt->execute()) {
    $_SESSION['success'] = "Booking approved successfully!";
} else {
    $_SESSION['error'] = "Error approving booking.";
}

header("Location: manage_bookings.php");
exit();
?>
