<?php
session_start();
require '../../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $issue_id = intval($_POST['issue_id']);
    $userID = intval($_SESSION['userID']);

    // Update the vehicle issues with the mechanic id
    $sql = "UPDATE vehicleissues SET mech_id = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $userID, $issue_id);

    if ($stmt->execute()) {
        header("Location: view_issue.php?id=$issue_id&message=insert");
    } else {
        echo 'Error updating issue assignment.';
    }

    $stmt->close();
    $conn->close();
}
?>
