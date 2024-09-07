<?php
// Start the session
session_start();

require '../navbar/nav.php';
include_once('../../connection.php');

// Check if form is submitted
if (isset($_POST['action'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $shop_name = mysqli_real_escape_string($conn, $_POST['shop_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $number = mysqli_real_escape_string($conn, $_POST['number']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $branch = mysqli_real_escape_string($conn, $_POST['branch']);
    
    if ($_POST['action'] == 'edit') {
        $query = "UPDATE shops SET shop_name='$shop_name', email='$email', number='$number', address='$address', branch='$branch' WHERE id='$id'";
        if (mysqli_query($conn, $query)) {
            header('Location: manage_shops.php?message=edit');
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } elseif ($_POST['action'] == 'delete') {
        $query = "DELETE FROM shops WHERE id='$id'";
        if (mysqli_query($conn, $query)) {
            header('Location: manage_shops.php?message=delete');
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}

// Close the database connection
mysqli_close($conn);
?>
