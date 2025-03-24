<?php
// Database Configuration
$host = "localhost";  // Change this if your database is hosted elsewhere
$user = "root";       // Default XAMPP user
$pass = "";           // Default is empty in XAMPP
$dbname = "hm_db"; // Database name

// Create a connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
