<?php
require '../products/vendor/autoload.php';

\Stripe\Stripe::setApiKey('sk_test_51PfklnDFvPyG4fvuUh6ZfPSa5LBwdmWSlgABfkzEjUZeJH5YHDpHoHzWRKDrjYt325wJZSXY4ip4TY4tYfZ9cYnZ00AkL5f2Zd');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'lkr',
                'product_data' => [
                    'name' => 'Loyalty Card',
                ],
                'unit_amount' => 500000,
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' => 'http://localhost:3000/vehicle_owner/Loyalty_card/success.php?email=' . urlencode($email),
        'cancel_url' => 'http://localhost:3000/vehicle_owner/Loyalty_card/loyalty_card.php',
    ]);


    header('Location: ' . $session->url);
    exit;
}
?>
