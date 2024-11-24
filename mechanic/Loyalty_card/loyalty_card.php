<?php
session_start();
require '../../connection.php';
require '../navbar/nav.php';

if (!isset($_SESSION['email'])) {
    echo "User is not logged in.";
    exit;
}

$loggedInOwnerEmail = $_SESSION['email'];

$name = $_SESSION['name'];
$phone = $_SESSION['phone'];
$city = $_SESSION['address'];

$checkCardQuery = $conn->prepare("SELECT * FROM mech_loyalty_card WHERE email = ?");
$checkCardQuery->bind_param("s", $loggedInOwnerEmail);
$checkCardQuery->execute();
$result = $checkCardQuery->get_result();

if ($result->num_rows > 0) {
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
        <div class="cart-header">
            <button class="back-btn" onclick="window.location.href='../products/product.php'">&larr; Back</button>
        </div>
    <div class="container-card">
      <div class="up-1">
        <span class="logo-card">
          <img src="images/customer-loyalty.png" alt="" />
          <h5>Loyalty Card</h5>
        </span>
        <img src="images/chip.png" alt="" class="chip" />
      </div>

      <div class="card-details">
        <div class="name-number">
          <h6>Card Number</h6>
          <h5 class="number"><?= $cardNo ?></h5><br><br><br><br>
          <h5 class="name"><?= htmlspecialchars($name) ?></h5>
        </div>
        <div class="valid-date">
          <h6>Valid Thru</h6>
          <h5><?= $expireDate ?></h5>
        </div>
      </div>
    </body>
    </html>
    <?php
} else {
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
    <div class="loyalty-card-details">
    <div class="cart-header">
            <button class="back-btn" onclick="window.location.href='../products/product.php'">&larr; Back</button>
        </div>
        <h2>Your Details</h2>
        <p><strong>Name:</strong> <?= htmlspecialchars($name) ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($phone) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($loggedInOwnerEmail) ?></p>
        <p><strong>City:</strong> <?= htmlspecialchars($city) ?></p>
        
        <form action="stripe_payment.php" method="POST">
            <input type="hidden" name="email" value="<?= htmlspecialchars($loggedInOwnerEmail) ?>">
            <button type="submit">Purchase Card</button>
        </form>
    </div> <br> <br>
</body>
</html>

    <?php require '../footer/footer.php';
}

$checkCardQuery->close();
$conn->close();
?>
