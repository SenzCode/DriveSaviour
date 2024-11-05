<?php
session_start();
require '../../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $issue_id = $_POST['issue_id'];
    $status = $_POST['status'];

    $sql = "UPDATE vehicleissues SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $issue_id);

    if ($stmt->execute()) {
        header("Location: breakdown.php?message=updated");
        exit;
    } else {
        echo "Error updating status.";
    }

    $stmt->close();
    $conn->close();
}
?>
