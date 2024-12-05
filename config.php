<?php
$servername = "localhost";
$username = "root"; // Your MySQL username
$password = "";     // Your MySQL password
$dbname = "user_management"; // Your database name
$port = 3307;       // MySQL port (if using XAMPP)

try {
    $conn = new PDO("mysql:host=$servername;port=$port;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception for better error handling
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Log error and show generic message to avoid exposing sensitive details
    error_log("Connection failed: " . $e->getMessage());
    die("Connection failed. Please try again later.");
}
?>
