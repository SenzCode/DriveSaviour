<?php
session_start();
require '../../connection.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone = $_POST['phone'];
    $city = $_POST['city'];

    // Check if the email already exists
    $checkEmail = $conn->prepare("SELECT * FROM vehicle_owner WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $result = $checkEmail->get_result();

    if ($result->num_rows > 0) {
        $message = "Email already registered. Please use a different email.";
    } else {
        $stmt = $conn->prepare("INSERT INTO vehicle_owner (name, email, password, phone, city) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $password, $phone, $city);

        if ($stmt->execute()) {
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;
            $_SESSION['phone'] = $phone;
            $_SESSION['city'] = $city;

            $message = "Registration successful!";
        } else {
            $message = "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    $checkEmail->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM vehicle_owner WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['userID'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['phone'] = $user['phone'];
            $_SESSION['city'] = $user['city'];

            header("Location: ../loader.php");
            exit();
        } else {
            $message = "Invalid password!";
        }
    } else {
        $message = "No user found with this email.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <title>DriveSaviour</title>
    <link rel="icon" type="image/png" href="../../img/logo.jpg">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>

<div class="container">
    <div class="form-box login">
        <form action="login.php" method="POST">
            <h1>Login</h1>

            <!-- Display the message here -->
            <?php if (!empty($message)) : ?>
                <p style="color: red;" class="message"><?php echo $message; ?></p>
            <?php endif; ?>

            <div class="input-box">
                <input type="email" name="email" placeholder="Email" required>
                <i class='bx bx-envelope'></i>
            </div>
            <div class="input-box">
                <input type="password" name="password" placeholder="Password" required>
                <i class='bx bxs-lock-alt'></i>
            </div>
            <div class="forgot-link">
                <a href="">Forgot Password?</a>
            </div>
            <button type="submit" class="btn" name="login">Login</button>
            <p>our social platforms</p>
            <div class="social-icon">
                <a href=""><i class='bx bxl-google'></i></a>
                <a href=""><i class='bx bxl-facebook'></i></a>
                <a href=""><i class='bx bxl-github'></i></a>
                <a href=""><i class='bx bxl-linkedin'></i></a>
            </div>
        </form>
    </div>

    <div class="form-box register">
        <form action="login.php" method="POST">
            <h1>Register</h1>
            <div class="input-box">
                <input type="email" name="email" placeholder="Email" required>
                <i class='bx bx-envelope'></i>
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
                <i class='bx bxs-phone'></i>
            </div>
            <div class="input-box">
                <input type="text" name="city" placeholder="City" required>
                <i class='bx bx-map-pin'></i>
            </div>
            <div class="forgot-link">
                <a href="">Forgot Password?</a>
            </div>
            <button type="submit" name="register" class="btn">Register</button>
            <p>or register with social platforms</p>
            <div class="social-icon">
                <a href=""><i class='bx bxl-google'></i></a>
                <a href=""><i class='bx bxl-facebook'></i></a>
                <a href=""><i class='bx bxl-github'></i></a>
                <a href=""><i class='bx bxl-linkedin'></i></a>
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
