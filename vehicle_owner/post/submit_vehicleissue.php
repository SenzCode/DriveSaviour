<?php
session_start();
require('../../connection.php');

$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
$contact = isset($_SESSION['phone']) ? $_SESSION['phone'] : '';

// Retrieve data from POST request
$model = $_POST['model'];
$year = $_POST['year'];
$number_plate = $_POST['number_plate'];
$fuel_type = $_POST['fuel_type'];
$engine_type = $_POST['engine_type'];
$tire_size = $_POST['tire_size'];
$vehicle_issue = $_POST['vehicle_issue'];
$status = 'Pending';
$location = $_POST['location']; // Get the location

// Insert data into vehicleissues table
$sql = "INSERT INTO vehicleissues (email, vehicle_model, year, mobile_number, vehicle_issue, status, location) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssss", $email, $model, $year, $contact, $vehicle_issue, $status, $location); // Bind location here

if ($stmt->execute()) {
    echo "Vehicle issue reported successfully!";
    header('Location: post.php');
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
