<?php
require '../products/vendor/autoload.php';

\Stripe\Stripe::setApiKey('sk_test_51PfklnDFvPyG4fvuUh6ZfPSa5LBwdmWSlgABfkzEjUZeJH5YHDpHoHzWRKDrjYt325wJZSXY4ip4TY4tYfZ9cYnZ00AkL5f2Zd');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $mech_id = $_POST['mech_id']; // Ensure this is passed from the form

    // Save mech_id in session
    session_start();
    $_SESSION['mech_id'] = $mech_id;

    // Create a new Checkout Session for the purchase
    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'lkr',
                'product_data' => [
                    'name' => 'Monthly Subscription',
                ],
                'unit_amount' => 150000,
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' => 'http://localhost:3000/mechanic/Breakdown/success.php?mech_id=' . urlencode($email),
        'cancel_url' => 'http://localhost:3000/mechanic/Breakdown/breakdown.php',
    ]);

    // Redirect to the Stripe Checkout page
    header('Location: ' . $session->url);
    exit;
}
?>
