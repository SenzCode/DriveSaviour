<?php
// Ensure no output before this line
session_start(); // Start session at the very top

require '../navbar/nav.php'; 
require '../../connection.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login/login.php?redirect=" . urlencode($_SERVER['REQUEST_URI']));
    exit();
}

$user_id = $_SESSION['user_id'];

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_password'])) {
    // Get form input
    $oldPassword = $_POST['oldPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmNewPassword = $_POST['confirmNewPassword'];

    // Fetch the current password from the database
    $query = "SELECT password FROM shop_owner WHERE ownerID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->bind_result($currentPasswordHash);
    $stmt->fetch();
    $stmt->close();

    if (password_verify($oldPassword, $currentPasswordHash)) {
        if ($newPassword === $confirmNewPassword) {
            $newPasswordHash = password_hash($newPassword, PASSWORD_BCRYPT);

            $updateQuery = "UPDATE shop_owner SET password = ? WHERE ownerID = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param('si', $newPasswordHash, $user_id);
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

        <!-- Display error or success message -->
        <?php if ($error): ?>
            <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
            <p class="success-message"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>

        <form action="manage_password.php" method="POST">
            <div>
                <label for="oldPassword">Old Password:</label>
                <input type="password" id="oldPassword" name="oldPassword" required>
            </div>
            <div>
                <label for="newPassword">New Password:</label>
                <input type="password" id="newPassword" name="newPassword" required>
            </div>
            <div>
                <label for="confirmNewPassword">Confirm New Password:</label>
                <input type="password" id="confirmNewPassword" name="confirmNewPassword" required>
            </div>
            <button type="submit" name="update_password">Change Password</button>
        </form>
    </div>
</body>
</html>
