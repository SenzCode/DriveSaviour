<?php
session_start();
include_once('../../connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $shop_name = $_POST['shop_name'];
    $email = $_POST['email'];
    $number = $_POST['number'];
    $address = $_POST['address'];
    $branch = $_POST['branch'];
    $ownerEmail = $_POST['ownerEmail'];  // Ensure you retrieve the ownerEmail from the form

    // Check if the ownerEmail exists in the shop_owner table
    $checkEmailQuery = "SELECT email FROM shop_owner WHERE email = '$ownerEmail'";
    $emailResult = mysqli_query($conn, $checkEmailQuery);

    if (mysqli_num_rows($emailResult) > 0) {
        // Proceed with the insertion if the email exists
        $sql = "INSERT INTO shops (shop_name, email, number, address, branch, ownerEmail) 
                VALUES ('$shop_name', '$email', '$number', '$address', '$branch', '$ownerEmail')";

        if (mysqli_query($conn, $sql)) {
            // Redirect to shop.php with a success message
            header("Location: shop.php?message=insert");
            exit;
        } else {
            // Redirect to shop.php with an error message
            $error = mysqli_error($conn);
            header("Location: shop.php?message=error&error=" . urlencode($error));
            exit;
        }
    } else {
        // Redirect to shop.php with an error message if ownerEmail doesn't exist
        $error = "Owner email does not exist in the shop_owner table";
        header("Location: shop.php?message=error&error=" . urlencode($error));
        exit;
    }
} else {
    // Redirect back if the request method is not POST
    header("Location: shop.php");
    exit;
}
