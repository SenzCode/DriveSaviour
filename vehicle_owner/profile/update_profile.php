<?php
session_start();
require('../../connection.php');

// Retrieve form data
$email = $_POST['email'];
$name = $_POST['name'];
$phone = $_POST['phone'];
$city = $_POST['city'];

// Prepare SQL query to update the data in the vehicle_owner table
$sql = "UPDATE vehicle_owner 
        SET name = ?, phone = ?, city = ? 
        WHERE email = ?";

// Prepare statement
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $name, $phone, $city, $email);

if ($stmt->execute()) {
    // Update session variables with new profile data
    $_SESSION['name'] = $name;
    $_SESSION['phone'] = $phone;
    $_SESSION['city'] = $city;

    // Redirect to the profile page with success message
    header('Location: profile.php?status=success');
    exit(); // Add exit after the header to ensure no further script execution
} else {
    // Redirect to the profile page with error message
    header('Location: profile.php?status=error');
    exit(); // Add exit after the header to ensure no further script execution
}

// Close statement and connection
$stmt->close();
$conn->close();
?>