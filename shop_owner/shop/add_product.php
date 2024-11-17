<?php
session_start();

if (!isset($_SESSION['email'])) {
    echo "User is not logged in.";
    exit;
}

require('../../connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $shop_id = filter_var($_POST['shop_id'], FILTER_SANITIZE_NUMBER_INT);  // Corrected to $_POST
    $product_name = filter_var($_POST['product_name'], FILTER_SANITIZE_STRING);
    $quantity_available = (int) $_POST['quantity_available'];
    $price = filter_var($_POST['price'], FILTER_SANITIZE_STRING);

    if (isset($_FILES['image'])) {
        $image = $_FILES['image']['name'];
        $image = filter_var($image, FILTER_SANITIZE_STRING);
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = '../../uploads/' . $image;

        // Check if product already exists in the same shop
        $select_products = mysqli_prepare($conn, "SELECT * FROM products WHERE product_name = ? AND shop_id = ?");
        mysqli_stmt_bind_param($select_products, "si", $product_name, $shop_id); // Bind both the product name (string) and shop ID (integer)
        mysqli_stmt_execute($select_products);
        mysqli_stmt_store_result($select_products);

        // Check if any rows were returned
        if (mysqli_stmt_num_rows($select_products) > 0) {
            $error = "Product name already exists!";
            header("Location: products.php?shop_id=$shop_id&message=error&error=" . urlencode($error));
            exit;
        } else {
            if ($image_size > 2000000) { // 2MB limit
                $error = "Image size is too large!";
                header("Location: products.php?message=error&error=" . urlencode($error));
                exit;
            } else {
                if (!is_writable('../../uploads/')) {
                    $error = "Upload directory is not writable.";
                    header("Location: products.php?message=error&error=" . urlencode($error));
                    exit;
                }

                if (move_uploaded_file($image_tmp_name, $image_folder)) {
                    $image_url = '../../uploads/' . $image;
                    $insert_product = mysqli_prepare($conn, "INSERT INTO products (shop_id, product_name, image_url, quantity_available, price) VALUES (?, ?, ?, ?, ?)");
                    mysqli_stmt_bind_param($insert_product, "issdi", $shop_id, $product_name, $image_url, $quantity_available, $price);

                    if (mysqli_stmt_execute($insert_product)) {
                        header("Location: products.php?shop_id=$shop_id&message=insert");
                        exit;
                    } else {
                        $error = "Failed to insert product.";
                        header("Location: products.php?message=error&error=" . urlencode($error));
                        exit;
                    }
                } else {
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
    header("Location: products.php");
    exit;
}
