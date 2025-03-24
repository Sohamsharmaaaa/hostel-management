<?php
// Include database connection
include('../config/db_connect.php');

// Check if the user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch student's complaints from the database
$query = "SELECT c.complaint_id, c.complaint_type, c.description, c.status, c.created_at 
          FROM complaints c
          WHERE c.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Complaints</title>
    <link rel="stylesheet" href="../assets/css/my_complaint.css">
</head>
<body>
    <div class="container">
        <h1>My Complaints</h1>

        <?php if (mysqli_num_rows($result) > 0) { ?>
            <table>
                <thead>
                    <tr>
                        <th>Complaint ID</th>
                        <th>Complaint Type</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?php echo $row['complaint_id']; ?></td>
                            <td><?php echo $row['complaint_type']; ?></td>
                            <td><?php echo $row['description']; ?></td>
                            <td><?php echo ucfirst($row['status']); ?></td>
                            <td><?php echo $row['created_at']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>No complaints found.</p>
        <?php } ?>
    </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
