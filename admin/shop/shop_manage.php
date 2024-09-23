<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include_once('../../connection.php');


mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        
        $id = intval($_POST['id']);
        $ownerEmail = mysqli_real_escape_string($conn, $_POST['ownerEmail']);
        $shop_name = mysqli_real_escape_string($conn, $_POST['shop_name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $number = mysqli_real_escape_string($conn, $_POST['number']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);
        $branch = mysqli_real_escape_string($conn, $_POST['branch']);

        if ($_POST['action'] == 'edit') {
            
            
            $sql = "UPDATE shops SET ownerEmail=?, shop_name=?, email=?, number=?, address=?, branch=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssssssi', $ownerEmail, $shop_name, $email, $number, $address, $branch, $id);
            $stmt->execute();
            $stmt->close();
            
            header("Location: view_shop.php?message=edit_success");
            exit;
        } elseif ($_POST['action'] == 'delete') {
            
            
            $conn->begin_transaction();

            try {
                
                $sql = "DELETE FROM shops WHERE id=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('i', $id);
                $stmt->execute();
                $stmt->close();

                
                $conn->commit();

                header("Location: view_shop.php?message=delete_success");
                exit;
            } catch (Exception $e) {
                
                $conn->rollback();
                
                error_log("Deletion error: " . $e->getMessage());
                
                header("Location: view_shop.php?message=error&error=Unable to delete shop. Please try again.");
                exit;
            }
        }
    } else {
        
        header("Location: view_shop.php");
        exit;
    }
} catch (mysqli_sql_exception $e) {
   
    error_log("Database error: " . $e->getMessage());
    
    header("Location: view_shop.php?message=error&error=Unable to change the owner email.");
    exit;
}
?>
