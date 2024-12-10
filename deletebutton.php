<?php
session_start();

// Prevent caching by sending proper headers
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

// Check if the user is logged in and has admin role
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Include database connection
include('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    try {
        $user_id = $_POST['user_id'];

        // Secure delete query
        $stmt = $conn->prepare("DELETE FROM users WHERE id = :id");
        $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);

        // Execute deletion
        if ($stmt->execute()) {
            header("Location: admin_dashboard.php?success=User deleted successfully");
            exit();
        } else {
            header("Location: admin_dashboard.php?error=Failed to delete user");
            exit();
        }
    } catch (PDOException $e) {
        header("Location: admin_dashboard.php?error=" . $e->getMessage());
        exit();
    }
} else {
    header("Location: dashboard.php?error=Invalid request");
    exit();
}
?>
