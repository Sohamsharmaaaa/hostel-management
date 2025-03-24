<?php
include '../config/db_connect.php';

// Fetch counts from database
$total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM users"))['count'];
$total_rooms = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM rooms"))['count'];
$total_bookings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM bookings"))['count'];
$total_payments = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(amount) AS total FROM payments"))['total'];
$total_complaints = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM complaints"))['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<div class="dashboard-container">
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="manage_users.php">Manage Users</a></li>
            <li><a href="manage_rooms.php">Manage Rooms</a></li>
            <li><a href="manage_bookings.php">Manage Bookings</a></li>
            <li><a href="view_payments.php">View Payments</a></li>
            <li><a href="complaints.php">Complaints</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h1>Welcome, Admin</h1>
        <p>Manage users, rooms, bookings, and more.</p>

        <div class="stats">
            <div class="stat-card">
                <i class="fa fa-users"></i>
                <h3><?php echo $total_users; ?></h3>
                <p>Total Users</p>
            </div>
            <div class="stat-card">
                <i class="fa fa-bed"></i>
                <h3><?php echo $total_rooms; ?></h3>
                <p>Total Rooms</p>
            </div>
            <div class="stat-card">
                <i class="fa fa-calendar-check"></i>
                <h3><?php echo $total_bookings; ?></h3>
                <p>Total Bookings</p>
            </div>
            <div class="stat-card">
                <i class="fa fa-money-bill-wave"></i>
                <h3>₹<?php echo number_format($total_payments, 2); ?></h3>
                <p>Total Earnings</p>
            </div>
            <div class="stat-card">
                <i class="fa fa-exclamation-triangle"></i>
                <h3><?php echo $total_complaints; ?></h3>
                <p>Total Complaints</p>
            </div>
        </div>
    </div>
</div>

</body>
</html>
