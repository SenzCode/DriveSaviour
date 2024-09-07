<?php
// Start the session
session_start();

// Include the database connection
include_once('../../connection.php');

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if action is edit (Edit Shop)
    if ($_POST['action'] === 'edit') {
        // Retrieve values from POST
        $shop_id = mysqli_real_escape_string($conn, $_POST['id']);
        $shop_name = mysqli_real_escape_string($conn, $_POST['shop_name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $number = mysqli_real_escape_string($conn, $_POST['number']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);
        $branch = mysqli_real_escape_string($conn, $_POST['branch']);

        // Perform update query
        $sql = "UPDATE shops SET 
                shop_name = '$shop_name', 
                email = '$email', 
                number = '$number', 
                address = '$address', 
                branch = '$branch'
                WHERE id = '$shop_id'";

        if (mysqli_query($conn, $sql)) {
            // Redirect with success message
            header("Location: shop.php?message=edit");
            exit;
        } else {
            // Redirect with error message
            header("Location: shop.php?message=error");
            exit;
        }
    } elseif ($_POST['action'] === 'delete') {
        // Handle delete action
        $shop_id = mysqli_real_escape_string($conn, $_POST['id']);

        // Perform delete query
        $sql = "DELETE FROM shops WHERE id = '$shop_id'";

        if (mysqli_query($conn, $sql)) {
            // Redirect with success message
            header("Location: shop.php?message=delete");
            exit;
        } else {
            // Redirect with error message
            header("Location: shop.php?message=error");
            exit;
        }
    } else {
        // Invalid action
        header("Location: shop.php?message=error");
        exit;
    }
} else {
    // Redirect if accessed directly without POST request
    header("Location: shop.php");
    exit;
}
?>
