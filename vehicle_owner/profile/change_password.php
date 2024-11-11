<?php
session_start();
require('../../connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_SESSION['email'];
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Check if new passwords match
    if ($newPassword !== $confirmPassword) {
        header("Location: profile.php?message=Password+Mismatch");
        exit;
    }

    // Retrieve current password from the database
    $query = "SELECT password FROM vehicle_owner WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($dbPassword);
    $stmt->fetch();
    $stmt->close();

    // Verify current password
    if (password_verify($currentPassword, $dbPassword)) {
        // Hash new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update the password in the database
        $updateQuery = "UPDATE vehicle_owner SET password = ? WHERE email = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("ss", $hashedPassword, $email);
        
        if ($updateStmt->execute()) {
            header("Location: profile.php?message=Password+Updated");
        } else {
            header("Location: profile.php?message=Update+Failed");
        }
        $updateStmt->close();
    } else {
        header("Location: profile.php?message=Incorrect+Current+Password");
    }
    $conn->close();
}
?>
