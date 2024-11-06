<?php
session_start();
require('../../connection.php');

// Retrieve form data
$email = $_POST['email'];
$name = $_POST['name'];
$phone = $_POST['phone'];
$city = $_POST['city'];

// Prepare SQL query to update profile data
$sql = "UPDATE vehicle_owner 
        SET name = ?, phone = ?, city = ? 
        WHERE email = ?";

// Prepare statement
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $name, $phone, $city, $email);

// Execute query and check if successful
if ($stmt->execute()) {
    // Update session variables
    $_SESSION['name'] = $name;
    $_SESSION['phone'] = $phone;
    $_SESSION['city'] = $city;

    // Redirect with success message
    header('Location: profile.php?status=success');
    exit();
} else {
    // Redirect with error message
    header('Location: profile.php?status=error');
    exit();
}

// Close statement and connection
$stmt->close();
$conn->close();
?>
