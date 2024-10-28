<?php
session_start();
require '../../connection.php';

if (isset($_GET['email'])) {
    $email = $_GET['email'];
    
    // Generate a card number and expiration date
    $card_no = uniqid('LC-'); // Unique Card Number
    $expire_date = date('Y-m-d', strtotime('+1 year')); // Set expiration to 1 year from now
    
    // Insert into loyalty_card table
    $stmt = $conn->prepare("INSERT INTO loyalty_card (card_no, expire_date, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $card_no, $expire_date, $email);

    if ($stmt->execute()) {
        header('Location: loyalty_card.php');
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
