<?php
require '../../connection.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "DELETE FROM shops WHERE id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            session_start();
            $_SESSION['message'] = "Shop deleted successfully!";
        } else {
            session_start();
            $_SESSION['message'] = "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        session_start();
        $_SESSION['message'] = "Error: " . $conn->error;
    }
}

$conn->close();
header("Location: shop.php"); 
exit();
?>
