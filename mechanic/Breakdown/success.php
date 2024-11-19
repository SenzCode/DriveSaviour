<?php
session_start();
require '../../connection.php';

// Retrieve the mechanic email
if (isset($_GET['email'])) {
    $email = $_GET['email'];
} elseif (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
} else {
    die("Error: Mechanic email is missing!");
}

// Check if the mechanic's email exists in the `mechanic` table
$checkMechanicExists = $conn->prepare("SELECT * FROM mechanic WHERE email = ?");
$checkMechanicExists->bind_param("s", $email);
$checkMechanicExists->execute();
$resultMechanic = $checkMechanicExists->get_result();

if ($resultMechanic->num_rows === 0) {
    die("Error: Mechanic email does not exist in the mechanic table.");
}
$checkMechanicExists->close();

// Set purchase and expiry dates
$purchase_date = date('Y-m-d');
$expire_date = date('Y-m-d', strtotime('+1 month'));

// Check if the mechanic's email already exists in the `paid_mechanics` table
$checkMechanicQuery = $conn->prepare("SELECT * FROM paid_mechanics WHERE email = ?");
$checkMechanicQuery->bind_param("s", $email);
$checkMechanicQuery->execute();
$result = $checkMechanicQuery->get_result();

if ($result->num_rows > 0) {
    // Update existing record
    $updateQuery = $conn->prepare("UPDATE paid_mechanics SET purchase_date = ?, expire_date = ? WHERE email = ?");
    $updateQuery->bind_param("sss", $purchase_date, $expire_date, $email);

    if ($updateQuery->execute()) {
        echo "Subscription updated successfully!";
        header('Location: breakdown.php');
        exit;
    } else {
        echo "Error updating subscription: " . $updateQuery->error;
    }

    $updateQuery->close();
} else {
    // Insert new record
    $insertQuery = $conn->prepare("INSERT INTO paid_mechanics (email, purchase_date, expire_date) VALUES (?, ?, ?)");
    $insertQuery->bind_param("sss", $email, $purchase_date, $expire_date);

    if ($insertQuery->execute()) {
        echo "Subscription created successfully!";
        header('Location: breakdown.php');
        exit;
    } else {
        echo "Error creating subscription: " . $insertQuery->error;
    }

    $insertQuery->close();
}

$checkMechanicQuery->close();
$conn->close();
?>
