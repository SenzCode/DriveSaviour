<?php
session_start();
require('../../connection.php');

// Retrieve form data
$email = $_POST['email'];
$name = $_POST['name'];
$phone = $_POST['contact'];
$number_plate = $_POST['number_plate'];
$model = $_POST['model'];
$year = $_POST['year'];
$fuel_type = $_POST['fuel_type'];
$engine_type = $_POST['engine_type'];
$tire_size = $_POST['tire_size'];

// Prepare SQL query to insert the data into the vehicle table
$sql = "INSERT INTO vehicle (email, name, contact, number_plate, model, year, fuel_type, engine_type, tire_size) 
        VALUES ('$email', '$name', '$phone', '$number_plate', '$model', '$year', '$fuel_type', '$engine_type', '$tire_size')";

if (mysqli_query($conn, $sql)) {
    // Redirect or display a success message
    echo "Vehicle details added successfully!";
    header('Location: profile.php'); // Adjust this to your actual profile page
    exit(); // Add exit after the header to ensure no further script execution
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);
?>
