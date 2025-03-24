<?php
include '../config/db_connect.php';

$query = "SELECT payments.payment_id, users.name AS user_name, bookings.booking_id, 
                 payments.amount, payments.payment_date, payments.payment_status 
          FROM payments
          JOIN bookings ON payments.booking_id = bookings.booking_id
          JOIN users ON bookings.user_id = users.user_id";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Payments</title>
    <link rel="stylesheet" href="../assets/css/view_payments.css">
</head>
<body>

<div class="sidebar">
    <h2>Admin Panel</h2>
    <ul>
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="manage_users.php">Manage Users</a></li>
        <li><a href="manage_rooms.php">Manage Rooms</a></li>
        <li><a href="manage_bookings.php">Manage Bookings</a></li>
        <li><a href="view_payments.php" class="active">View Payments</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>

<div class="main-content">
    <h2>View Payments</h2>
    <table>
        <tr>
            <th>Payment ID</th>
            <th>User</th>
            <th>Booking ID</th>
            <th>Amount</th>
            <th>Payment Date</th>
            <th>Payment Status</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $row['payment_id']; ?></td>
            <td><?php echo $row['user_name']; ?></td>
            <td><?php echo $row['booking_id']; ?></td>
            <td>₹<?php echo number_format($row['amount'], 2); ?></td>
            <td><?php echo $row['payment_date']; ?></td>
            <td><?php echo ucfirst($row['payment_status']); ?></td>
        </tr>
        <?php } ?>
    </table>
</div>

</body>
</html>
