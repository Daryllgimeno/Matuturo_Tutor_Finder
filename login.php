<?php
session_start();
include 'config.php'; // Assuming 'config.php' connects to your database

// Handle form submissions (login and register)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Register Form
    if (isset($_POST['register'])) {
        $username = htmlspecialchars(trim($_POST['username']));
        $email = htmlspecialchars(trim($_POST['email']));
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $role = htmlspecialchars(trim($_POST['role']));

        try {
            // Use prepared statement to prevent SQL injection
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->execute([$username, $email, $password, $role]);

            header("Location: login.php?success=1");
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Login Form
    if (isset($_POST['login'])) {
        $username = htmlspecialchars(trim($_POST['username']));
        $password = $_POST['password'];

        try {
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Redirect based on role
                switch ($user['role']) {
                    case 'student':
                        header("Location: student_dashboard.php");
                        break;
                    case 'tutor':
                        header("Location: tutor_dashboard.php");
                        break;
                    case 'admin':
                        header("Location: admin_dashboard.php");
                        break;
                    default:
                        header("Location: login.php");
                }
                exit();
            } else {
                echo "Invalid username or password";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}

$conn = null;
?>

<!-- HTML remains unchanged, except for minor adjustments -->


<html>
    <head>
        <title>Login and Register</title>
        <link rel="stylesheet" href="login.css">
    </head>
    <body>
        <div class="container">
            <div class="menu">
                <ul>
                    <li class="logo"><img src="BSU LOGO.png" alt=""></li>
                    <li><a href="home.html" class="active1">Home</a></li>
                    <li><a href="" class="active2">Tutors</a></li>
                    <li><a href="aboutus.html" class="active3">About Us</a></li>
                </ul>
            </div>
            <div class="form-box">
                <div class="button-box">
                    <div id="btn"></div>
                    <button type="button" class="toggle-btn" onclick="login()">Log In</button>
                    <button type="button" class="toggle-btn" onclick="register()">Register</button>
                </div>

                <!-- Login Form -->
                <form id="login" class="input-group" method="POST" action="login.php">
                    <input type="text" class="input-field" name="username" placeholder="Username" required>
                    <input type="password" class="input-field" name="password" placeholder="Password" required>
                    <input type="checkbox" class="check-box" name="remember"><span>Remember Password</span>
                    <button type="submit" class="submit-btn" name="login">Log In</button>
                </form>

                <!-- Register Form -->
                <form id="register" class="input-group" method="POST" action="login.php">
                    <input type="text" class="input-field" name="username" placeholder="Username" required>
                    <input type="email" class="input-field" name="email" placeholder="Email ID" required>
                    <input type="password" class="input-field" name="password" placeholder="Password" required>
                    
                    <!-- Role selection -->
                    <div class="role-selection">
                        <label for="role">I am a:</label>
                        <select id="role" name="role" class="input-field" required>
                            <option value="student">Student</option>
                            <option value="tutor">Tutor</option>
                        </select>
                    </div>
                    
                    <input type="checkbox" class="check-box" name="terms"><span>I agree to the terms & conditions</span>
                    <button type="submit" class="submit-btn" name="register">Register</button>
                </form>
            </div>
        </div>

        <section class="footer">
            <ul>
                <li><a href="privacy.html" class="footer1">Privacy and Policy</a></li>
                <li><a href="terms.html" class="footer2">Terms and Conditions</a></li>
                <li><a href="contact.html" class="footer3">Contact</a></li>
            </ul>
            <p>© 2024 F4. All rights reserved.</p>
        </section>

        <script>
            var x = document.getElementById("login");
            var y = document.getElementById("register");
            var z = document.getElementById("btn");

            function register() {
                x.style.left = "-400px";
                y.style.left = "50px";
                z.style.left = "110px";
            }

            function login() {
                x.style.left = "50px";
                y.style.left = "450px";
                z.style.left = "0";
            }
        </script>
    </body>
</html>
