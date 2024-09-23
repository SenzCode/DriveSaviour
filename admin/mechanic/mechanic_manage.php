<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include_once('../../connection.php');


mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        $userID = intval($_POST['userID']);
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);
        $dob = $_POST['dob'];

        if ($_POST['action'] == 'edit') {
            
            $sql = "UPDATE mechanic SET name=?, email=?, phone=?, address=?, dob=? WHERE userID=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sssssi', $name, $email, $phone, $address, $dob, $userID);
            $stmt->execute();
            $stmt->close();
            
            header("Location: view_mechanic.php?message=edit_success");
            exit;
        } elseif ($_POST['action'] == 'delete') {
            
            
            $conn->begin_transaction();

            try {
                
                $deleteIssuesSql = "DELETE FROM vehicleissues WHERE mech_id = ?";
                $stmtIssues = $conn->prepare($deleteIssuesSql);
                $stmtIssues->bind_param('i', $userID);
                $stmtIssues->execute();
                $stmtIssues->close();

               
                $sql = "DELETE FROM mechanic WHERE userID=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('i', $userID);
                $stmt->execute();
                $stmt->close();

              
                $conn->commit();

                header("Location: view_mechanic.php?message=delete_success");
                exit;
            } catch (Exception $e) {
                
                $conn->rollback();
               
                error_log("Deletion error: " . $e->getMessage());
                
                header("Location: view_mechanic.php?message=error&error=Unable to delete mechanic. Please try again.");
                exit;
            }
        }
    } else {
        header("Location: view_mechanic.php");
        exit;
    }
} catch (mysqli_sql_exception $e) {
    
    error_log("Database error: " . $e->getMessage());
    
    header("Location: view_mechanic.php?message=error&error=Database error occurred.");
    exit;
}
?>
