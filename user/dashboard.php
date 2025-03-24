<?php
session_start();
include '../config/db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$query = "SELECT name, email FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Fetch statistics
$total_bookings = $conn->query("SELECT COUNT(*) as count FROM bookings WHERE user_id = $user_id")->fetch_assoc()['count'];
$total_payments = $conn->query("SELECT SUM(amount) as total FROM payments WHERE booking_id IN (SELECT booking_id FROM bookings WHERE user_id = $user_id)")->fetch_assoc()['total'];
$total_complaints = $conn->query("SELECT COUNT(*) as count FROM complaints WHERE user_id = $user_id")->fetch_assoc()['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="../assets/css/userdash.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Hostel System</h2>
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="book_room.php"><i class="fas fa-bed"></i> Book Room</a></li>
            <li><a href="my_bookings.php"><i class="fas fa-list"></i> My Bookings</a></li>
            <!-- <li><a href="payment.php"><i class="fas fa-wallet"></i> Payments</a></li> -->
            <li><a href="complaints.php"><i class="fas fa-exclamation-circle"></i> Complaints</a></li>
            <li><a href="profile.php"><i class="fas fa-user"></i> Profile</a></li>
            <li><a href="../auth/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h1>Welcome, <?php echo htmlspecialchars($user['name']); ?> 👋</h1>
        <p>Your Hostel Management Dashboard</p>

        <!-- Stats Section -->
        <div class="stats">
            <div class="stat-card">
                <i class="fas fa-book"></i>
                <h3><?php echo $total_bookings; ?></h3>
                <p>Total Bookings</p>
            </div>

            <!-- <div class="stat-card">
                <i class="fas fa-dollar-sign"></i>
                <h3>₹<?php echo number_format($total_payments, 2); ?></h3>
                <p>Total Payments</p>
            </div> -->

            <div class="stat-card">
                <i class="fas fa-exclamation-triangle"></i>
                <h3><?php echo $total_complaints; ?></h3>
                <p>Complaints Raised</p>
            </div>
        </div>
    </div>

</body>
</html>
