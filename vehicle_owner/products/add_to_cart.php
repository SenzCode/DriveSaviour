<?php
// Start the session and include the connection
session_start();
require '../../connection.php';

$response = '';  // Initialize response message

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

            // Set success response
            $response = "Product added to cart successfully!";
        } else {
            // Set error response for cart insertion failure
            $response = "Error adding product to cart. Please try again.";
        }
    } else {
        // Set error response for invalid quantity or product availability
        $response = "Invalid quantity or product not available.";
    }
} else {
    $response = "Invalid request.";
}

// Return the response message
echo $response;
?>
