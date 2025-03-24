<?php
session_start();
include '../config/db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: manage_users.php");
    exit();
}

$user_id = $_GET['id'];
$stmt = $conn->prepare("SELECT name, email, status FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $status = $_POST['status'];

    $update_stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, status = ? WHERE user_id = ?");
    $update_stmt->bind_param("sssi", $name, $email, $status, $user_id);

    if ($update_stmt->execute()) {
        header("Location: manage_users.php");
        exit();
    } else {
        echo "Error updating user!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="../assets/css/edit_user.css">
</head>
<body>
    <div class="container">
        <h1>Edit User</h1>
        <form method="post">
            <label>Name:</label>
            <input type="text" name="name" value="<?php echo $user['name']; ?>" required>

            <label>Email:</label>
            <input type="email" name="email" value="<?php echo $user['email']; ?>" required>

            <label>Status:</label>
            <select name="status">
                <option value="active" <?php echo ($user['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                <option value="inactive" <?php echo ($user['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
            </select>

            <button type="submit">Update User</button>
            <a href="manage_users.php">Cancel</a>
        </form>
    </div>
</body>
</html>
