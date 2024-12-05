<?php
session_start();

// Prevent caching by sending proper headers
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

// Check if the user is logged in and has the correct role
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'tutor') {
    header("Location: login.php");
    exit();
}

include('config.php'); // Include database connection

// Handle form submission for tutor posts
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message'], $_POST['subject'], $_POST['price'])) {
    $message = $_POST['message'];
    $subject = $_POST['subject'];
    $price = $_POST['price'];
    $price_amount = $price == 'paid' ? $_POST['price_amount'] : null;

    try {
        $stmt = $conn->prepare("INSERT INTO posts (user_id, role, message, post_type, subject, price, price_amount) VALUES (?, 'tutor', ?, 'looking_for_student', ?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $message, $subject, $price, $price_amount]);
    } catch (PDOException $e) {
        die('Error: ' . $e->getMessage());
    }
}

// Fetch student posts
try {
    $stmt = $conn->prepare("SELECT * FROM posts WHERE post_type = 'looking_for_tutor' AND role = 'student'");
    $stmt->execute();
    $student_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Error: ' . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutor Dashboard</title>
</head>
<body>
    <h1>Welcome, <?php echo $_SESSION['username']; ?></h1>

    <!-- Post Form -->
    <form method="POST">
        <textarea name="message" placeholder="Describe the subject you can teach" required></textarea><br>
        <input type="text" name="subject" placeholder="Subject" required><br>
        <select name="price" required>
            <option value="free">Free</option>
            <option value="paid">Paid</option>
        </select><br>
        <input type="number" name="price_amount" placeholder="Price Amount (if paid)" step="0.01"><br>
        <button type="submit">Post</button>
    </form>

    <!-- Display Student Posts -->
    <h2>Students Looking for Tutors</h2>
    <ul>
        <?php foreach ($student_posts as $post): ?>
            <li>
                <?php echo htmlspecialchars($post['message']); ?> <br>
                Subject: <?php echo htmlspecialchars($post['subject']); ?> <br>
                Price: <?php echo $post['price'] === 'free' ? 'Free' : '$' . number_format($post['price_amount'], 2); ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <!-- Logout Button -->
    <button onclick="window.location.href='logout.php'">Logout</button>
</body>
</html>
