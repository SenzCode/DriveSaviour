<?php
session_start();
require '../../connection.php';
require 'vendor/autoload.php';

// Stripe API configuration
\Stripe\Stripe::setApiKey('sk_test_51PfklnDFvPyG4fvuUh6ZfPSa5LBwdmWSlgABfkzEjUZeJH5YHDpHoHzWRKDrjYt325wJZSXY4ip4TY4tYfZ9cYnZ00AkL5f2Zd');

if (!isset($_SESSION['email'])) {
    echo "User is not logged in.";
    exit;
}

$userEmail = $_SESSION['email'];

if (!isset($_GET['session_id'])) {
    echo "Invalid payment session.";
    exit;
}

$session_id = $_GET['session_id'];

try {
    $session = \Stripe\Checkout\Session::retrieve($session_id);
    if ($session->payment_status !== 'paid') {
        echo "Payment not confirmed.";
        exit;
    }
} catch (Exception $e) {
    echo "Error retrieving payment session: " . $e->getMessage();
    exit;
}

$query = "SELECT c.*, p.id AS product_id, p.price 
          FROM mech_cart c 
          JOIN products p ON c.product_id = p.id 
          WHERE c.email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $userEmail);
$stmt->execute();
$cartItemsResult = $stmt->get_result();

$subtotal = 0;
$cartItems = [];
while ($item = $cartItemsResult->fetch_assoc()) {
    $cartItems[] = $item;
    $subtotal += $item['price'] * $item['quantity'];
}

$discountRate = 0;
$loyaltyCheckQuery = $conn->prepare("SELECT * FROM mech_loyalty_card WHERE email = ?");
$loyaltyCheckQuery->bind_param("s", $userEmail);
$loyaltyCheckQuery->execute();
$loyaltyResult = $loyaltyCheckQuery->get_result();
if ($loyaltyResult->num_rows > 0) {
    $discountRate = 0.05; // 5% discount
}

$discountAmount = $subtotal * $discountRate;
$totalAmountToPay = $subtotal - $discountAmount;

$referenceNumber = uniqid('MORD-', true);

$orderInsertQuery = $conn->prepare("INSERT INTO mech_orders (reference_number, product_id, quantity, purchase_date, item_total, total_price, discount, seller_income, commission, email, status) VALUES (?, ?, ?, NOW(), ?, ?, ?, ?, ?, ?, 'Pending')");

foreach ($cartItems as $item) {
    $productName = $item['product_id'];
    $quantity = $item['quantity'];
    $itemTotal = $item['price'] * $quantity;

    $commission = $itemTotal * 0.03; // 3% commission
    $sellerIncome = $itemTotal - $commission;

    $totalPrice = $totalAmountToPay;

    $orderInsertQuery->bind_param("ssiidddss", $referenceNumber, $productName, $quantity, $itemTotal, $totalPrice, $discountAmount, $sellerIncome, $commission, $userEmail);
    $orderInsertQuery->execute();

    $updateProductQuantityQuery = $conn->prepare("UPDATE products SET quantity_available = quantity_available - ? WHERE id = ?");
    $updateProductQuantityQuery->bind_param("ii", $quantity, $productName);
    $updateProductQuantityQuery->execute();
}

$deleteCartQuery = $conn->prepare("DELETE FROM mech_cart WHERE email = ?");
$deleteCartQuery->bind_param("s", $userEmail);
$deleteCartQuery->execute();

$orderInsertQuery->close();
$deleteCartQuery->close();
$loyaltyCheckQuery->close();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>
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

        .success-image {
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
        <img src="../../vehicle_owner/shop/img/success.png" alt="Success" class="success-image">
        <h1>Payment Successful</h1>
        <p>Thank you for your purchase! Your order has been successfully placed.</p>
        <button onclick="window.location.href='product.php'">Back to the product page</button>
    </div>
</body>
</html>
