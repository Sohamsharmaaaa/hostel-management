<?php
session_start();
include '../config/db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user bookings using a prepared statement
$sql = "SELECT b.booking_id, r.room_number, r.price, b.start_date, b.end_date, b.payment_status 
        FROM bookings b 
        JOIN rooms r ON b.room_id = r.room_id 
        WHERE b.user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings</title>
    <link rel="stylesheet" href="../assets/css/my_bookings.css">
</head>
<body>
    <div class="sidebar">
        <h2>Hostel System</h2>
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="book_room.php"><i class="fas fa-bed"></i> Book Room</a></li>
            <li><a href="my_bookings.php"><i class="fas fa-list"></i> My Bookings</a></li>
            <li><a href="complaints.php"><i class="fas fa-exclamation-circle"></i> Complaints</a></li>
            <li><a href="profile.php"><i class="fas fa-user"></i> Profile</a></li>
            <li><a href="../auth/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>
    
    <div class="main-content">
        <h1>My Bookings</h1>
        <p>Here you can view your room bookings.</p>

        <!-- Success/Error Messages -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert"><?= $_SESSION['message']; unset($_SESSION['message']); ?></div>
        <?php endif; ?>

        <table class="booking-table">
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Room Number</th>
                    <th>Price</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Payment Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$row['booking_id']}</td>";
                        echo "<td>{$row['room_number']}</td>";
                        echo "<td>₹" . number_format($row['price'], 2) . "</td>"; // Display price
                        echo "<td>{$row['start_date']}</td>";
                        echo "<td>{$row['end_date']}</td>";
                        echo "<td><span class='status " . ($row['payment_status'] == 'completed' ? 'paid' : 'pending') . "'>" . ucfirst($row['payment_status']) . "</span></td>";
                        echo "<td>";
                        
                        // Show Cancel button only if payment is pending
                        if ($row['payment_status'] == 'pending') {
                            echo "<form action='cancel_booking.php' method='POST' onsubmit='return confirm(\"Are you sure you want to cancel this booking?\")'>";
                            echo "<input type='hidden' name='booking_id' value='{$row['booking_id']}'>";
                            echo "<button type='submit' class='cancel-btn'>Cancel</button>";
                            echo "</form>";
                        } else {
                            echo "<span style='color:gray;'>Not Allowed</span>";
                        }

                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No bookings found.</td></tr>";
                }

                $stmt->close();
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>

