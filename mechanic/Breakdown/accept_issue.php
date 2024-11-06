<?php
session_start(); // Ensure the session is started
require '../../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $issue_id = intval($_POST['issue_id']);
    $userID = intval($_SESSION['userID']); // Mechanic's user ID from session

    // Update the `vehicleissues` table and set `mech_id` to the user ID
    $sql = "UPDATE vehicleissues SET mech_id = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $userID, $issue_id);

    if ($stmt->execute()) {
        header("Location: view_issue.php?id=$issue_id&message=insert");
    } else {
        echo 'Error updating issue assignment.';
    }

    // Close connection
    $stmt->close();
    $conn->close();
}
?>
