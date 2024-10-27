<?php
session_start();
require '../../connection.php';

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    echo "User is not logged in.";
    exit;
}

// Check if cart_id is set in the POST request
if (isset($_POST['cart_id'])) {
    $cart_id = $_POST['cart_id'];

    // Prepare the DELETE statement
    $query = "DELETE FROM cart WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $cart_id);

    if ($stmt->execute()) {
        // Successfully removed the itema
        header("Location: view_cart.php?success=Item removed from cart.");
        exit();
    } else {
        // Error handling
        header("Location: view_cart.php?error=Failed to remove item.");
        exit();
    }

} else {
    // If no cart_id is provided
    header("Location: view_cart.php?error=No item selected.");
    exit();
}

?>
