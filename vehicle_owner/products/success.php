<?php
session_start();
require '../../connection.php';
require 'vendor/autoload.php';
require '../../vendor/autoload.php';

// Stripe API setup
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

// Retrieve cart items for the user
$query = "SELECT c.*, p.id AS product_id, p.product_name, p.price 
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

// Check for loyalty discount
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

// Generate a unique order reference number
$referenceNumber = uniqid('ORD-', true);

// Insert order details into the database
$orderInsertQuery = $conn->prepare("INSERT INTO orders (reference_number, product_id, quantity, purchase_date, item_total, total_price, discount, seller_income, commission, email, status) VALUES (?, ?, ?, NOW(), ?, ?, ?, ?, ?, ?, 'Pending')");

foreach ($cartItems as $item) {
    $productId = $item['product_id'];
    $quantity = $item['quantity'];
    $itemTotal = $item['price'] * $quantity;

    // Calculate commission and seller income
    $commission = $itemTotal * 0.03; // 3% commission
    $sellerIncome = $itemTotal - $commission;

    $totalPrice = $totalAmountToPay;

    $orderInsertQuery->bind_param("ssiidddss", $referenceNumber, $productId, $quantity, $itemTotal, $totalPrice, $discountAmount, $sellerIncome, $commission, $userEmail);
    $orderInsertQuery->execute();

    // Update product quantity in the database
    $updateProductQuantityQuery = $conn->prepare("UPDATE products SET quantity_available = quantity_available - ? WHERE id = ?");
    $updateProductQuantityQuery->bind_param("ii", $quantity, $productId);
    $updateProductQuantityQuery->execute();
}

// Clear the cart for the user
$deleteCartQuery = $conn->prepare("DELETE FROM cart WHERE email = ?");
$deleteCartQuery->bind_param("s", $userEmail);
$deleteCartQuery->execute();

// Send confirmation email using PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();                                // Set mailer to use SMTP
    $mail->Host       = 'smtp.gmail.com';           // Specify main and backup SMTP servers
    $mail->SMTPAuth   = true;                       // Enable SMTP authentication
    $mail->Username   = 'ramithacampus@gmail.com';     // SMTP username
    $mail->Password   = 'ijjn tjwp erwe ktns';      // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
    $mail->Port       = 587;                        // TCP port to connect to

    // Recipients
    $mail->setFrom('ramithacampus@gmail.com', 'DriveSaviour');
    $mail->addAddress($userEmail);                 // Add a recipient

    // Email content
    $mail->isHTML(true);                           // Set email format to HTML
    $mail->Subject = 'Payment Successful - Your Order Confirmation';
    $mail->Body    = "
        <h1>Thank you for your purchase!</h1>
        <p>Your payment has been successfully processed. Your order reference number is <strong>$referenceNumber</strong>.</p>
        <p>Order Details:</p>
        <ul>
    ";
    foreach ($cartItems as $item) {
        $mail->Body .= "<li>{$item['product_name']} - Quantity: {$item['quantity']}</li>";
    }
    $mail->Body .= "
        </ul>
        <p>Total Amount Paid: <strong>Rs. {$totalAmountToPay}</strong></p>
        <p>We appreciate your business and hope to see you again soon!</p>
    ";

    $mail->AltBody = "Thank you for your purchase! Your payment has been successfully processed. Your order reference number is $referenceNumber.";

    $mail->send();
} catch (Exception $e) {
    echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

// Close database connections
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
        <img src="../shop/img/success.png" alt="Success" class="success-image">
        <h1>Payment Successful</h1>
        <p>Thank you for your purchase! Your order has been successfully placed.</p>
        <button onclick="window.location.href='product.php'">Back to the product page</button>
    </div>
</body>
</html>
