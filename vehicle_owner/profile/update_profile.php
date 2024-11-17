<?php
session_start();
require('../../connection.php');
$email = $_POST['email'];
$name = $_POST['name'];
$phone = $_POST['phone'];
$city = $_POST['city'];

$sql = "UPDATE vehicle_owner 
        SET name = ?, phone = ?, city = ? 
        WHERE email = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $name, $phone, $city, $email);

if ($stmt->execute()) {
    $_SESSION['name'] = $name;
    $_SESSION['phone'] = $phone;
    $_SESSION['city'] = $city;

    header('Location: profile.php?message=update_prof');
    exit();
} else {
    header('Location: profile.php?status=error');
    exit();
}

?>