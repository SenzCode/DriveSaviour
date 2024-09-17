<?php
session_start();
include_once('../../connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $city = $_POST['city'];

    if ($_POST['action'] == 'edit') {
        // Prepare the update query for vehicle_owner table
        $sql = "UPDATE vehicle_owner SET name=?, phone=?, address=?, city=? WHERE email=?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('sssss', $name, $phone, $address, $city, $email);
            if ($stmt->execute()) {
                header("Location: veiw_owners.php?message=edit_success");
                exit;
            } else {
                $error = $stmt->error;
                header("Location: veiw_owners.php?message=error&error=" . urlencode($error));
                exit;
            }
            $stmt->close();
        } else {
            $error = $conn->error;
            header("Location: veiw_owners.php?message=error&error=" . urlencode($error));
            exit;
        }
    } elseif ($_POST['action'] == 'delete') {
        // Delete query for vehicle_owner table
        $sql = "DELETE FROM vehicle_owner WHERE email=?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('s', $email);
            if ($stmt->execute()) {
                header("Location: veiw_owners.php?message=delete_success");
                exit;
            } else {
                $error = $stmt->error;
                header("Location: veiw_owners.php?message=error&error=" . urlencode($error));
                exit;
            }
            $stmt->close();
        } else {
            $error = $conn->error;
            header("Location: veiw_owners.php?message=error&error=" . urlencode($error));
            exit;
        }
    }
} else {
    // Redirect back if the request method is not POST
    header("Location: veiw_owners.php");
    exit;
}
