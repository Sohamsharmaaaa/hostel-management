<?php
session_start();
include('../config/db_connect.php');

$user_id = $_SESSION['user_id']; // Assuming the user_id is stored in session

// Fetch current user details
$sql = "SELECT * FROM users WHERE user_id = $user_id";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $bio = $_POST['bio'];

    // Handle profile picture upload
    if ($_FILES['profile_picture']['name']) {
        $profile_picture = $_FILES['profile_picture']['name'];
        $target_dir = "../assets/images/";
        $target_file = $target_dir . basename($profile_picture);
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file);
    } else {
        $profile_picture = $user['profile_picture']; // Retain old profile picture if no new one is uploaded
    }

    // Update the user details
    $sql = "UPDATE users SET name = '$name', email = '$email', bio = '$bio', profile_picture = '$profile_picture' WHERE user_id = $user_id";
    mysqli_query($conn, $sql);

    header('Location: profile.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/profile.css">
    <title>Edit Profile</title>
</head>
<body>
    <div class="profile-container">
        <h1>Edit Profile</h1>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" value="<?php echo $user['name']; ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" value="<?php echo $user['email']; ?>" required>
            </div>

            <div class="form-group">
                <label for="bio">Bio:</label>
                <textarea name="bio" id="bio"><?php echo $user['bio']; ?></textarea>
            </div>

            <div class="form-group">
                <label for="profile_picture">Profile Picture:</label>
                <input type="file" name="profile_picture" id="profile_picture">
            </div>

            <button type="submit" class="btn">Save Changes</button>
        </form>
    </div>
</body>
</html>
