<?php
session_start();

// Include your DB connection file
include('../config/db_connect.php');

// Fetch the logged-in user details
$user_id = $_SESSION['user_id']; // Assuming the user_id is stored in session

$sql = "SELECT * FROM users WHERE user_id = $user_id";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/profile.css">
    <title>User Profile</title>
</head>
<body>
    <div class="profile-container">
        <h1>Your Profile</h1>
        <div class="profile-info">
            <div class="profile-picture">
                <?php if ($user['profile_picture']): ?>
                    <img src="../assets/images/<?php echo $user['profile_picture']; ?>" alt="Profile Picture">
                <?php else: ?>
                    <img src="../assets/images/default-profile.png" alt="Default Profile Picture">
                <?php endif; ?>
            </div>
            <div class="profile-details">
                <p><strong>Name:</strong> <?php echo $user['name']; ?></p>
                <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
                <p><strong>Status:</strong> <?php echo ucfirst($user['status']); ?></p>
                <p><strong>Role:</strong> <?php echo ucfirst($user['role']); ?></p>
                <p><strong>Bio:</strong> <?php echo $user['bio'] ?: 'No bio available'; ?></p>
                <a href="edit_profile.php" class="btn">Edit Profile</a>

                <a href="dashboard.php" class="btn">Back to Dashboard</a>
            </div>
        </div>
    </div>
</body>
</html>
