<?php
$password = "Admin115#"; // Change this to your desired password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
echo "Hashed Password: " . $hashedPassword;
?>

