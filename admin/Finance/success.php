<?php
require '../../connection.php'; // Include the database connection file

// Initialize variables
$message = "";
$imageSrc = "../../vehicle_owner/shop/img/success.png"; // Default to success image

try {
    // Prepare the SQL queries to update payment_status to 'paid' and set paid_date to the current date for all unpaid orders and mech_orders
    $stmt1 = $conn->prepare("UPDATE orders SET payment_status = 'paid', paid_date = NOW() WHERE payment_status != 'paid'");
    $stmt2 = $conn->prepare("UPDATE mech_orders SET payment_status = 'paid', paid_date = NOW() WHERE payment_status != 'paid'");

    // Execute both queries
    $success1 = $stmt1->execute();
    $success2 = $stmt2->execute();

    if ($success1 && $success2) {
        $message = "<h3>Payment Successful! All orders have been marked as paid with the payment date recorded in both tables.</h3>";
    } else {
        $message = "<h3>There was an error updating the payment status.</h3>";
        $imageSrc = "../../vehicle_owner/shop/img/error.png"; 
    }

    // Close the prepared statements
    $stmt1->close();
    $stmt2->close();

} catch (Exception $e) {
    $message = "<h3>Error: " . $e->getMessage() . "</h3>";
    $imageSrc = "../../vehicle_owner/shop/img/errors.png"; 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Status</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }

        .container {
            background-color: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 500px;
        }

        h1 {
            font-size: 2.5em;
            color: #4CAF50;
            margin-bottom: 20px;
        }

        p {
            font-size: 1.2em;
            color: #555;
            margin-bottom: 30px;
        }

        .status-image {
            width: 100px;
            height: 100px;
            margin-bottom: 20px;
        }

        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="<?php echo $imageSrc; ?>" alt="Payment Status" class="status-image">
        <h1>Payment Status</h1>
        <?php echo $message; ?>
        <button onclick="window.location.href='finance.php'">Back to the finance page</button>
    </div>
</body>
</html>
