<?php
session_start();
include_once('../../connection.php');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function sanitizeInput($input) {
    return filter_var($input, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
}

// Password Update Logic
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_password'])) {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $old_password = $_POST['old_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if ($new_password !== $confirm_password) {
            $_SESSION['message'] = "New password and confirm password do not match.";
            $_SESSION['msg_type'] = "error";
        } else {
            // Check if the email exists
            $checkUserQuery = "SELECT * FROM mechanic WHERE email = ?";
            $stmt = $conn->prepare($checkUserQuery);
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $user = $result->fetch_assoc();

                // Verify the old password
                if (password_verify($old_password, $user['password'])) {
                    // Hash the new password
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
                    $_SESSION['message'] = "Incorrect old password.";
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

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forget Password</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <!-- Forget Password Section -->
        <div class="sign-in">
            <h2>Reset Your Password</h2>
            <form action="forget_password.php" method="POST">
                <div class="input-group">
                    <input type="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="input-group">
                    <input type="password" name="old_password" placeholder="Old Password" required>
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

        <!-- Popup for Messages -->
        <div id="popup" class="popup">
            <div class="popup-content">
                <p id="popup-message"></p>
                <button onclick="closePopup()">OK</button>
            </div>
        </div>
    </div>
</body>
</html>

<!-- CSS for Popup -->
<style>
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
        width: 80%;
        max-width: 300px;
    }
    .popup-content p {
        margin-bottom: 20px;
    }
</style>

<!-- JavaScript for Popup and Redirect -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Check if there's a message set in the session
        <?php if (isset($_SESSION['message'])): ?>
            // Show popup with message
            document.getElementById("popup-message").textContent = "<?php echo $_SESSION['message']; ?>";
            document.getElementById("popup").style.display = "flex";

            // Clear session message after displaying
            <?php unset($_SESSION['message']); unset($_SESSION['msg_type']); ?>
        <?php endif; ?>
    });

    function closePopup() {
        document.getElementById("popup").style.display = "none";

        // Check if the message is a success message to trigger redirect
        <?php if (isset($_SESSION['msg_type']) && $_SESSION['msg_type'] === 'success'): ?>
            // Redirect to login page on successful password update
            window.location.href = "login.php";
        <?php endif; ?>
    }
</script>
