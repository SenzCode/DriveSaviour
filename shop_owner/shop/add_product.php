<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    echo "User is not logged in.";
    exit;
}

require('../../connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Get form data and sanitize it
    $product_name = filter_var($_POST['product_name'], FILTER_SANITIZE_STRING);
    $quantity_available = (int) $_POST['quantity_available'];
    $price = filter_var($_POST['price'], FILTER_SANITIZE_STRING);

    // Image upload handling
    if (isset($_FILES['image'])) {
        $image = $_FILES['image']['name'];
        $image = filter_var($image, FILTER_SANITIZE_STRING);
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = '../uploads/' . $image;

        // Check if product already exists
        $select_products = mysqli_prepare($conn, "SELECT * FROM products WHERE product_name = ?");
        mysqli_stmt_bind_param($select_products, "s", $product_name);
        mysqli_stmt_execute($select_products);
        mysqli_stmt_store_result($select_products);

        if (mysqli_stmt_num_rows($select_products) > 0) {
            $error = "Product name already exists!";
            header("Location: products.php?message=error&error=" . urlencode($error));
            exit;
        } else {
            // Check if image size is too large
            if ($image_size > 2000000) { // 2MB limit
                $error = "Image size is too large!";
                header("Location: products.php?message=error&error=" . urlencode($error));
                exit;
            } else {
                // Debugging: Check if the uploads directory is writable
                if (!is_writable('../uploads/')) {
                    $error = "Upload directory is not writable.";
                    header("Location: products.php?message=error&error=" . urlencode($error));
                    exit;
                }

                // Move the uploaded image to the target folder
                if (move_uploaded_file($image_tmp_name, $image_folder)) {
                    $image_url = '../uploads/' . $image;

                    // Insert product into the database
                    $insert_product = mysqli_prepare($conn, "INSERT INTO products (product_name, image_url, quantity_available, price) VALUES (?, ?, ?, ?)");
                    mysqli_stmt_bind_param($insert_product, "ssdi", $product_name, $image_url, $quantity_available, $price);

                    if (mysqli_stmt_execute($insert_product)) {
                        header("Location: products.php?message=insert");
                        exit;
                    } else {
                        $error = "Failed to insert product.";
                        header("Location: products.php?message=error&error=" . urlencode($error));
                        exit;
                    }
                } else {
                    // Debugging: Show error if move_uploaded_file fails
                    $error = "Failed to upload image. Error: " . print_r(error_get_last(), true);
                    header("Location: products.php?message=error&error=" . urlencode($error));
                    exit;
                }
            }
        }
    } else {
        $error = "Please upload a valid image.";
        header("Location: products.php?message=error&error=" . urlencode($error));
        exit;
    }
} else {
    // Redirect back if the request method is not POST
    header("Location: products.php");
    exit;
}
