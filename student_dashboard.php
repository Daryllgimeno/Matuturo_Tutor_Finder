<?php
session_start();

// Prevent caching by sending proper headers
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

// Check if the user is logged in and has the correct role
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'student') {
    header("Location: login.php");
    exit();
}

echo "Welcome, Student: " . $_SESSION['username'];
?>

<!-- Rest of your student dashboard content -->
<a href="logout.php">Logout</a>
