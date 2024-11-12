<?php
// Start session
session_start();
require 'connection.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userType = $_POST['userType'];
    $password = $_POST['password'];

    // Sanitize user input
    $password = $conn->real_escape_string($password);

    if ($userType == "admin") {
        $email = $_POST['email'];
        $email = $conn->real_escape_string($email);
        $sql = "SELECT * FROM admin WHERE email = '$email' AND password = '$password'";
    } elseif ($userType == "mechanic") {
        $email = $_POST['email'];
        $email = $conn->real_escape_string($email);
        $sql = "SELECT * FROM mechanic WHERE email = '$email'";
    } elseif ($userType == "shop_owner") {
        $email = $_POST['email'];
        $email = $conn->real_escape_string($email);
        $sql = "SELECT * FROM shop_owner WHERE email = '$email'";
    } elseif ($userType == "vehicle_owner"){
        $email = $_POST['email'];
        $email = $conn->real_escape_string($email);
        $sql = "SELECT * FROM vehicle_owner WHERE email = '$email'";
    } else {
        echo "Invalid user type selected.";
        exit();
    }

    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Password verification
        if ($userType == "mechanic" || $userType == "shop_owner" || $userType == "vehicle_owner") {
            if (password_verify($password, $row['password'])) {
                $_SESSION['userID'] = $row['userID'] ?? $row['ownerID'];
                $_SESSION['name'] = $row['name'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['userType'] = $userType;
                header("Location: {$userType}/Login/login.php"); // Redirect to the appropriate dashboard
                exit();
            } else {
                echo "Invalid password.";
            }
        } elseif ($userType == "admin" && $row['password'] == $password) {
            $_SESSION['email'] = $row['email'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['userType'] = $userType;
            header("Location: admin/Login/login.php"); // Redirect to admin dashboard
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with the provided credentials.";
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
        <!-- Left side panel for blue background -->
        <div class="left-panel">
            <h2>Welcome Back!</h2>
            <p>Login to continue to your dashboard</p>
        </div>

        <!-- Right side form box -->
        <div class="form-box">
            <form action="index.php" method="post">
                <h1>Login</h1>

                <div class="input-box">
                    <label for="userType">Login as:</label>
                    <select id="userType" name="userType" onchange="toggleInput()">
                        <option value="admin">Admin</option>
                        <option value="mechanic">Mechanic</option>
                        <option value="shop_owner">Shop Owner</option>
                        <option value="vehicle_owner">Vehicle Owner</option>
                    </select>
                </div>

                <div class="input-box" id="emailInput">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                </div>

                <div class="input-box">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>

                <input type="submit" value="Login" class="btn">
            </form>
        </div>
    </div>

    <script>
        function toggleInput() {
            var userType = document.getElementById("userType").value;
            var emailInput = document.getElementById("emailInput");
            emailInput.style.display = "block";
        }

        // Initialize form
        toggleInput();
    </script>
</body>
</html>
