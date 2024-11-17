<?php
require '../../connection.php';
session_start();

if (!isset($_SESSION['email'])) {
    echo "User is not logged in.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] === 'insert') {
    // Retrieve and sanitize input data
    $cat_name = htmlspecialchars(trim($_POST['cat_name']));
    $description = htmlspecialchars(trim($_POST['description']));
    $created_date = $_POST['cdate'];

    $query = "INSERT INTO category (category_name, description, created_date) VALUES ('$cat_name', '$description', '$created_date')";

    if (mysqli_query($conn, $query)) {
        header("Location: category.php?message=insert");
        exit();
    } else {
        $error = mysqli_error($conn);
        header("Location: category.php?message=error&error=" . urlencode($error));
        exit();
    }
} else {
    header("Location: category.php?message=error&error=" . urlencode("Invalid request"));
    exit();
}
?>
