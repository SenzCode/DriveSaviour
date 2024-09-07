<?php
// Start the session
session_start();

// Include the database connection
include_once('../../connection.php');

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if action is insert (Add Shop)
    if ($_POST['action'] === 'insert') {
        // Retrieve values from POST
        $shop_name = mysqli_real_escape_string($conn, $_POST['shop_name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $number = mysqli_real_escape_string($conn, $_POST['number']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);
        $branch = mysqli_real_escape_string($conn, $_POST['branch']);

        // Perform insert query
        $sql = "INSERT INTO shops (shop_name, email, number, address, branch) 
                VALUES ('$shop_name', '$email', '$number', '$address', '$branch')";

        if (mysqli_query($conn, $sql)) {
            // Redirect with success message
            header("Location: shop.php?message=insert");
            exit;
        } else {
            // Redirect with error message and SQL error details
            header("Location: shop.php?message=error&error=" . urlencode(mysqli_error($conn)));
            exit;
        }
    } else {
        // Invalid action
        header("Location: shop.php?message=error&action=invalid");
        exit;
    }
} else {
    // Redirect if accessed directly without POST request
    header("Location: shop.php?message=error&access=direct");
    exit;
}
?>
