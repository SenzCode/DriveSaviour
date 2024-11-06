<?php
session_start();
require('../../connection.php');

// Retrieve the vehicle ID from the form
$v_id = $_POST['v_id'];

// Delete the vehicle from the database
$sql = "DELETE FROM vehicle WHERE v_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $v_id);

if ($stmt->execute()) {
    header('Location: profile.php?status=delete_success');
    exit();
} else {
    header('Location: profile.php?status=delete_error');
    exit();
}

// Close statement and connection
$stmt->close();
$conn->close();
?>
