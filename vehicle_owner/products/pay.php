<?php
session_start();
require '../../connection.php';
require '../navbar/nav.php';

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

// Check if the user is eligible for a loyalty card discount
$discountRate = 0;
$loyaltyCheckQuery = $conn->prepare("SELECT * FROM loyalty_card WHERE email = ?");
$loyaltyCheckQuery->bind_param("s", $userEmail);
$loyaltyCheckQuery->execute();
$loyaltyResult = $loyaltyCheckQuery->get_result();

if ($loyaltyResult->num_rows > 0) {
    $discountRate = 0.03; // 3% discount
}

// Fetch cart items for the logged-in user
$query = "SELECT c.*, p.product_name, p.price, s.shop_name 
          FROM cart c 
          JOIN products p ON c.product_id = p.id 
          JOIN shops s ON p.shop_id = s.id 
          WHERE c.email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $userEmail);
$stmt->execute();
$result = $stmt->get_result();

$cart_items = [];
$subtotal = 0;

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $cart_items[] = $row;
        $subtotal += $row['price'] * $row['quantity'];
    }
}

// Calculate discount amount and final total to pay
$discountAmount = $subtotal * $discountRate;
$totalAmountToPay = $subtotal - $discountAmount;

// Prepare data for Stripe Checkout
$line_items = [
    [
        'price_data' => [
            'currency' => 'lkr',
            'product_data' => [
                'name' => 'Total Amount after Discount',
            ],
            'unit_amount' => $totalAmountToPay * 100, // Stripe uses cents
        ],
        'quantity' => 1,
    ]
];

// Create Stripe checkout session
$session = \Stripe\Checkout\Session::create([
    'payment_method_types' => ['card'],
    'line_items' => $line_items,
    'mode' => 'payment',
    'success_url' => 'http://localhost:3000/vehicle_owner/products/success.php?session_id={CHECKOUT_SESSION_ID}',  // Replace with your actual success URL
    'cancel_url' => 'http://localhost:3000/vehicle_owner/products/cancel.php',    // Replace with your actual cancel URL
]);

// Close connections
$stmt->close();
$loyaltyCheckQuery->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link rel="stylesheet" href="../navbar/style.css">
    <script src="https://js.stripe.com/v3/"></script>
    <style>

.dark-mode header{
    background-color: rgb(0, 0, 0);;
}
 
.dark-mode nav ul li a{
    color: white;
}
 
.dark-mode  .menu-toggle .bar,
.dark-mode  .menu-toggle .bar2{
    background-color: white;
}
.dark-mode .total-amount{
    color: white;
    background-color: black;
    border: 1px solid #2B5AC2;
}
.dark-mode tr:nth-child(even) td{
    background-color: #212121;
}
.total-amount {
    width: 100%;
    max-width: 500px; 
    background-color: #ffffff;
    color: #333;
    padding: 30px;
    margin: 20px auto;
    font-family: Arial, sans-serif; 
    font-size: 14px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    border: 1px solid #ddd;
    border-radius: 20px;
    line-height: 1.6;
    margin-top: 130px;
}

h1 {
    text-align: center;
    font-size: 18px;
    margin: 0 0 10px;
    padding: 0;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 1px;
    border-bottom: 1px dashed #ddd;
    padding-bottom: 10px;
}

h3 {
    font-size: 16px;
    font-weight: bold;
}

p, th, td {
    font-size: 14px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

th, td {
    text-align: left;
    padding: 5px 0;
}

th {
    font-weight: bold;
}

tr:nth-child(even) td {
    background-color: #f9f9f9;
}

.dashed-line {
    border-top: 1px dashed #ddd;
    margin: 10px 0;
}

.subtotal, .total {
    display: flex;
    justify-content: space-between;
    margin: 10px 0;
}

.dark-mode .discount{
    color: red;
}

.discount {
    display: flex;
    justify-content: space-between;
    color: darkred;
}

#checkout-button {
    display: block;
    width: 100%;
    padding: 10px;
    background-color: #28a745; 
    color: white;
    border: none;
    border-radius: 3px;
    cursor: pointer;
    font-size: 16px;
    margin-top: 20px;
    text-align: center;
}

#checkout-button:hover {
    background-color: #218838;
}

.footer {
    text-align: center;
    font-size: 14px;
    font-weight: bold;
    margin-top: 20px;
}

.footer .thank-you {
    border-top: 1px dashed #ddd;
    padding-top: 10px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.footer .barcode {
    margin-top: 10px;
}

/* Responsive adjustments */
@media (max-width: 500px) {
    .total-amount {
        padding: 15px;
    }
    h1 {
        font-size: 16px;
    }
    h3, p, th, td {
        font-size: 14px;
    }
    #checkout-button {
        font-size: 14px;
    }
}

    </style>
</head>
<body>
    <div class="total-amount">
        <h1>Receipt</h1>
        
        <div class="info">
        <p>
            <span>Terminal#1</span>
            <span style="float: right;"><?= date("d-m-Y h:i A") ?></span>
        </p>

            <div class="dashed-line"></div>
        </div>
        
        <h3>Product Details:</h3>
        <table>
            <tr>
                <th>Product Name</th>
                <th>Unit Price</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
            <?php foreach ($cart_items as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['product_name']) ?></td>
                    <td>Rs. <?= htmlspecialchars($item['price']) ?></td>
                    <td><?= htmlspecialchars($item['quantity']) ?></td>
                    <td>Rs. <?= htmlspecialchars($item['price'] * $item['quantity']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <div class="dashed-line"></div>

        <div class="subtotal">
            <span>Subtotal:</span>
            <span>Rs. <?= htmlspecialchars($subtotal) ?></span>
        </div>
        <?php if ($discountRate > 0): ?>
            <div class="discount">
                <span>Discount (3%):</span>
                <span>- Rs. <?= htmlspecialchars($discountAmount) ?></span>
            </div>
        <?php endif; ?>
        <div class="total">
            <span>Total Amount:</span>
            <span>Rs. <?= htmlspecialchars($totalAmountToPay) ?></span>
        </div>

        <!-- Checkout button -->
        <button id="checkout-button">Proceed to Payment</button>

        <!-- Footer with Thank You and Barcode -->
        <div class="footer">
            <div class="thank-you">*** Thank You! ***</div>
            <div class="barcode">|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||</div>
        </div>
    </div>
    
    <script src="https://js.stripe.com/v3/"></script>
    <script type="text/javascript">
        var stripe = Stripe('pk_test_51PfklnDFvPyG4fvuMaEs2nvWYDso54r90Op0XcSzzVwnbHnFX5z5LUpTjIH4XFWuAapq6BxvIg2owDqoYXoyG9BJ00Z9SBx1bD');
        var checkoutButton = document.getElementById('checkout-button');

        checkoutButton.addEventListener('click', function () {
            stripe.redirectToCheckout({
                sessionId: '<?= $session->id ?>'
            }).then(function (result) {
                if (result.error) {
                    alert(result.error.message);
                }
            });
        });
    </script>
</body>
</html>
