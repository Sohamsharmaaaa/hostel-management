<?php
include '../config/db_connect.php';
session_start();

// Fetch complaints from the database
$query = "SELECT complaints.*, users.name AS user_name FROM complaints 
          JOIN users ON complaints.user_id = users.user_id ORDER BY complaints.created_at DESC";
$result = mysqli_query($conn, $query);

// Update complaint status
if (isset($_GET['resolve'])) {
    $complaint_id = $_GET['resolve'];
    $update_query = "UPDATE complaints SET status = 'Resolved' WHERE complaint_id = $complaint_id";
    mysqli_query($conn, $update_query);
    header("Location: complaints.php");
    exit();
}

// Delete complaint
if (isset($_GET['delete'])) {
    $complaint_id = $_GET['delete'];
    $delete_query = "DELETE FROM complaints WHERE complaint_id = $complaint_id";
    mysqli_query($conn, $delete_query);
    header("Location: complaints.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Complaints</title>
    <link rel="stylesheet" href="../assets/css/complaints.css">
</head>
<body>

<div class="sidebar">
    <h2>Admin Panel</h2>
    <ul>
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="manage_users.php">Manage Users</a></li>
        <li><a href="manage_rooms.php">Manage Rooms</a></li>
        <li><a href="manage_bookings.php">Manage Bookings</a></li>
        <li><a href="view_payments.php">View Payments</a></li>
        <li><a href="complaints.php" class="active">Complaints</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>

<div class="main-content">
    <h2>Complaints</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>Type</th>
            <th>Description</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $row['complaint_id']; ?></td>
            <td><?php echo $row['user_name']; ?></td>
            <td><?php echo $row['complaint_type']; ?></td>
            <td><?php echo $row['description']; ?></td>
            <td><?php echo $row['status']; ?></td>
            <td>
                <?php if ($row['status'] == 'Pending') { ?>
                    <a href="complaints.php?resolve=<?php echo $row['complaint_id']; ?>" class="btn-resolve">Resolve</a>
                <?php } ?>
                <a href="complaints.php?delete=<?php echo $row['complaint_id']; ?>" class="btn-delete" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>

</body>
</html>
