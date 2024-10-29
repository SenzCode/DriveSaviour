<?php
session_start();

require '../navbar/nav.php';
require '../../connection.php';

$user_email = $_SESSION['email'];

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_password'])) {
    $oldPassword = $_POST['oldPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmNewPassword = $_POST['confirmNewPassword'];

    
    $query = "SELECT password FROM shop_owner WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $user_email);
    $stmt->execute();
    $stmt->bind_result($currentPasswordHash);
    $stmt->fetch();
    $stmt->close();

    if (password_verify($oldPassword, $currentPasswordHash)) {
        if ($newPassword === $confirmNewPassword) {
            $newPasswordHash = password_hash($newPassword, PASSWORD_BCRYPT);

            $updateQuery = "UPDATE shop_owner SET password = ? WHERE email = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param('ss', $newPasswordHash, $user_email);
            if ($updateStmt->execute()) {
                $success = "Your password has been updated successfully.";
            } else {
                $error = "An error occurred while updating your password. Please try again.";
            }
            $updateStmt->close();
        } else {
            $error = "New password and confirmation do not match.";
        }
    } else {
        $error = "Old password is incorrect.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="../navbar/style.css">
</head>
<body>
    <div class="main-content">
        <h1>Manage Password</h1>

        <?php if ($error): ?>
            <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
            <p class="success-message"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>

        <form action="manage_password.php" method="POST">
            <div class="password-field">
                <label for="oldPassword">Old Password:</label>
                <input type="password" id="oldPassword" name="oldPassword" required>
                <i class="fa fa-eye toggle-password" onclick="togglePassword('oldPassword', this)"></i>
            </div>
            <div class="password-field">
                <label for="newPassword">New Password:</label>
                <input type="password" id="newPassword" name="newPassword" required>
                <i class="fa fa-eye toggle-password" onclick="togglePassword('newPassword', this)"></i>
            </div>
            <div class="password-field">
                <label for="confirmNewPassword">Confirm New Password:</label>
                <input type="password" id="confirmNewPassword" name="confirmNewPassword" required>
                <i class="fa fa-eye toggle-password" onclick="togglePassword('confirmNewPassword', this)"></i>
            </div>
            <button type="submit" name="update_password">Change Password</button>
        </form>
    </div>

    <script>
        function togglePassword(fieldId, icon) {
            const field = document.getElementById(fieldId);
            if (field.type === "password") {
                field.type = "text";
                icon.classList.replace("fa-eye", "fa-eye-slash");
            } else {
                field.type = "password";
                icon.classList.replace("fa-eye-slash", "fa-eye");
            }
        }
    </script>
</body>
</html>