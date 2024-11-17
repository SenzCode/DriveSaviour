<?php
session_start();
require('../../connection.php');

$email = $_POST['email'];
$name = $_POST['name'];
$phone = $_POST['phone'];
$address = $_POST['address'];

$sql = "UPDATE mechanic 
        SET name = ?, phone = ?, address = ? 
        WHERE email = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $name, $phone, $address, $email);

if ($stmt->execute()) {
    $_SESSION['name'] = $name;
    $_SESSION['phone'] = $phone;
    $_SESSION['address'] = $address;

    header('Location: profile.php?status=success');
    exit();
} else {
    header('Location: profile.php?status=error');
    exit();
}

?>