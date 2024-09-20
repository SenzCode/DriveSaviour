<?php
session_start();
include_once('../../connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize input data
    $userID = intval($_POST['userID']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $dob = $_POST['dob'];

    if ($_POST['action'] == 'edit') {
        // Prepare the update query for mechanic table
        $sql = "UPDATE mechanic SET name=?, email=?, phone=?, address=?, dob=? WHERE userID=?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('sssssi', $name, $email, $phone, $address, $dob, $userID);
            if ($stmt->execute()) {
                header("Location: view_mechanic.php?message=edit_success");
                exit;
            } else {
                $error = $stmt->error;
                header("Location: view_mechanic.php?message=error&error=" . urlencode($error));
                exit;
            }
            $stmt->close();
        } else {
            $error = $conn->error;
            header("Location: view_mechanic.php?message=error&error=" . urlencode($error));
            exit;
        }
    } elseif ($_POST['action'] == 'delete') {
        // Delete query for mechanic table
        $sql = "DELETE FROM mechanic WHERE userID=?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('i', $userID);
            if ($stmt->execute()) {
                header("Location: view_mechanic.php?message=delete_success");
                exit;
            } else {
                $error = $stmt->error;
                header("Location: view_mechanic.php?message=error&error=" . urlencode($error));
                exit;
            }
            $stmt->close();
        } else {
            $error = $conn->error;
            header("Location: view_mechanic.php?message=error&error=" . urlencode($error));
            exit;
        }
    }
} else {
    // Redirect back if the request method is not POST
    header("Location: view_mechanic.php");
    exit;
}
?>
