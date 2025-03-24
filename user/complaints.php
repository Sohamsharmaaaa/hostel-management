<?php
// Include database connection
include('../config/db_connect.php');

// Check if the user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $complaint_type = mysqli_real_escape_string($conn, $_POST['complaint_type']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    // Insert complaint into the database
    $query = "INSERT INTO complaints (user_id, complaint_type, description, status) VALUES (?, ?, ?, 'open')";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iss", $user_id, $complaint_type, $description);

    if ($stmt->execute()) {
        $message = "Complaint submitted successfully!";
    } else {
        $message = "Error submitting complaint. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Complaint</title>
    <link rel="stylesheet" href="../assets/css/usercomp.css">
</head>
<body>
<h3 style="text-align:center;" ><a href="dashboard.php">&#8592; Back to dashboard</a></h3>
    <div class="container">
        <h1>Submit Complaint</h1>
        
        <?php if (isset($message)) { echo "<p>$message</p>"; } ?>
        

        <form action="complaints.php" method="POST">
            <div>
                <label for="complaint_type">Complaint Type:</label>
                <select name="complaint_type" id="complaint_type" required>
                    <option value="Room Issues">Room Issues</option>
                    <option value="Food Quality">Food Quality</option>
                    <option value="Cleanliness">Cleanliness</option>
                    <option value="Noise">Noise</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div>
                <label for="description">Description:</label>
                <textarea name="description" id="description" rows="5" required></textarea>
            </div>

            <button type="submit">Submit Complaint</button>
            

        </form>

        <div class="my-complaints-link">
    <p style="text-align:center;"><a href="my_complaints.php">Click here to view my complaints</a></p>
</div>
    </div>

   

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
