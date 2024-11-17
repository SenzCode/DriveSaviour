<?php
session_start();
include_once('../../connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $shop_name = mysqli_real_escape_string($conn, $_POST['shop_name']);
    $email = $_POST['email'];
    $number = $_POST['number'];
    $address = $_POST['address'];
    $branch = $_POST['branch'];
    $ownerEmail = $_POST['ownerEmail'];

    $checkEmailQuery = "SELECT email FROM shop_owner WHERE email = '$ownerEmail'";
    $emailResult = mysqli_query($conn, $checkEmailQuery);

    if (mysqli_num_rows($emailResult) > 0) {
        $sql = "INSERT INTO shops (shop_name, email, number, address, branch, ownerEmail) 
                VALUES ('$shop_name', '$email', '$number', '$address', '$branch', '$ownerEmail')";

        if (mysqli_query($conn, $sql)) {
            header("Location: shop.php?message=insert");
            exit;
        } else {
            $error = mysqli_error($conn);
            header("Location: shop.php?message=error&error=" . urlencode($error));
            exit;
        }
    } else {
        $error = "Owner email does not exist in the shop_owner table";
        header("Location: shop.php?message=error&error=" . urlencode($error));
        exit;
    }
} else {
    header("Location: shop.php");
    exit;
}
