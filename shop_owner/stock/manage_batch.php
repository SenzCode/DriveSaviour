<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../login.php");
    exit;
}

include_once('../../connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $suplier_id = $_POST['suplier_id'];
    $suplier_name = $_POST['suplier_name'];
    $batch_num = $_POST['batch_num'];
    $product_name = $_POST['prod_id'];
    $cat_id = $_POST['cat_id'];
    $purchase_price = $_POST['purchase_price'];
    $avail_qty = $_POST['avail_qty'];
    $date = $_POST['date'];

    if ($action === 'edit') {
        // Update batch details
        $stmt = $conn->prepare("
            UPDATE batch
            SET 
                suplier_name = ?, 
                batch_num = ?, 
                product_name = ?, 
                cat_id = ?, 
                purchase_price = ?, 
                avail_qty = ?, 
                date = ?
            WHERE 
                suplier_id = ?
        ");

        if ($stmt) {
            $stmt->bind_param(
                "sssidssi", 
                $suplier_name, 
                $batch_num, 
                $product_name, 
                $cat_id, 
                $purchase_price, 
                $avail_qty, 
                $date, 
                $suplier_id
            );

            if ($stmt->execute()) {
                header("Location: stock.php?message=edit");
            } else {
                header("Location: stock.php?message=error&error=" . urlencode($stmt->error));
            }

            $stmt->close();
        } else {
            header("Location: stock.php?message=error&error=" . urlencode($conn->error));
        }
    } elseif ($action === 'delete') {
        // Delete batch
        $stmt = $conn->prepare("DELETE FROM batch WHERE suplier_id = ?");
        if ($stmt) {
            $stmt->bind_param("i", $suplier_id);

            if ($stmt->execute()) {
                header("Location: stock.php?message=delete");
            } else {
                header("Location: stock.php?message=error&error=" . urlencode($stmt->error));
            }

            $stmt->close();
        } else {
            header("Location: stock.php?message=error&error=" . urlencode($conn->error));
        }
    }
} else {
    header("Location: stock.php?message=error&error=Invalid Request");
}

$conn->close();
?>
