<?php
session_start();
include_once('../../connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['id'];
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $quantity_available = (int) $_POST['quantity_available'];
    $price = (float) $_POST['price'];

    if ($_POST['action'] == 'edit') {
        // Update query
        $sql = "UPDATE products SET product_name='$product_name', quantity_available='$quantity_available', price='$price' WHERE id='$product_id'";
        if (mysqli_query($conn, $sql)) {
            header("Location: products.php?message=edit");
            exit;
        } else {
            $error = mysqli_error($conn);
            header("Location: products.php?message=error&error=" . urlencode($error));
            exit;
        }
    } elseif ($_POST['action'] == 'delete') {
        // Delete query
        $sql = "DELETE FROM products WHERE id='$product_id'";
        if (mysqli_query($conn, $sql)) {
            header("Location: products.php?message=delete");
            exit;
        } else {
            $error = mysqli_error($conn);
            header("Location: products.php?message=error&error=" . urlencode($error));
            exit;
        }
    }
} else {
    header("Location: products.php");
    exit;
}
?>
