<?php
session_start();
include '../config/db_connect.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$success_msg = $error_msg = "";

// Initialize room_id and booking details
$room_id = "";
$room_details = null;
$amount_for_one_month = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['room_id']) && !empty($_POST['start_date']) && !empty($_POST['end_date'])) {
        $room_id = intval($_POST['room_id']);  // Ensure it's an integer
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];

        // Insert booking
        $sql = "INSERT INTO bookings (user_id, room_id, start_date, end_date, payment_status) 
                VALUES (?, ?, ?, ?, 'pending')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiss", $user_id, $room_id, $start_date, $end_date);

        if ($stmt->execute()) {
            // Now update the room availability **only if the booking is successful**
            $conn->query("UPDATE rooms SET availability = 'booked' WHERE room_id = $room_id");

            // Fetch room details to calculate amount
            $sql = "SELECT * FROM rooms WHERE room_id = $room_id";
            $result = $conn->query($sql);
            $room_details = $result->fetch_assoc();

            // Calculate amount for one month (assuming it's for one month)
            $amount_for_one_month = $room_details['price'];

            $success_msg = "Room booked successfully!";
        } else {
            $error_msg = "Error booking the room.";
        }
    } else {
        $error_msg = "All fields are required!";
    }
}

// Fetch available rooms for selection
$sql = "SELECT * FROM rooms WHERE availability = 'available'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Room</title>
    <link rel="stylesheet" href="../assets/css/book_room.css">
</head>
<body>
    <?php include '../includes/navbar.php'; ?>

    <div class="container">
        <h2>Book a Room</h2>

        <?php if ($success_msg) echo "<p class='success'>$success_msg</p>"; ?>
        <?php if ($error_msg) echo "<p class='error'>$error_msg</p>"; ?>

        <!-- Room Booking Form -->
        <form method="POST">
            <div class="form-group">
                <label for="room_id">Select Room:</label>
                <select name="room_id" required>
                    <option value="">Choose a Room</option>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <option value="<?= $row['room_id']; ?>">
                            <?= "Room " . $row['room_number'] . " - " . $row['room_type'] . " (₹" . $row['price'] . ")"; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label for="start_date">Start Date:</label>
                <input type="date" name="start_date" required>
            </div>

            <div class="form-group">
                <label for="end_date">End Date:</label>
                <input type="date" name="end_date" required>
            </div>

            <button type="submit">Book Now</button>
        </form>

        <!-- Booking Summary if room is booked -->
        <?php if ($room_details) { ?>
            <div class="booking-summary">
                <h3>Booking Summary</h3>
                <table class="room-details-table">
                    <tr>
                        <th>Room Number</th>
                        <td><?= $room_details['room_number']; ?></td>
                    </tr>
                    <tr>
                        <th>Room Type</th>
                        <td><?= $room_details['room_type']; ?></td>
                    </tr>
                    <tr>
                        <th>Price for 1 Month</th>
                        <td>₹<?= number_format($amount_for_one_month, 2); ?></td>
                    </tr>
                    <tr>
                        <th>Start Date</th>
                        <td><?= $start_date; ?></td>
                    </tr>
                    <tr>
                        <th>End Date</th>
                        <td><?= $end_date; ?></td>
                    </tr>
                </table>
            </div>
        <?php } ?>

        <h3><a href="dashboard.php">&#8592; Back to dashboard</a></h3>
    </div>
</body>
</html>
