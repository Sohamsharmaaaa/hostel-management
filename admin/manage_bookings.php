<?php
session_start();
include '../config/db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch bookings data
$sql = "SELECT b.booking_id, u.name AS user_name, r.room_number, 
                 b.start_date, b.end_date, b.payment_status
          FROM bookings b
          JOIN users u ON b.user_id = u.user_id
          JOIN rooms r ON b.room_id = r.room_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings</title>
    <link rel="stylesheet" href="../assets/css/manage_bookings.css">
</head>
<body>

<div class="container">
    <aside class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="manage_users.php">Manage Users</a></li>
            <li><a href="manage_rooms.php">Manage Rooms</a></li>
            <li><a href="manage_bookings.php" class="active">Manage Bookings</a></li>
            <li><a href="view_payments.php">View Payments</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </aside>

    <main class="content">
        <h1>Manage Bookings</h1>
        <table>
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>User</th>
                    <th>Room</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Payment Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr class="<?= $row['payment_status'] == 'pending' ? 'pending' : 'paid' ?>">
                        <td><?= $row['booking_id']; ?></td>
                        <td><?= $row['user_name']; ?></td>
                        <td><?= $row['room_number']; ?></td>
                        <td><?= $row['start_date']; ?></td>
                        <td><?= $row['end_date']; ?></td>
                        <td><?= ucfirst($row['payment_status']); ?></td>
                        <td>
                            <a href="approve_booking.php?id=<?= $row['booking_id']; ?>" class="approve-btn">Approve</a>
                            <a href="cancel_booking.php?id=<?= $row['booking_id']; ?>" class="cancel-btn">Cancel</a>
                            <a href="delete_booking.php?id=<?= $row['booking_id']; ?>" class="delete-btn">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>
</div>

</body>
</html>
