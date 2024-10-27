<?php
session_start();
require '../../connection.php';
require 'vendor/autoload.php'; // Stripe PHP library

// Stripe API configuration (replace with your secret key)
                           
\Stripe\Stripe::setApiKey('sk_test_51PfklnDFvPyG4fvuUh6ZfPSa5LBwdmWSlgABfkzEjUZeJH5YHDpHoHzWRKDrjYt325wJZSXY4ip4TY4tYfZ9cYnZ00AkL5f2Zd');

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    echo "User is not logged in.";
    exit;
}

// Get the logged-in user's email
$userEmail = $_SESSION['email'];

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
$total_amount = 0;

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $cart_items[] = $row;
        $total_amount += $row['price'] * $row['quantity'];
    }
}

// Prepare data for Stripe Checkout
$line_items = [];
foreach ($cart_items as $item) {
    $line_items[] = [
        'price_data' => [
            'currency' => 'usd', // Change the currency as needed
            'product_data' => [
                'name' => $item['product_name'],
            ],
            'unit_amount' => $item['price'] * 100, // Stripe expects amounts in cents
        ],
        'quantity' => $item['quantity'],
    ];
}

// Create Stripe checkout session
$session = \Stripe\Checkout\Session::create([
    'payment_method_types' => ['card'],
    'line_items' => [$line_items],
    'mode' => 'payment',
    'success_url' => 'http://localhost:3000/vehicle_owner/products/success.php',  // Replace with your actual success URL
    'cancel_url' => 'http://localhost:3000/vehicle_owner/products/cancel.php',    // Replace with your actual cancel URL
]);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>
    <h1>Payment</h1>
    <div class="total-amount">
        <h3>Total Amount: Rs. <?= htmlspecialchars($total_amount) ?></h3>
    </div>

    <!-- Redirect to Stripe Checkout -->
    <button id="checkout-button">Proceed to Payment</button>

    <script type="text/javascript">
        var stripe = Stripe('pk_test_51PfklnDFvPyG4fvuMaEs2nvWYDso54r90Op0XcSzzVwnbHnFX5z5LUpTjIH4XFWuAapq6BxvIg2owDqoYXoyG9BJ00Z9SBx1bD');  // Replace with your publishable key
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
