<?php
require '../../connection.php'; // Include the database connection file

try {
    // Prepare the SQL query to update payment_status to 'paid' for all orders
    $stmt = $conn->prepare("UPDATE orders SET payment_status = 'paid' WHERE payment_status != 'paid'");
    
    // Execute the query
    if ($stmt->execute()) {
        echo "<h3>Payment Successful! All orders have been marked as paid.</h3>";
    } else {
        echo "<h3>There was an error updating the payment status.</h3>";
    }
    $stmt->close();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
