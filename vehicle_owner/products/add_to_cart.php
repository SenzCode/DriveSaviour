<?php
session_start(); // Start the session
require '../../connection.php';

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    echo "User is not logged in. Please log in to add items to the cart.";
    exit;
}

// Get the logged-in user's email
$email = $_SESSION['email'];

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $product_id = $_POST['id'];
    $shop_id = $_POST['shop_id'];
    $quantity = $_POST['quantity'];

    // Get the product price and available quantity
    $query = "SELECT price, quantity_available FROM products WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        $price = $product['price'];
        $available_quantity = $product['quantity_available'];

        // Check if the requested quantity is available
        if ($quantity > $available_quantity) {
            echo "Insufficient quantity available.";
            exit;
        }

        // Calculate total price
        $total_price = $price * $quantity;

        // Insert into the cart table
        $insert_query = "INSERT INTO cart (product_id, email, quantity, price, total_price) VALUES (?, ?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bind_param("isidd", $product_id, $email, $quantity, $price, $total_price);

        if ($insert_stmt->execute()) {
            echo "Product added to cart successfully.";
            // Optionally redirect to another page or refresh the cart
            header("Location: view_cart.php");
            exit;
        } else {
            echo "Error adding product to cart: " . $conn->error;
        }
    } else {
        echo "Product not found.";
    }

    // Close the prepared statements
    $stmt->close();
    $insert_stmt->close();
}

// Close the database connection
$conn->close();
?>
