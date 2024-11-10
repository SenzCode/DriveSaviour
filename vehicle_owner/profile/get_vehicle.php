<?php
require '../../connection.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if v_id is passed
if (isset($_GET['v_id'])) {
    $v_id = intval($_GET['v_id']);

    // Fetch vehicle details based on v_id
    $sql = "SELECT model, year, fuel_type, engine_type, tire_size FROM vehicle WHERE v_id = $v_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output data in JSON format
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode(['error' => 'Vehicle not found']);
    }
} else {
    echo json_encode(['error' => 'Invalid request']);
}

$conn->close();
?>
