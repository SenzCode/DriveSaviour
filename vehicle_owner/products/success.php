<?php
session_start();
require '../../connection.php';
require 'vendor/autoload.php'; // Stripe PHP library

// Stripe API configuration
\Stripe\Stripe::setApiKey('sk_test_51PfklnDFvPyG4fvuUh6ZfPSa5LBwdmWSlgABfkzEjUZeJH5YHDpHoHzWRKDrjYt325wJZSXY4ip4TY4tYfZ9cYnZ00AkL5f2Zd');

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    echo "User is not logged in.";
    exit;
}

// Get the logged-in user's email
$userEmail = $_SESSION['email'];

// Verify the Stripe session ID passed from the payment page
if (!isset($_GET['session_id'])) {
    echo "Invalid payment session.";
    exit;
}

$session_id = $_GET['session_id'];

// Retrieve the Stripe session to confirm payment
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

// Fetch cart items for the logged-in user
$query = "SELECT c.*, p.id AS product_id, p.price 
          FROM cart c 
          JOIN products p ON c.product_id = p.id 
          WHERE c.email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $userEmail);
$stmt->execute();
$cartItemsResult = $stmt->get_result();

// Calculate the subtotal and discount if eligible
$subtotal = 0;
$cartItems = [];
while ($item = $cartItemsResult->fetch_assoc()) {
    $cartItems[] = $item;
    $subtotal += $item['price'] * $item['quantity'];
}

// Check if user is eligible for a loyalty card discount
$discountRate = 0;
$loyaltyCheckQuery = $conn->prepare("SELECT * FROM loyalty_card WHERE email = ?");
$loyaltyCheckQuery->bind_param("s", $userEmail);
$loyaltyCheckQuery->execute();
$loyaltyResult = $loyaltyCheckQuery->get_result();
if ($loyaltyResult->num_rows > 0) {
    $discountRate = 0.03; // 3% discount
}

// Calculate final totals
$discountAmount = $subtotal * $discountRate;
$totalAmountToPay = $subtotal - $discountAmount;

// Generate a unique reference number for this order
$referenceNumber = uniqid('ORD-', true); // Generate a unique reference number

// Insert the order into the `orders` table
$orderInsertQuery = $conn->prepare("INSERT INTO orders (reference_number, product_id, quantity, purchase_date, item_total, total_price, discount, email, status) VALUES (?, ?, ?, NOW(), ?, ?, ?, ?, 'Pending')");

foreach ($cartItems as $item) {
    $productName = $item['product_id'];
    $quantity = $item['quantity'];
    $itemTotal = $item['price'] * $quantity; // Calculate item total for each product

    // Calculate total price for the order
    $totalPrice = $totalAmountToPay; // You might want to save the total price in a different way depending on your logic

    $orderInsertQuery->bind_param("ssiidss", $referenceNumber, $productName, $quantity, $itemTotal, $totalPrice, $discountAmount, $userEmail);
    $orderInsertQuery->execute();
}

// Clear cart for the user
$deleteCartQuery = $conn->prepare("DELETE FROM cart WHERE email = ?");
$deleteCartQuery->bind_param("s", $userEmail);
$deleteCartQuery->execute();

// Close connections
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
</head>
<body>
    <h1>Payment Successful</h1>
    <p>Thank you for your purchase! Your order has been successfully placed.</p>
</body>
</html>
