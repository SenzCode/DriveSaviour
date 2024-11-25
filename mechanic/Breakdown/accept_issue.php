<?php
session_start();
require '../../connection.php';
require '../../vendor/autoload.php'; // PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $issue_id = intval($_POST['issue_id']);
    $userID = intval($_SESSION['userID']); // Mechanic's user ID

    // Enable MySQLi error reporting
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    // Validate input
    if (!$issue_id || !$userID) {
        die("Invalid issue ID or user ID.");
    }

    try {
        // Update the vehicle issue with the mechanic ID
        $sql = "UPDATE vehicleissues SET mech_id = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("ii", $userID, $issue_id);

        if (!$stmt->execute()) {
            die("Execution failed: " . $stmt->error);
        }

        // Fetch the owner's email and vehicle issue details
        $ownerEmailQuery = "SELECT vo.email AS owner_email, vi.vehicle_issue 
                            FROM vehicleissues vi 
                            JOIN vehicle v ON vi.v_id = v.v_id 
                            JOIN vehicle_owner vo ON v.email = vo.email 
                            WHERE vi.id = ?";
        $emailStmt = $conn->prepare($ownerEmailQuery);
        if (!$emailStmt) {
            die("Prepare failed for owner's email: " . $conn->error);
        }
        $emailStmt->bind_param("i", $issue_id);
        $emailStmt->execute();
        $emailResult = $emailStmt->get_result();

        if ($emailResult->num_rows === 0) {
            die("Owner email not found.");
        }

        $emailData = $emailResult->fetch_assoc();
        $ownerEmail = $emailData['owner_email'];
        $vehicleIssue = $emailData['vehicle_issue'];

        // Fetch the mechanic's information
        $mechanicInfoQuery = "SELECT name, phone FROM mechanic WHERE userID = ?";
        $mechStmt = $conn->prepare($mechanicInfoQuery);
        if (!$mechStmt) {
            die("Prepare failed for mechanic info: " . $conn->error);
        }
        $mechStmt->bind_param("i", $userID);
        $mechStmt->execute();
        $mechResult = $mechStmt->get_result();

        if ($mechResult->num_rows === 0) {
            die("Mechanic info not found.");
        }

        $mechanic = $mechResult->fetch_assoc();
        $mechanicName = $mechanic['name'];
        $mechanicContact = $mechanic['phone'];

        // Send email notification to the owner
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'ramithacampus@gmail.com'; // Your email
            $mail->Password   = 'ijjn tjwp erwe ktns';     // App password for Gmail
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Recipients
            $mail->setFrom('ramithacampus@gmail.com', 'DriveSaviour');
            $mail->addAddress($ownerEmail); // Owner's email

            // Email content
            $mail->isHTML(true);
            $mail->Subject = 'Mechanic Assigned to Your Vehicle Issue';
            $mail->Body    = "
                <h1>Mechanic Assigned</h1>
                <p>Dear Customer,</p>
                <p>We are pleased to inform you that a mechanic has been assigned to your vehicle issue.</p>
                <p><strong>Issue:</strong> $vehicleIssue</p>
                <p><strong>Mechanic Name:</strong> $mechanicName</p>
                <p><strong>Contact Number:</strong> $mechanicContact</p>
                <p>The mechanic will contact you shortly. Thank you for using DriveSaviour.</p>
                <p>Best regards,<br>DriveSaviour Team</p>
            ";
            $mail->AltBody = "A mechanic has been assigned to your vehicle issue: $vehicleIssue.\n
                              Mechanic Name: $mechanicName\n
                              Contact Number: $mechanicContact\n
                              The mechanic will contact you shortly.";

            $mail->send();
        } catch (Exception $e) {
            echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

        // Redirect with success message
        header("Location: view_issue.php?id=$issue_id&message=insert");
        exit();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    } finally {
        // Clean up resources
        if (isset($stmt)) $stmt->close();
        if (isset($emailStmt)) $emailStmt->close();
        if (isset($mechStmt)) $mechStmt->close();
        $conn->close();
    }
}
?>
