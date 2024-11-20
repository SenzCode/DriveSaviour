<?php
session_start();
include_once('../../connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $batch_num = mysqli_real_escape_string($conn, $_POST['batch_num']);
    $action = $_POST['action'];

    if ($action == 'edit') {
        $prod_id =  $_POST['prod_id'];
        $suplier_name = mysqli_real_escape_string($conn, $_POST['suplier_name']);
        $purchase_price = (float) $_POST['purchase_price'];
        $avail_qty = (int) $_POST['avail_qty'];
        $date = $_POST['date']; 

        $sql = "UPDATE batch SET product_name = ?, suplier_name = ?, purchase_price = ?, avail_qty = ?, date = ? WHERE batch_num = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdiss", $prod_id, $suplier_name, $purchase_price, $avail_qty, $date, $batch_num);

        if ($stmt->execute()) {
            header("Location: stock.php?message=edit");
            exit;
        } else {
            $error = $stmt->error;
            header("Location: stock.php?message=error&error=" . urlencode($error));
            exit;
        }
    } elseif ($action == 'delete') {
        $sql = "DELETE FROM batch WHERE batch_num = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $batch_num);

        if ($stmt->execute()) {
            header("Location: stock.php?message=delete");
            exit;
        } else {
            $error = $stmt->error;
            header("Location: stock.php?message=error&error=" . urlencode($error));
            exit;
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['batch_id'])) {
    $batch_num = mysqli_real_escape_string($conn, $_GET['batch_id']);
    $stmt = $conn->prepare("SELECT * FROM batch WHERE batch_num = ?");
    $stmt->bind_param("s", $batch_num);
    $stmt->execute();
    $batch_data = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$batch_data) {
        echo "Batch not found.";
        exit;
    }

    // Render form here (example HTML form above)
}
?>
