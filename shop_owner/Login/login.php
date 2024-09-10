<?php
session_start(); // Start the session at the beginning of the script
include_once('../../connection.php');
 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
 
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
}
$conn->close();
?>

 
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login</title>
<!-- <link rel="stylesheet" href="style.css"> -->
<link rel="stylesheet" href="style-new.css">
<script src="script.js"></script>
</head>
<body>
<div class="container">
<h2 class="text-topic">Login</h2>
<form method="post" action="login.php" onsubmit="return validateLoginForm()">
<div class="mb-3">
<label for="email" class="form-label">Email</label><br>
<input type="email" class="form-control" id="email" name="email" required>
</div>
<div class="mb-3">
<label for="password" class="form-label">Password</label><br>
<input type="password" class="form-control" id="password" name="password" required>
</div>
<button type="submit" class="btn-add">Login</button><br><br>
<a href="register.html" class="btn-add2">Create an Account</a>
</form>
</div>
</body>
</html>

 