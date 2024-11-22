<?php
session_start();
require '../../connection.php';
require '../navbar/nav.php';

if (!isset($_SESSION['email'])) {
    echo "User is not logged in.";
    exit;
}

$userEmail = $_SESSION['email'];
$query = "SELECT c.*, p.product_name, p.image_url, s.shop_name 
          FROM mech_cart c 
          JOIN products p ON c.product_id = p.id 
          JOIN shops s ON p.shop_id = s.id 
          WHERE c.email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $userEmail);
$stmt->execute();
$result = $stmt->get_result();

$cart_items = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $cart_items[] = $row;
    }
}

$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="../navbar/style.css">
    <link rel="stylesheet" href="add-to-cart.css">
    <title>Your Cart</title>
    <style>
        .small-icon {
            width: 32px; 
            height: 32px; 
            object-fit: contain; 
            margin: 0 5px; 
            transition: transform 0.3s; 
        }

        .small-icon:hover {
            transform: scale(1.1);
        }
    </style>
</head>
<body>
    <div class="main_container">
        <?php if ($message == 'removed'): ?>
            <div class="alert remove-success" id="success-alert">The Item removed successfully.</div>
        <?php elseif ($message == 'err'): ?>
            <div class="alert alert-success" id="success-alert">Something went wrong.</div>
        <?php endif; ?>

        <!-- Cart Header and Back Button -->
            <div class="cart-header">
            <button class="back-btn" onclick="window.location.href='product.php'">&larr; Back</button>
            <h1>Your Cart</h1>
        </div>

        <div class="product-card">
            <?php if (count($cart_items) > 0): ?>
                <?php foreach ($cart_items as $item): ?>
                    <div class="cart-item">
                        <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['product_name']) ?>">
                        <div class="product-details">
                            <h3><?= htmlspecialchars($item['product_name']) ?></h3>
                            <div>Price: Rs. <?= htmlspecialchars($item['price']) ?></div>
                            <div>Quantity: <?= htmlspecialchars($item['quantity']) ?></div>
                            <div>Shop: <?= htmlspecialchars($item['shop_name']) ?></div>
                        </div>
                        <form action="remove_from_cart.php" method="POST"> <!-- Create a remove_from_cart.php for removing items -->
                            <input type="hidden" name="cart_id" value="<?= $item['id'] ?>">
                            <button type="submit" class="remove-btn" >
                            <img src="../../img/delete.png" alt="delete icon" class="small-icon">
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Your cart is empty.</p>
            <?php endif; ?>
        </div>
        <button class="checkout-btn" onclick="window.location.href='pay.php'">Proceed to Checkout</button>
    </div>
    <?php require '../../vehicle_owner/footer/footer.php';?>
</body>

<script>
    // Hide the alert message after 10 seconds
    setTimeout(function() {
        var alert = document.getElementById('success-alert');
        if (alert) {
            alert.style.display = 'none';
        }
    }, 10000); // 10 seconds
</script>

</html>
