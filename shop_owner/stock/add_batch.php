<?php
session_start();
if (!isset($_SESSION['email'])) {
    echo "User is not logged in.";
    exit;
}

include_once('../../connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'insert') {
    $email = $_SESSION['email'];
    $suplier_name = $_POST['suplier_name'];
    $batch_num = $_POST['batch_num'];
    $prod_id = $_POST['prod_id'];
    $cat_id = $_POST['cat_id'];
    $purchase_price = $_POST['purchase_price'];
    $avail_qty = $_POST['avail_qty'];
    $date = $_POST['date'];
    

    $stmt = $conn->prepare("INSERT INTO batch (suplier_name, email, batch_num, product_name, cat_id, purchase_price, avail_qty, date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        header("Location: stock.php?message=error&error=" . urlencode($conn->error));
        exit;
    }

    $stmt->bind_param("sssssdis", $suplier_name, $email, $batch_num, $prod_id, $cat_id, $purchase_price, $avail_qty, $date);

    if ($stmt->execute()) {
        header("Location: stock.php?message=insert");
    } else {
        header("Location: stock.php?message=error&error=" . urlencode($stmt->error));
    }

    $stmt->close();
}
