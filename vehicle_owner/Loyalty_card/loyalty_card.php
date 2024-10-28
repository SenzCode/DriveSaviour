<?php
session_start(); // Start the session at the beginning
require '../../connection.php';
require '../navbar/nav.php';

if (!isset($_SESSION['email'])) {
    echo "User is not logged in.";
    exit;
}

$loggedInOwnerEmail = $_SESSION['email'];

// Get user details from the session
$name = $_SESSION['name'];
$phone = $_SESSION['phone'];
$city = $_SESSION['city'];

// Check if the user is already registered for the loyalty card
$checkCardQuery = $conn->prepare("SELECT * FROM loyalty_card WHERE email = ?");
$checkCardQuery->bind_param("s", $loggedInOwnerEmail);
$checkCardQuery->execute();
$result = $checkCardQuery->get_result();

if ($result->num_rows > 0) {
    // User is already registered; fetch their loyalty card data
    $loyaltyCard = $result->fetch_assoc();
    $cardNo = htmlspecialchars($loyaltyCard['card_no']);
    $expireDate = htmlspecialchars($loyaltyCard['expire_date']);
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Loyalty Card</title>
        <link rel="stylesheet" href="../navbar/style.css">
        <link rel="stylesheet" href="style.css">
    </head>
    <body>

    <div class="container-card">
      <header>
        <span class="logo-card">
          <img src="images/logo.png" alt="" />
          <h5>Loyalty Card</h5>
        </span>
        <img src="images/chip.png" alt="" class="chip" />
      </header>

      <div class="card-details">
        <div class="name-number">
          <h6>Card Number</h6>
          <h5 class="number"><?= $cardNo ?></h5>
          <h5 class="name"><?= htmlspecialchars($name) ?></h5>
        </div>
        <div class="valid-date">
          <h6>Valid Thru</h6>
          <h5><?= $expireDate ?></h5>
        </div>
      </div>
        <!-- <div class="loyalty-card-details">
            <h2>Your Loyalty Card Details</h2>
            <p><strong>Name:</strong> <?= htmlspecialchars($name) ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($phone) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($loggedInOwnerEmail) ?></p>
            <p><strong>City:</strong> <?= htmlspecialchars($city) ?></p>
            <p><strong>Card Number:</strong> <?= $cardNo ?></p>
            <p><strong>Expiration Date:</strong> <?= $expireDate ?></p>
        </div> -->
    </body>
    </html>
    <?php
} else {
    // User is not registered; show the purchase option
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Loyalty Card</title>
        <link rel="stylesheet" href="../navbar/style.css">
    </head>
    <body>
        <div class="loyalty-card-details">
            <h2>Your Details</h2>
            <p><strong>Name:</strong> <?= htmlspecialchars($name) ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($phone) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($loggedInOwnerEmail) ?></p>
            <p><strong>City:</strong> <?= htmlspecialchars($city) ?></p>
            
            <form action="stripe_payment.php" method="POST">
                <input type="hidden" name="email" value="<?= htmlspecialchars($loggedInOwnerEmail) ?>">
                <button type="submit">Purchase Card</button>
            </form>
        </div>
    </body>
    </html>
    <?php
}

// Close the database connection
$checkCardQuery->close();
$conn->close();
?>
