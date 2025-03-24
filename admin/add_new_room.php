<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include '../config/db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $room_number = trim($_POST['room_number']);
    $room_type = $_POST['room_type'];
    $price = $_POST['price'];
    $availability = $_POST['availability'];

    $stmt = $conn->prepare("INSERT INTO rooms (room_number, room_type, price, availability) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssds", $room_number, $room_type, $price, $availability);

    if ($stmt->execute()) {
        header("Location: manage_rooms.php");
        exit();
    } else {
        echo "Error adding room!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Room</title>
    <link rel="stylesheet" href="../assets/css/add_room.css">
</head>
<body>
    <div class="container">
        <h1>Add New Room</h1>
        <form method="post">
            <label>Room Number:</label>
            <input type="text" name="room_number" required>

            <label>Room Type:</label>
            <select name="room_type">
                <option value="single">Single</option>
                <option value="double">Double</option>
                <option value="suite">Suite</option>
            </select>

            <label>Price:</label>
            <input type="number" name="price" required>

            <label>Availability:</label>
            <select name="availability">
                <option value="available">Available</option>
                <option value="occupied">Occupied</option>
            </select>

            <button type="submit">Add Room</button>
            <a href="manage_rooms.php">Cancel</a>
        </form>
    </div>
</body>
</html>
