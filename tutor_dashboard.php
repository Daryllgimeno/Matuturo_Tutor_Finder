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

// Fetch student posts along with their username
try {
    $stmt = $conn->prepare("SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.id WHERE post_type = 'looking_for_tutor' AND posts.role = 'student'");
    $stmt->execute();
    $student_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Error: ' . $e->getMessage());
}

// Fetch user's own posts
try {
    $stmt = $conn->prepare("SELECT * FROM posts WHERE user_id = ? AND post_type = 'looking_for_student'");
    $stmt->execute([$_SESSION['user_id']]);
    $user_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Error: ' . $e->getMessage());
}

// Handle deletion of user's own post
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_post_id'])) {
    $delete_post_id = $_POST['delete_post_id'];

    try {
        $stmt = $conn->prepare("DELETE FROM posts WHERE id = ? AND user_id = ?");
        $stmt->execute([$delete_post_id, $_SESSION['user_id']]);
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    } catch (PDOException $e) {
        die('Error: ' . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutor Dashboard</title>

    <!-- Add Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Add Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- BSU Background Style -->
    <style>
        body {
            background: linear-gradient( rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5) ), url('BSU3.png');
            color: #fff;
        }
        .card {
            background-color: #ffffff;
            color: #333;
        }
    </style>
</head>
<body class="font-sans">
    <!-- Navbar with Logout Button on the Right -->
    <nav class="navbar navbar-expand-lg navbar-light bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand text-white" href="#">Tutor Dashboard</a>
            <div class="d-flex ms-auto">
                <button class="btn btn-outline-light" onclick="window.location.href='logout.php'">Logout</button>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Welcome, <?php echo $_SESSION['username']; ?></h1>

        <!-- Post Form -->
        <div class="card p-4 mt-3">
            <h3 class="mb-3">Post Your Tutor Availability</h3>
            <form method="POST">
                <div class="mb-3">
                    <textarea name="message" class="form-control" placeholder="Describe the subject you can teach" required></textarea>
                </div>
                <div class="mb-3">
                    <input type="text" name="subject" class="form-control" placeholder="Subject" required>
                </div>
                <div class="mb-3">
                    <select name="price" class="form-select" required>
                        <option value="free">Free</option>
                        <option value="paid">Paid</option>
                    </select>
                </div>
                <div class="mb-3">
                    <input type="number" name="price_amount" class="form-control" placeholder="Price Amount (if paid)" step="0.01">
                </div>
                <button type="submit" class="btn btn-primary">Post</button>
            </form>
        </div>

        <!-- Display Student Posts -->
        <h2 class="mt-4">Students Looking for Tutors</h2>
        <div class="list-group">
            <?php foreach ($student_posts as $post): ?>
                <div class="list-group-item">
                    <p><strong>Posted by:</strong> <?php echo htmlspecialchars($post['username']); ?></p>
                    <p><strong>Message:</strong> <?php echo htmlspecialchars($post['message']); ?></p>
                    <p><strong>Subject:</strong> <?php echo htmlspecialchars($post['subject']); ?></p>
                    <p><strong>Price:</strong> <?php echo $post['price'] === 'free' ? 'Free' : '$' . number_format($post['price_amount'], 2); ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Display User's Own Posts with Delete Option -->
        <h2 class="mt-4">Your Posts</h2>
        <div class="list-group">
            <?php foreach ($user_posts as $post): ?>
                <form method="POST" class="list-group-item">
                    <p><strong>Message:</strong> <?php echo htmlspecialchars($post['message']); ?></p>
                    <p><strong>Subject:</strong> <?php echo htmlspecialchars($post['subject']); ?></p>
                    <p><strong>Price:</strong> <?php echo $post['price'] === 'free' ? 'Free' : '$' . number_format($post['price_amount'], 2); ?></p>
                    <button type="submit" name="delete_post_id" value="<?php echo $post['id']; ?>" class="btn btn-danger mt-2">Delete</button>
                </form>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
