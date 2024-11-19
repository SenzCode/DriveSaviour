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

    // Check if the same address and branch already exist for another shop
    $checkShopQuery = "SELECT * FROM shops WHERE address = '$address' OR branch = '$branch'";
    $shopResult = mysqli_query($conn, $checkShopQuery);

    if (mysqli_num_rows($shopResult) > 0) {
        // If shop with same address and branch exists, show error message
        $error = "A shop already exists with the same address and branch.";
        header("Location: shop.php?message=error&error=" . urlencode($error));
        exit;
    }

    // Check if the owner email exists in shop_owner table
    $checkEmailQuery = "SELECT email FROM shop_owner WHERE email = '$ownerEmail'";
    $emailResult = mysqli_query($conn, $checkEmailQuery);

    if (mysqli_num_rows($emailResult) > 0) {
        // Insert the new shop into the database
        $sql = "INSERT INTO shops (shop_name, email, number, address, branch, ownerEmail) 
                VALUES ('$shop_name', '$email', '$number', '$address', '$branch', '$ownerEmail')";

        if (mysqli_query($conn, $sql)) {
            // Redirect with success message
            header("Location: shop.php?message=insert");
            exit;
        } else {
            // Handle database error
            $error = mysqli_error($conn);
            header("Location: shop.php?message=error&error=" . urlencode($error));
            exit;
        }
    } else {
        // If owner email does not exist in shop_owner table, show error
        $error = "Owner email does not exist in the shop_owner table.";
        header("Location: shop.php?message=error&error=" . urlencode($error));
        exit;
    }
} else {
    // Redirect if the form is not submitted
    header("Location: shop.php");
    exit;
}
