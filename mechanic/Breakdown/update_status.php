<?php
session_start();
require '../../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $issue_id = $_POST['issue_id'];
    $status = $_POST['status'];

    $conn->begin_transaction();

    try {
        $sql = "UPDATE vehicleissues SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $status, $issue_id);
        $stmt->execute();

        if ($status === 'Done') {
            $copySql = "INSERT INTO vehicleissuesdone (id, email, v_id, mech_id, location, vehicle_issue, job_done_at, status, city)
                        SELECT id, email, v_id, mech_id, location, vehicle_issue, NOW(), status, city 
                        FROM vehicleissues WHERE id = ?";
            $copyStmt = $conn->prepare($copySql);
            $copyStmt->bind_param("i", $issue_id);
            $copyStmt->execute();

            $deleteSql = "DELETE FROM vehicleissues WHERE id = ?";
            $deleteStmt = $conn->prepare($deleteSql);
            $deleteStmt->bind_param("i", $issue_id);
            $deleteStmt->execute();

            $deleteStmt->close();
            $copyStmt->close();
        }

        $stmt->close();
        $conn->commit();
        header("Location: breakdown.php?message=updated");
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error updating status: " . $e->getMessage();
    }

    $conn->close();
}
?>
