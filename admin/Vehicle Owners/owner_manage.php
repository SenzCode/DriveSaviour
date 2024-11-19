<?php
session_start();
include_once('../../connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name    = mysqli_real_escape_string($conn, $_POST['name']);
    $email   = $_POST['email'];
    $phone   = $_POST['phone'];
    $city    = $_POST['city'];

    if ($_POST['action'] == 'edit') {
        $sql = "UPDATE vehicle_owner SET name=?, phone=?, city=? WHERE email=?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param('ssss', $name, $phone, $city, $email);

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
    header("Location: veiw_owners.php");
    exit;
}
?>
