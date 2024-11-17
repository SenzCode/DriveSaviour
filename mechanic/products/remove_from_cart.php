<?php
session_start();
require '../../connection.php';

if (!isset($_SESSION['email'])) {
    echo "User is not logged in.";
    exit;
}

if (isset($_POST['cart_id'])) {
    $cart_id = $_POST['cart_id'];

    $query = "DELETE FROM mech_cart WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $cart_id);

    if ($stmt->execute()) {
        header("Location: view_cart.php?message=removed");
        exit();
    } else {
        header("Location: view_cart.php?message=err");
        exit();
    }

} else {
    header("Location: view_cart.php?error=No item selected.");
    exit();
}

?>
