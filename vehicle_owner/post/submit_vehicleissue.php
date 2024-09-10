<?php
session_start();
require('../../connection.php');

// Check if email and phone are set in session
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
$location = $_POST['location'];
$mech_id = isset($_POST['mech_id']) ? $_POST['mech_id'] : null;

// Retrieve v_id from the vehicle table based on number_plate, model, or other identifiers
$sql_vehicle = "SELECT v_id FROM vehicle WHERE number_plate = ? AND email = ?";
$stmt_vehicle = $conn->prepare($sql_vehicle);
$stmt_vehicle->bind_param("ss", $number_plate, $email);
$stmt_vehicle->execute();
$result_vehicle = $stmt_vehicle->get_result();

if ($result_vehicle->num_rows > 0) {
    $row_vehicle = $result_vehicle->fetch_assoc();
    $v_id = $row_vehicle['v_id'];

    // Insert data into vehicleissues table
    $sql = "INSERT INTO vehicleissues (v_id, email, vehicle_model, year, mobile_number, vehicle_issue, status, location, mech_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssssss", $v_id, $email, $model, $year, $contact, $vehicle_issue, $status, $location, $mech_id);

    if ($stmt->execute()) {
        echo "Vehicle issue reported successfully!";
        header('Location: post.php');
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
} else {
    echo "Error: Vehicle not found in the database.";
}

// Close vehicle statement and connection
$stmt_vehicle->close();
$conn->close();
?>
