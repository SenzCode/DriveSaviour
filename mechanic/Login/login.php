<?php
session_start();
include_once('../../connection.php');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function sanitizeInput($input) {
    return filter_var($input, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
}

// Registration Logic
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['register'])) {
        $name = sanitizeInput($_POST['name']);
        $phone = sanitizeInput($_POST['phone']);
        $address = sanitizeInput($_POST['address']);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];

        $checkEmailQuery = "SELECT * FROM mechanic WHERE email = ?";
        $stmt = $conn->prepare($checkEmailQuery);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "Email already in use. Please choose a different email.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $insertQuery = "INSERT INTO mechanic (name, phone, address, email, password) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param('sssss', $name, $phone, $address, $email, $hashedPassword);
            if ($stmt->execute()) {
                $_SESSION['userID'] = $conn->insert_id;
                $_SESSION['name'] = $name;
                $_SESSION['email'] = $email;
                $_SESSION['address'] = $address;
                $_SESSION['phone'] = $phone;
                $_SESSION['logged_in'] = true;
                echo "New Account added successfully.";
            } else {
                echo "Error: " . $stmt->error;
            }
        }
        $stmt->close();
    }

    // Login Logic
    elseif (isset($_POST['login'])) {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];

        $query = "SELECT * FROM mechanic WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['userID'] = $user['userID'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['address'] = $user['address'];
                $_SESSION['phone'] = $user['phone'];
                $_SESSION['logged_in'] = true;
                header('Location: ../products/product.php');
                exit();
            } else {
                echo "Invalid email or password.";
            }
        } else {
            echo "Invalid email or password.";
        }
        $stmt->close();
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <!-- Sign In Section -->
        <div class="sign-in">
    <h2>Sign In</h2>
    <div class="social-buttons">
                <a href="#"><img src="https://img.icons8.com/color/48/000000/google-logo.png" alt="Google"></a>
                <a href="#"><img src="https://img.icons8.com/ios-filled/50/000000/mac-os.png" alt="Apple"></a>
                <a href="#"><img src="https://img.icons8.com/color/48/000000/facebook-new.png" alt="Facebook"></a>
            </div>
    <p>Sign in with google or email and password</p>
    
    <form action="login.php" method="POST">
        <div class="input-group">
            <input type="email" name="email" placeholder="Email" required>
        </div>
        <div class="input-group">
            <input type="password" name="password" placeholder="Password" required>
        </div>
        <p><a href="forget_password.php">Forget Your Password?</a></p>
        <button type="submit" name="login">SIGN IN</button>
    </form>
    <p>Don’t have an account? <a href="signup.php">Sign up here</a></p>
</div>


        <!-- Sign Up Section -->
        
    </div>
</body>
</html>
