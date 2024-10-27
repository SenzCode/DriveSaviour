<?php
// Start the session and include the connection
session_start();
require '../../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['id'];
    $shop_id = $_POST['shop_id'];
    $quantity = (int)$_POST['quantity'];
    $username = $_SESSION['username'];  // Assuming the username is stored in the session

    // Fetch product details
    $product_query = "SELECT * FROM products WHERE id = $product_id";
    $product_result = mysqli_query($conn, $product_query);
    $product = mysqli_fetch_assoc($product_result);

    if ($product && $quantity > 0 && $quantity <= $product['quantity_available']) {
        $price = $product['price'];
        $product_name = $product['product_name'];
        $image_url = $product['image_url'];

        // Insert into CartTable
        $cart_query = "INSERT INTO CartTable (shop_id, cat_id, product_name, image_url, quantity, price, username) 
                       VALUES ($shop_id, {$product['cat_id']}, '$product_name', '$image_url', $quantity, $price, '$username')";

        $cart_result = mysqli_query($conn, $cart_query);

        if ($cart_result) {
            // Reduce quantity in the ProductsTable
            $new_quantity = $product['quantity_available'] - $quantity;
            $update_product_query = "UPDATE products SET quantity_available = $new_quantity WHERE id = $product_id";
            mysqli_query($conn, $update_product_query);

            // Redirect to the cart page with a success message
            header("Location: view_cart.php?success=Product added to cart successfully");
            exit();
        } else {
            // Handle the error during the cart insertion
            echo "Error adding product to cart: " . mysqli_error($conn);
        }
    } else {
        // If the quantity is invalid or exceeds available stock
        echo "Invalid quantity or product not available.";
    }
} else {
    echo "Invalid request.";
}
?>
