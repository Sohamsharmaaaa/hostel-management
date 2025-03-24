<?php
// Include database connection
include('../config/db_connect.php');

// Check if the user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Get the user ID from the session
$user_id = $_SESSION['user_id'];

// Get the booking details from the database
$booking_query = "SELECT b.booking_id, b.start_date, b.end_date, b.payment_status, r.room_type, r.price 
                  FROM bookings b
                  JOIN rooms r ON b.room_id = r.room_id
                  WHERE b.user_id = ? AND b.payment_status = 'pending'";

$stmt = $conn->prepare($booking_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$booking_result = $stmt->get_result();

// Fetch booking details
$booking = $booking_result->fetch_assoc();

if (!$booking) {
    echo "No active booking found.";
    exit();
}

// Get start and end dates of the booking
$start_date = strtotime($booking['start_date']);
$end_date = strtotime($booking['end_date']);
$room_price = $booking['price']; // Monthly price

// Calculate the number of months for the booking
$months = ceil(($end_date - $start_date) / (30 * 24 * 60 * 60)); // Calculate the number of months

// Calculate the total cost
$total_cost = $room_price * $months;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Page</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Booking Details</h1>
        
        <p><strong>Room Type:</strong> <?php echo $booking['room_type']; ?></p>
        <p><strong>Check-in Date:</strong> <?php echo date('Y-m-d', $start_date); ?></p>
        <p><strong>Check-out Date:</strong> <?php echo date('Y-m-d', $end_date); ?></p>
        <p><strong>Total Cost:</strong> ₹<?php echo number_format($total_cost, 2); ?></p>
        <p><strong>Status:</strong> <?php echo ucfirst($booking['payment_status']); ?></p>

        <h2>Payment Details</h2>
        
        <?php
        // Check if there are any payments for this booking
        $payment_query = "SELECT * FROM payments WHERE booking_id = ?";
        $payment_stmt = $conn->prepare($payment_query);
        $payment_stmt->bind_param("i", $booking['booking_id']);
        $payment_stmt->execute();
        $payment_result = $payment_stmt->get_result();
        
        if ($payment_result->num_rows > 0) {
            $payment = $payment_result->fetch_assoc();
            echo "<p><strong>Payment Status:</strong> " . ucfirst($payment['payment_status']) . "</p>";
            echo "<p><strong>Amount Paid:</strong> ₹" . number_format($payment['amount'], 2) . "</p>";
            echo "<p><strong>Payment Date:</strong> " . date('Y-m-d H:i:s', strtotime($payment['payment_date'])) . "</p>";
        } else {
            echo "<p>No payment found for this booking.</p>";
        }
        ?>
        
        <h2>Make Payment</h2>
        <?php
        // Check if payment status is 'pending'
        if ($booking['payment_status'] == 'pending') {
            echo '<form action="process_payment.php" method="POST">
                    <input type="hidden" name="booking_id" value="' . $booking['booking_id'] . '">
                    <input type="hidden" name="total_cost" value="' . $total_cost . '">
                    <button type="submit">Proceed to Payment</button>
                  </form>';
        }
        ?>
    </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
