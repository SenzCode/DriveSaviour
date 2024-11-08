<?php
require 'vendor/autoload.php'; // Include Stripe PHP library

\Stripe\Stripe::setApiKey('sk_test_51PIMdPDwJDfpiSSr04muva7l4XmHisSOvB1AKimDn25sT7tkMB5TRWvAt7we5h3xMMpL6zjAAas2J7ktFAoERJ4600kydtwfzm'); // Replace with your secret key

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $total_amount = $_POST['total_amount'];

    // Convert total amount to cents for Stripe (assuming amount is in LKR)
    $amount_in_cents = $total_amount * 100;

    // Create a new Checkout Session for the payment
    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'lkr',
                'product_data' => [
                    'name' => 'Total Seller Income Payment',
                ],
                'unit_amount' => $amount_in_cents,
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' => 'http://localhost:3000/admin/Finance/success.php?email=' . urlencode($email),
        'cancel_url' => 'http://localhost:3000/admin/Finance/finance.php',
    ]);

    // Redirect to the Stripe Checkout page
    header('Location: ' . $session->url);
    exit;
}
?>
