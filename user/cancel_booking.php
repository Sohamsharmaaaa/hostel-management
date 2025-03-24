<?php
include '../config/db_connect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $booking_id = $_POST['booking_id'];

    // Get room_id associated with this booking
    $query = "SELECT room_id FROM bookings WHERE booking_id = '$booking_id'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $room_id = $row['room_id'];

    // Delete the booking
    $delete_query = "DELETE FROM bookings WHERE booking_id = '$booking_id'";
    if (mysqli_query($conn, $delete_query)) {
        // Update room availability
        $update_room = "UPDATE rooms SET availability = 'available' WHERE room_id = '$room_id'";
        mysqli_query($conn, $update_room);

        $_SESSION['message'] = "Booking cancelled successfully!";
    } else {
        $_SESSION['message'] = "Error cancelling booking.";
    }

    header("Location: my_bookings.php");
    exit();
} else {
    header("Location: my_bookings.php");
    exit();
}
?>
