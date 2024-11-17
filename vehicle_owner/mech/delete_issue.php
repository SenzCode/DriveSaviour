<?php
session_start();
require '../../connection.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
if (isset($data['issueID'])) {
    $issueID = intval($data['issueID']);
    $query = "DELETE FROM vehicleissues WHERE id = ?";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $issueID);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Issue deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error deleting issue']);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Error preparing statement']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No issue ID provided']);
}

$conn->close();
?>
