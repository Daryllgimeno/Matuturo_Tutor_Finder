<?php
// The password you want to hash
$password = "admin";

// Hash the password using BCRYPT
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// Output the hashed password
echo "Hashed Password: " . $hashed_password;
?>
