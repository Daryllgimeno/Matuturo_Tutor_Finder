<?php
// The password you want to hash
$password = "admin";

// Hash the password using BCRYPT
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

//how to insert hashed credentials
// INSERT INTO users (username, password, role, email) 
//VALUES ('admin', 'hashed_password_here', 'admin', 'admin@example.com');


// Output the hashed password
echo "Hashed Password: " . $hashed_password;
?>


