<?php
session_start();

if (!isset($_SESSION['email'])) {
    echo "User is not logged in.";
    exit;
}

require('../../connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $shop_id = filter_var($_POST['shop_id'], FILTER_SANITIZE_NUMBER_INT);
    $batch_num = filter_var($_POST['batch_num'], FILTER_SANITIZE_STRING);
    $product_name = filter_var($_POST['product_name'], FILTER_SANITIZE_STRING);
    $category_id = filter_var($_POST['category_id'], FILTER_SANITIZE_NUMBER_INT);
    $quantity_available = (int)$_POST['quantity_available'];
    $price = filter_var($_POST['price'], FILTER_SANITIZE_STRING);

    if (isset($_FILES['image'])) {
        $image = $_FILES['image']['name'];
        $image = filter_var($image, FILTER_SANITIZE_STRING);
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $upload_dir = '../../uploads/';
        $image_folder = $upload_dir . $image;

        if ($image_size > 2000000) { // 2MB limit
            $error = "Image size is too large!";
            header("Location: products.php?message=error&error=" . urlencode($error));
            exit;
        } else {
            if (!is_writable($upload_dir)) {
                $error = "Upload directory is not writable.";
                header("Location: products.php?message=error&error=" . urlencode($error));
                exit;
            }

            if (move_uploaded_file($image_tmp_name, $image_folder)) {
                // Save web-accessible path
                $image_url = '../../uploads/' . $image;

                $stmt = $conn->prepare("INSERT INTO products (shop_id, cat_id, batch_num, product_name, image_url, quantity_available, price) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("iiissis", $shop_id, $category_id, $batch_num, $product_name, $image_url, $quantity_available, $price);

                if ($stmt->execute()) {
                    header("Location: products.php?shop_id=$shop_id&message=insert");
                } else {
                    $error = $stmt->error;
                    header("Location: products.php?shop_id=$shop_id&message=error&error=" . urlencode($error));
                }
                $stmt->close();
            } else {
                $error = "Failed to upload image. Error: " . print_r(error_get_last(), true);
                header("Location: products.php?message=error&error=" . urlencode($error));
                exit;
            }
        }
    } else {
        $error = "Please upload a valid image.";
        header("Location: products.php?message=error&error=" . urlencode($error));
        exit;
    }
}
?>
