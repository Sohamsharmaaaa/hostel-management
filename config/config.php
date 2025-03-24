<?php
session_start();
ini_set('session.gc_maxlifetime', 86400); // Set session lifetime to 24 hours
ini_set('session.cookie_lifetime', 86400);

$host = "localhost";
$user = "root";  // Change if using another DB user
$password = "";
$dbname = "hm_db";

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
