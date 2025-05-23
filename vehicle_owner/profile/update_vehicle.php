<?php
session_start();
require('../../connection.php');
$v_id = $_POST['v_id'];
$model = $_POST['model'];
$year = $_POST['year'];
$fuel_type = $_POST['fuel_type'];
$engine_type = $_POST['engine_type'];
$tire_size = $_POST['tire_size'];

// update the vehicle details
$sql = "UPDATE vehicle SET model = ?, year = ?, fuel_type = ?, engine_type = ?, tire_size = ? WHERE v_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssi", $model, $year, $fuel_type, $engine_type, $tire_size, $v_id);

if ($stmt->execute()) {
    header('Location: profile.php?message=update_success');
    exit();
} else {
    header('Location: profile.php?status=update_error');
    exit();
}

?>
