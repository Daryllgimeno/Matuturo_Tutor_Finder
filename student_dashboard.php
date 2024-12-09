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

include('config.php'); // Include database connection

// Handle form submission for student posts (student looking for tutor)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message'], $_POST['subject'], $_POST['price'])) {
    $message = $_POST['message'];
    $subject = $_POST['subject'];
    $price = $_POST['price'];
    $price_amount = $price == 'paid' ? $_POST['price_amount'] : null;

    try {
        // Insert a post from the student looking for a tutor
        $stmt = $conn->prepare("INSERT INTO posts (user_id, role, message, post_type, subject, price, price_amount) VALUES (?, 'student', ?, 'looking_for_tutor', ?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $message, $subject, $price, $price_amount]);
    } catch (PDOException $e) {
        die('Error: ' . $e->getMessage());
    }
}

// Handle search query
$search_subject = isset($_GET['subject']) ? $_GET['subject'] : '';
$search_name = isset($_GET['name']) ? $_GET['name'] : '';
$search_price = isset($_GET['price']) ? $_GET['price'] : '';

// Fetch tutor posts (tutors looking for students) with optional filters
try {
    $sql = "SELECT posts.*, users.username FROM posts 
            JOIN users ON posts.user_id = users.id 
            WHERE post_type = 'looking_for_student' AND posts.role = 'tutor'";

    // Add search filters to the query
    $conditions = [];
    $params = [];

    if ($search_subject) {
        $conditions[] = "subject LIKE ?";
        $params[] = "%$search_subject%";
    }
    if ($search_name) {
        $conditions[] = "users.username LIKE ?";
        $params[] = "%$search_name%";
    }
    if ($search_price && $search_price != 'all') {
        $conditions[] = "price = ?";
        $params[] = $search_price;
    }

    if (count($conditions) > 0) {
        $sql .= " AND " . implode(' AND ', $conditions);
    }

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $tutor_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Error: ' . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f7fa;
            color: #333;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #FF5722;
            color: white;
            padding: 15px 0;
            text-align: center;
            position: relative;
        }
        header h1 {
            margin: 0;
            font-size: 24px;
        }
        .logout-btn {
            position: absolute;
            top: 15px;
            right: 20px;
            background-color: #FF5722;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        .logout-btn:hover {
            background-color: #e64a19;
        }
        .container {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .container h2 {
            color: #FF5722;
            font-size: 22px;
            margin-bottom: 20px;
        }
        textarea, input[type="text"], select, input[type="number"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }
        textarea:focus, input[type="text"]:focus, select:focus, input[type="number"]:focus {
            border-color: #FF5722;
            outline: none;
        }
        button {
            background-color: #FF5722;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            width: 100%;
            margin: 10px 0;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #e64a19;
        }
        .post-list {
            margin-top: 20px;
        }
        .post-item {
            padding: 20px;
            margin-bottom: 10px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .post-item h4 {
            margin: 0 0 10px;
            color: #333;
            font-size: 18px;
        }
        .post-item p {
            margin: 5px 0;
            color: #555;
        }
        .post-item .price {
            font-weight: bold;
            color: #FF5722;
        }
        footer {
            text-align: center;
            background-color: #333;
            color: white;
            padding: 10px;
            margin-top: 20px;
        }
        .search-form input[type="text"], .search-form select {
            width: 30%;
            display: inline-block;
            margin-right: 10px;
        }
        .search-form button {
            width: auto;
            display: inline-block;
        }
    </style>
</head>
<body>

    <header>
        <h1>Welcome, <?php echo $_SESSION['username']; ?></h1>
        <button class="logout-btn" onclick="window.location.href='logout.php'">Logout</button>
    </header>

    <div class="container">
        <!-- Post Form for Student Looking for Tutor -->
        <h2>Post Looking for a Tutor</h2>
        <form method="POST">
            <textarea name="message" placeholder="Describe what you need help with" required></textarea><br>
            <input type="text" name="subject" placeholder="Subject" required><br>
            <select name="price" required>
                <option value="free">Free</option>
                <option value="paid">Paid</option>
            </select><br>
            <input type="number" name="price_amount" placeholder="Price Amount (if paid)" step="0.01"><br>
            <button type="submit">Post</button>
        </form>
    </div>

    <div class="container">
        <!-- Search Form -->
        <h2>Search Tutors</h2>
        <form method="GET" class="search-form">
            <input type="text" name="subject" placeholder="Search by Subject" value="<?php echo htmlspecialchars($search_subject); ?>"><br>
            <input type="text" name="name" placeholder="Search by Tutor Name" value="<?php echo htmlspecialchars($search_name); ?>"><br>
            <select name="price">
                <option value="all" <?php echo $search_price == 'all' ? 'selected' : ''; ?>>All Prices</option>
                <option value="free" <?php echo $search_price == 'free' ? 'selected' : ''; ?>>Free</option>
                <option value="paid" <?php echo $search_price == 'paid' ? 'selected' : ''; ?>>Paid</option>
            </select><br>
            <button type="submit">Search</button>
        </form>
    </div>

    <div class="container">
        <!-- Display Tutor Posts Looking for Students -->
        <h2>Tutors Looking for Students</h2>
        <div class="post-list">
            <?php foreach ($tutor_posts as $post): ?>
                <div class="post-item">
                    <h4><?php echo htmlspecialchars($post['username']); ?> - <?php echo htmlspecialchars($post['subject']); ?></h4>
                    <p><?php echo htmlspecialchars($post['message']); ?></p>
                    <p><strong>Price:</strong> <?php echo $post['price'] == 'free' ? 'Free' : '$' . htmlspecialchars($post['price_amount']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Online Tutoring System</p>
    </footer>

</body>
</html>
