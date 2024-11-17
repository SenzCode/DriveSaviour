<?php
require '../../connection.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['v_id'])) {
    $v_id = intval($_GET['v_id']);

    $sql = "SELECT model, year, fuel_type, engine_type, tire_size FROM vehicle WHERE v_id = $v_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode(['error' => 'Vehicle not found']);
    }
} else {
    echo json_encode(['error' => 'Invalid request']);
}

$conn->close();
?>
