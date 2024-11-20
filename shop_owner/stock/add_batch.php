<?php
session_start();
include_once('../../connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $prod_id = $_POST['prod_id'];
    $batch_num = mysqli_real_escape_string($conn, $_POST['batch_num']);
    $suplier_name = mysqli_real_escape_string($conn, $_POST['suplier_name']);
    $purchase_price = (float) $_POST['purchase_price'];
    $avail_qty = (int) $_POST['avail_qty'];
    $date = $_POST['date'];

    // Insert query
    $sql = "INSERT INTO batch (product_name, batch_num, suplier_name, purchase_price, avail_qty, date) VALUES ('$prod_id', '$batch_num', '$suplier_name', '$purchase_price', '$avail_qty', '$date')";
    if (mysqli_query($conn, $sql)) {
        header("Location: stock.php?message=insert");
        exit;
    } else {
        $error = mysqli_error($conn);
        header("Location: stock.php?message=error&error=" . urlencode($error));
        exit;
    }
} else {
    header("Location: stock.php");
    exit;
}
?>
