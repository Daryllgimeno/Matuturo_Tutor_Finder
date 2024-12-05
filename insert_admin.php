<?php
// Include your database connection
include 'config.php'; // Ensure this file contains $conn for the DB connection

// Define the admin user data
$username = "admin";                // Admin username
$email = "admin@example.com";        // Admin email
$password = "adminpassword";         // Admin password (plaintext, it will be hashed)
$role = "admin";                    // User role (admin in this case)

// Hash the password using password_hash()
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Use prepared statements to insert the user into the database
try {
    // Prepare the SQL query to insert the admin user
    $sql = "INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)";
    $stmt = $conn->prepare($sql); // Prepare the SQL statement

    // Bind the parameters to the query
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->bindParam(':role', $role);

    // Execute the query
    $stmt->execute();

    // Success message
    echo "Admin user created successfully!";
} catch (PDOException $e) {
    // Error handling
    echo "Error: " . $e->getMessage();
}

// Close the connection
$conn = null;
?>
