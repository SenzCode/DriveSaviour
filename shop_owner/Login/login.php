<?php
session_start(); 
include_once('../../connection.php');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = filter_input(INPUT_POST, 'password', FILTER_DEFAULT); // No sanitization for passwords

        $query = "SELECT * FROM shop_owner WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Store user information in session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['logged_in'] = true;

                // Redirect to a protected page
                header('Location: ../home/home.php');
                exit();
            } else {
                echo "Invalid email or password.";
            }
        } else {
            echo "Invalid email or password.";
        }

        $stmt->close();
    } elseif (isset($_POST['register'])) {
        // Handle registration
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = filter_input(INPUT_POST, 'password', FILTER_DEFAULT); // No sanitization for passwords

        $checkEmailQuery = "SELECT * FROM shop_owner WHERE email = ?";
        $stmt = $conn->prepare($checkEmailQuery);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "Email already in use. Please choose a different email.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $insertQuery = "INSERT INTO shop_owner (name, phone, email, password) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param('ssss', $name, $phone, $email, $hashedPassword);
            if ($stmt->execute()) {
                echo "New Account added successfully.";
            } else {
                echo "Error: " . $stmt->error;
            }
        }
        $stmt->close();
    }
}

// Close the database connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<title>Login</title>
<link rel="stylesheet" href="style.css">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
<div class="container">

    <div class="form-box login">
            <form action="login.php" method="POST">
                <h1>Login</h1>
                <div class="input-box">
                    <input type="email"  name="email" placeholder="Email" required>
                    <i class='bx bx-envelope' ></i>
                </div>
                <div class="input-box">
                    <input type="password" name="password" placeholder="Password" required>
                    <i class='bx bxs-lock-alt'></i>
                </div>
                <div class="forgot-link">
                    <a href="" Forgot Password?></a>
                </div>
                <button type="submit" class="btn" name="login">Login</button>
                <p>or login with social platforms</p>
                <div class="social-icon">
                    <a href=""><i class='bx bxl-google' ></i></a>
                    <a href=""><i class='bx bxl-facebook' ></i></a>
                    <a href=""><i class='bx bxl-github' ></i></a>
                    <a href=""><i class='bx bxl-linkedin' ></i></a>
                </div>
            </form>
        </div>

        <div class="form-box register">
            <form action="login.php" method="POST">
                <h1>Register</h1>
                <div class="input-box">
                    <input type="email" name="email" placeholder="Email" required>
                    <i class='bx bx-envelope' ></i>
                </div>
                <div class="input-box">
                    <input type="text" name="name" placeholder="Name" required>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box">
                    <input type="password" name="password" placeholder="Password" required>
                    <i class='bx bxs-lock-alt'></i>
                </div>
                <div class="input-box">
                    <input type="number" name="phone" placeholder="Phone" required>
                    <i class='bx bxs-phone' ></i>
                </div>
                <div class="forgot-link">
                    <a href="" Forgot Password?></a>
                </div>
                <button type="submit" name="register" class="btn">Register</button>
                <p>or register with social platforms</p>
                <div class="social-icon">
                    <a href=""><i class='bx bxl-google' ></i></a>
                    <a href=""><i class='bx bxl-facebook' ></i></a>
                    <a href=""><i class='bx bxl-github' ></i></a>
                    <a href=""><i class='bx bxl-linkedin' ></i></a>
                </div>
            </form>
        </div>

        <div class="toggle-box">
            <div class="toggle-panel toggle-left">
                 <h1>Hello, Welcome</h1><br>
                 <p>Register with your personal details to use all of site features</p>
                 <button class="btn register-btn" id="login">Sign Up</button>
             </div>
             <div class="toggle-panel toggle-right">
                <h1>Welcome Back!</h1><br>
                <p>Enter your personal details to use all of site features</p>
                <button class="btn login-btn" id="register">Sign In</button>
            </div>
        </div>
</div>

<script src="script.js"></script>
</body>
</html>
