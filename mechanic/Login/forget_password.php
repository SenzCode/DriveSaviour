<?php
session_start();
include_once('../../connection.php');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function sanitizeInput($input) {
    return filter_var($input, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_password'])) {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if ($new_password !== $confirm_password) {
            $_SESSION['message'] = "New password and confirm password do not match.";
            $_SESSION['msg_type'] = "error";
        } else {
            $checkUserQuery = "SELECT * FROM mechanic WHERE email = ?";
            $stmt = $conn->prepare($checkUserQuery);
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $hashedPassword = password_hash($new_password, PASSWORD_BCRYPT);

                // Update the password
                $updatePasswordQuery = "UPDATE mechanic SET password = ? WHERE email = ?";
                $stmt = $conn->prepare($updatePasswordQuery);
                $stmt->bind_param('ss', $hashedPassword, $email);
                if ($stmt->execute()) {
                    $_SESSION['message'] = "Password updated successfully.";
                    $_SESSION['msg_type'] = "success";
                } else {
                    $_SESSION['message'] = "Error: " . $stmt->error;
                    $_SESSION['msg_type'] = "error";
                }
            } else {
                $_SESSION['message'] = "No account found with that email.";
                $_SESSION['msg_type'] = "error";
            }
            $stmt->close();
        }
        header("Location: forget_password.php");
        exit();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forget Password</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #007BFF;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: #ffffff;
            color: #000;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            height:40%;
        }
        .container h2 {
            margin-bottom: 20px;
            text-align: center;
        }
        .input-group {
            margin-bottom: 15px;
        }
        .input-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #007BFF;
            color: #fff;
            border: none;
            padding: 10px;
            width: 100%;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        p {
            text-align: center;
        }
        a {
            color: #007BFF;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }
        .popup-content {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            color: #000;
            width: 80%;
            max-width: 300px;
        }
        .popup-content p {
            margin-bottom: 20px;
        }
        .popup-content button {
            background-color: #007BFF;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .popup-content button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Reset Your Password</h2>
        <form action="forget_password.php" method="POST">
            <div class="input-group">
                <input type="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="input-group">
                <input type="password" name="new_password" placeholder="New Password" required>
            </div>
            <div class="input-group">
                <input type="password" name="confirm_password" placeholder="Confirm New Password" required>
            </div>
            <button type="submit" name="update_password">Update Password</button>
        </form>
        <p>Remember your password? <a href="login.php">Sign in here</a></p>
    </div>

    <div id="popup" class="popup">
        <div class="popup-content">
            <p id="popup-message"></p>
            <button onclick="closePopup()">OK</button>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
    <?php if (isset($_SESSION['message'])): ?>
        document.getElementById("popup-message").textContent = "<?php echo $_SESSION['message']; ?>";
        document.getElementById("popup").style.display = "flex";
        <?php unset($_SESSION['message']); unset($_SESSION['msg_type']); ?>
    <?php endif; ?>
});

function closePopup() {
    document.getElementById("popup").style.display = "none";
    <?php if (isset($_SESSION['msg_type']) && $_SESSION['msg_type'] === 'success'): ?>
        window.location.href = "login.php"; // Redirect to the sign-in page on success
    <?php else: ?>
        window.location.href = "forget_password.php"; // Reload forget_password page on error
    <?php endif; ?>
}

    </script>
</body>
</html>
