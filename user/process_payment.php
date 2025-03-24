<?php
session_start();
include '../config/db_connect.php';

// Check if the user has clicked the payment button
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $booking_id = $_POST['booking_id'];

    // Update the booking payment status to 'completed'
    $update_query = "UPDATE bookings SET payment_status = 'completed' WHERE booking_id = '$booking_id'";
    $update_result = mysqli_query($conn, $update_query);
    
    if ($update_result) {
        // Record the payment details in the payments table
        $amount = 1000; // Assuming a fixed amount for now
        $payment_date = date('Y-m-d H:i:s');
        $payment_query = "INSERT INTO payments (booking_id, amount, payment_date, payment_status) 
                          VALUES ('$booking_id', '$amount', '$payment_date', 'successful')";
        mysqli_query($conn, $payment_query);
        
        // Redirect to payment page with success message
        header('Location: payment.php?status=success');
        exit();
    } else {
        // Redirect to payment page with error message
        header('Location: payment.php?status=error');
        exit();
    }
}
?>
