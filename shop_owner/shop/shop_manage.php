<?php
session_start();
include_once('../../connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $shop_id = $_POST['id'];
    $shop_name = mysqli_real_escape_string($conn, $_POST['shop_name']);
    $email = $_POST['email'];
    $number = $_POST['number'];
    $address = $_POST['address'];
    $branch = $_POST['branch'];
    $ownerID = $_POST['ownerEmail'];

    if ($_POST['action'] == 'edit') {
        // Update query
        $sql = "UPDATE shops SET shop_name='$shop_name', email='$email', number='$number', address='$address', branch='$branch' WHERE id='$shop_id'";
        if (mysqli_query($conn, $sql)) {
            header("Location: shop.php?message=edit");
            exit;
        } else {
            $error = mysqli_error($conn);
            header("Location: shop.php?message=error&error=" . urlencode($error));
            exit;
        }
    } elseif ($_POST['action'] == 'delete') {
        // Delete query
        $sql = "DELETE FROM shops WHERE id='$shop_id'";
        if (mysqli_query($conn, $sql)) {
            header("Location: shop.php?message=delete");
            exit;
        } else {
            $error = mysqli_error($conn);
            header("Location: shop.php?message=error&error=" . urlencode($error));
            exit;
        }
    }
} else {
    header("Location: shop.php");
    exit;
}
