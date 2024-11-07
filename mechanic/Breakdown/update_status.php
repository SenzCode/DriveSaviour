<?php
session_start();
require '../../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $issue_id = $_POST['issue_id'];
    $status = $_POST['status'];

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Update the status in the vehicleissues table
        $sql = "UPDATE vehicleissues SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $status, $issue_id);
        $stmt->execute();

        if ($status === 'Done') {
            // Copy the record to vehicleissuesdone table
            $copySql = "INSERT INTO vehicleissuesdone (id, email, v_id, mech_id, location, vehicle_issue, job_done_at, status, city)
                        SELECT id, email, v_id, mech_id, location, vehicle_issue, NOW(), status, city 
                        FROM vehicleissues WHERE id = ?";
            $copyStmt = $conn->prepare($copySql);
            $copyStmt->bind_param("i", $issue_id);
            $copyStmt->execute();

            // Delete the record from vehicleissues table
            $deleteSql = "DELETE FROM vehicleissues WHERE id = ?";
            $deleteStmt = $conn->prepare($deleteSql);
            $deleteStmt->bind_param("i", $issue_id);
            $deleteStmt->execute();

            $deleteStmt->close();
            $copyStmt->close();
        }

        $stmt->close();

        // Commit the transaction
        $conn->commit();
        header("Location: breakdown.php?message=updated");
        exit;
    } catch (Exception $e) {
        // Rollback the transaction if any query fails
        $conn->rollback();
        echo "Error updating status: " . $e->getMessage();
    }

    $conn->close();
}
?>
