<?php
session_start();
include '../config/db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch all users
$result = $conn->query("SELECT user_id, name, email, status FROM users");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="../assets/css/manage_users.css">
</head>
<body>
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="manage_users.php" class="active">Manage Users</a></li>
            <li><a href="manage_rooms.php">Manage Rooms</a></li>
            <li><a href="manage_bookings.php">Manage Bookings</a></li>
            <li><a href="view_payments.php">View Payments</a></li>
            <li><a href="complaints.php">Complaints</a></li>
            <li><a href="../auth/logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h1>Manage Users</h1>
        <table>
            <tr>
                <th>User ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            <?php while ($user = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $user['user_id']; ?></td>
                <td><?php echo $user['name']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td><?php echo ucfirst($user['status']); ?></td>
                <td>
                <td>
                    <a href="edit_user.php?id=<?php echo $user['user_id']; ?>">Edit</a>
                    <a href="delete_user.php?id=<?php echo $user['user_id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                </td>

                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html> 
