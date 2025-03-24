<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include '../config/db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: manage_rooms.php");
    exit();
}

$room_id = $_GET['id'];
$stmt = $conn->prepare("SELECT room_number, room_type, price, availability FROM rooms WHERE room_id = ?");
$stmt->bind_param("i", $room_id);
$stmt->execute();
$result = $stmt->get_result();
$room = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $room_number = trim($_POST['room_number']);
    $room_type = $_POST['room_type'];
    $price = $_POST['price'];
    $availability = $_POST['availability'];

    $update_stmt = $conn->prepare("UPDATE rooms SET room_number = ?, room_type = ?, price = ?, availability = ? WHERE room_id = ?");
    $update_stmt->bind_param("ssdsi", $room_number, $room_type, $price, $availability, $room_id);

    if ($update_stmt->execute()) {
        header("Location: manage_rooms.php");
        exit();
    } else {
        echo "Error updating room!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Room</title>
    <link rel="stylesheet" href="../assets/css/edit_room.css">
</head>
<body>
    <div class="container">
        <h1>Edit Room</h1>
        <form method="post">
            <label>Room Number:</label>
            <input type="text" name="room_number" value="<?php echo $room['room_number']; ?>" required>

            <label>Room Type:</label>
            <select name="room_type">
                <option value="single" <?php echo ($room['room_type'] == 'single') ? 'selected' : ''; ?>>Single</option>
                <option value="double" <?php echo ($room['room_type'] == 'double') ? 'selected' : ''; ?>>Double</option>
                <option value="suite" <?php echo ($room['room_type'] == 'suite') ? 'selected' : ''; ?>>Suite</option>
            </select>

            <label>Price:</label>
            <input type="number" name="price" value="<?php echo $room['price']; ?>" required>

            <label>Availability:</label>
            <select name="availability">
                <option value="available" <?php echo ($room['availability'] == 'available') ? 'selected' : ''; ?>>Available</option>
                <option value="occupied" <?php echo ($room['availability'] == 'occupied') ? 'selected' : ''; ?>>Occupied</option>
            </select>

            <button type="submit">Update Room</button>
            <a href="manage_rooms.php">Cancel</a>
        </form>
    </div>
</body>
</html>
