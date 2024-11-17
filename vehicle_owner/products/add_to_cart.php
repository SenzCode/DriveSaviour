<?php
session_start();
require '../../connection.php';

if (!isset($_SESSION['email'])) {
    echo "User is not logged in. Please log in to add items to the cart.";
    exit;
}

$email = $_SESSION['email'];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['id'];
    $shop_id = $_POST['shop_id'];
    $quantity = $_POST['quantity'];

    $query = "SELECT price, quantity_available FROM products WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        $price = $product['price'];
        $available_quantity = $product['quantity_available'];

        if ($quantity > $available_quantity) {
            echo "Insufficient quantity available.";
            exit;
        }

        // Check if the product is already in the cart for the user
        $check_cart_query = "SELECT quantity FROM cart WHERE product_id = ? AND email = ?";
        $check_cart_stmt = $conn->prepare($check_cart_query);
        $check_cart_stmt->bind_param("is", $product_id, $email);
        $check_cart_stmt->execute();
        $cart_result = $check_cart_stmt->get_result();

        if ($cart_result->num_rows > 0) {
            // Product exists in the cart, update quantity and total price
            $cart_item = $cart_result->fetch_assoc();
            $new_quantity = $cart_item['quantity'] + $quantity;
            $total_price = $price * $new_quantity;

            $update_cart_query = "UPDATE cart SET quantity = ?, total_price = ? WHERE product_id = ? AND email = ?";
            $update_cart_stmt = $conn->prepare($update_cart_query);
            $update_cart_stmt->bind_param("idis", $new_quantity, $total_price, $product_id, $email);

            if ($update_cart_stmt->execute()) {
                echo "Cart updated successfully.";
                header("Location: product.php?message=update");
                exit;
            } else {
                echo "Error updating cart: " . $conn->error;
            }

            $update_cart_stmt->close();
        } else {
            // Product not in cart, insert new record
            $total_price = $price * $quantity;
            $insert_query = "INSERT INTO cart (product_id, email, quantity, price, total_price) VALUES (?, ?, ?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_query);
            $insert_stmt->bind_param("isidd", $product_id, $email, $quantity, $price, $total_price);

            if ($insert_stmt->execute()) {
                header("Location: product.php?message=insert");
                exit;
            } else {
                echo "Error adding product to cart: " . $conn->error;
            }

            $insert_stmt->close();
        }

        $check_cart_stmt->close();
    } else {
        echo "Product not found.";
    }

    $stmt->close();
}

$conn->close();
?>
