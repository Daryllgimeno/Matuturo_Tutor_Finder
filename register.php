<?php
include 'config.php';  // Make sure $conn is a PDO instance

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize user inputs
    $username = htmlspecialchars($_POST['username']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $role = htmlspecialchars($_POST['role']);

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Prepare the SQL statement
        $sql = "INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)";
        
        // Prepare statement
        $stmt = $conn->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':role', $role);

        // Execute the statement
        $stmt->execute();

        echo "<div class='alert alert-success'>New record created successfully</div>";
    } catch (PDOException $e) {
        // Handle error
        echo "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
    }
}
?>
