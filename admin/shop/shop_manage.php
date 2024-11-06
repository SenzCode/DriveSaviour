<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include_once('../../connection.php');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        // Sanitize and assign POST data
        $id          = intval($_POST['id']);
        $ownerEmail  = mysqli_real_escape_string($conn, $_POST['ownerEmail']);
        $shop_name   = mysqli_real_escape_string($conn, $_POST['shop_name']);
        $email       = mysqli_real_escape_string($conn, $_POST['email']);
        $number      = mysqli_real_escape_string($conn, $_POST['number']);
        $address     = mysqli_real_escape_string($conn, $_POST['address']);
        $branch      = mysqli_real_escape_string($conn, $_POST['branch']);

        // Check action type for handling edit or delete
        if ($_POST['action'] === 'edit') {

            // Edit shop details in the database
            $sql = "UPDATE shops SET ownerEmail=?, shop_name=?, email=?, number=?, address=?, branch=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssssssi', $ownerEmail, $shop_name, $email, $number, $address, $branch, $id);
            $stmt->execute();
            $stmt->close();

            // Redirect with success message
            header("Location: view_shop.php?message=edit_success");
            exit;

        } elseif ($_POST['action'] === 'delete') {

            // Start transaction for delete operation
            $conn->begin_transaction();

            try {
                // Delete the shop record
                $sql = "DELETE FROM shops WHERE id=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('i', $id);
                $stmt->execute();
                $stmt->close();

                // Commit the transaction
                $conn->commit();

                // Redirect with success message
                header("Location: view_shop.php?message=delete_success");
                exit;

            } catch (Exception $e) {
                // Rollback the transaction in case of error
                $conn->rollback();

                // Log error and show failure message
                error_log("Deletion error: " . $e->getMessage());
                header("Location: view_shop.php?message=error&error=Unable to delete shop. Please try again.");
                exit;
            }
        }
    } else {
        // Redirect if not POST request
        header("Location: view_shop.php");
        exit;
    }
} catch (mysqli_sql_exception $e) {
    // Handle database related errors
    error_log("Database error: " . $e->getMessage());

    // Show error message
    header("Location: view_shop.php?message=error&error=Unable to change the owner email.");
    exit;
}

?>
