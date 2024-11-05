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
          FROM cart c 
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
$loyaltyCheckQuery = $conn->prepare("SELECT * FROM loyalty_card WHERE email = ?");
$loyaltyCheckQuery->bind_param("s", $userEmail);
$loyaltyCheckQuery->execute();
$loyaltyResult = $loyaltyCheckQuery->get_result();
if ($loyaltyResult->num_rows > 0) {
    $discountRate = 0.03; // 3% discount
}

$discountAmount = $subtotal * $discountRate;
$totalAmountToPay = $subtotal - $discountAmount;

$referenceNumber = uniqid('ORD-', true);

$orderInsertQuery = $conn->prepare("INSERT INTO orders (reference_number, product_id, quantity, purchase_date, item_total, total_price, discount, seller_income, commission, email, status) VALUES (?, ?, ?, NOW(), ?, ?, ?, ?, ?, ?, 'Pending')");

foreach ($cartItems as $item) {
    $productName = $item['product_id'];
    $quantity = $item['quantity'];
    $itemTotal = $item['price'] * $quantity;

    // Calculate commission and seller income
    $commission = $itemTotal * 0.03; // 3% commission
    $sellerIncome = $itemTotal - $commission; // Seller income after deducting commission

    $totalPrice = $totalAmountToPay;

    $orderInsertQuery->bind_param("ssiidddss", $referenceNumber, $productName, $quantity, $itemTotal, $totalPrice, $discountAmount, $sellerIncome, $commission, $userEmail);
    $orderInsertQuery->execute();

    $updateProductQuantityQuery = $conn->prepare("UPDATE products SET quantity_available = quantity_available - ? WHERE id = ?");
    $updateProductQuantityQuery->bind_param("ii", $quantity, $productName);
    $updateProductQuantityQuery->execute();
}

$deleteCartQuery = $conn->prepare("DELETE FROM cart WHERE email = ?");
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
</head>
<body>
    <h1>Payment Successful</h1>
    <p>Thank you for your purchase! Your order has been successfully placed.</p>
    <button onclick="window.location.href='product.php'">Back to the product page</button>
</body>
</html>
