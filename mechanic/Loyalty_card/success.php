<?php
session_start();
require '../../connection.php';

if (isset($_GET['email'])) {
    $email = $_GET['email'];
    
    $card_no = str_pad(mt_rand(0, 9999999999999999), 16, '0', STR_PAD_LEFT);
    $expire_date = date('Y-m-d', strtotime('+1 year'));
    
    // Insert into loyalty_card table
    $stmt = $conn->prepare("INSERT INTO mech_loyalty_card (card_no, expire_date, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $card_no, $expire_date, $email);

    if ($stmt->execute()) {
        header('Location: ../Loyalty_card/loyalty_card.php');
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
