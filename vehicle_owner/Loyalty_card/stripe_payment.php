<?php
require '../products/vendor/autoload.php'; // Include Stripe PHP library

\Stripe\Stripe::setApiKey('sk_test_51PfklnDFvPyG4fvuUh6ZfPSa5LBwdmWSlgABfkzEjUZeJH5YHDpHoHzWRKDrjYt325wJZSXY4ip4TY4tYfZ9cYnZ00AkL5f2Zd'); // Replace with your secret key

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    
    // Create a new Checkout Session for the purchase
    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'lkr',
                'product_data' => [
                    'name' => 'Loyalty Card',
                ],
                'unit_amount' => 500000, // $50.00 (in cents)
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' => 'http://localhost:3000/vehicle_owner/Loyalty_card/success.php?email=' . urlencode($email),
        'cancel_url' => 'http://localhost:3000/vehicle_owner/Loyalty_card/cancel.php',
    ]);

    // Redirect to the Stripe Checkout page
    header('Location: ' . $session->url);
    exit;
}
?>
