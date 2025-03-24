<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include '../config/db_connect.php';

// Check if admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch all rooms from database
$query = "SELECT room_id, room_number, room_type, price, availability FROM rooms";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Rooms</title>
    <link rel="stylesheet" href="../assets/css/manage_rooms.css">
</head>
<body>

<div class="container">
    <!-- Sidebar Navigation -->
    <aside class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="manage_users.php">Manage Users</a></li>
            <li><a href="manage_rooms.php" class="active">Manage Rooms</a></li>
            <li><a href="manage_bookings.php">Manage Bookings</a></li>
            <li><a href="view_payments.php">View Payments</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="content">
        <h1>Manage Rooms</h1>
        <a href="add_new_room.php" class="add-room-btn">+ Add New Room</a>
        <table border="1">
            <thead>
                <tr>
                    <th>Room Number</th>
                    <th>Type</th>
                    <th>Price</th>
                    <th>Availability</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['room_number']); ?></td>
                    <td><?php echo htmlspecialchars($row['room_type']); ?></td>
                    <td><?php echo htmlspecialchars($row['price']); ?></td>
                    <td><?php echo htmlspecialchars($row['availability']); ?></td>
                    <td>
                        <a href="edit_room.php?id=<?php echo $row['room_id']; ?>">Edit</a>
                        <a href="delete_room.php?id=<?php echo $row['room_id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>
</div>

</body>
</html>
