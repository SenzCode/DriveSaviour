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
          FROM cart c 
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
    <title>Your Cart</title>
    <link rel="stylesheet" href="../navbar/style.css">
    <link rel="stylesheet" href="view_cart.css">
    
</head>

<body>
    <div class="main_container">
        <!-- Display success/error messages -->
        <?php if ($message == 'removed'): ?>
            <div class="alert remove-success" id="success-alert">The item was removed successfully.</div>
        <?php elseif ($message == 'err'): ?>
            <div class="alert alert-success" id="success-alert">Something went wrong.</div>
        <?php endif; ?>

        <!-- Cart Header -->
        <div class="cart-header">
            <a href="product.php" class="back-btn">← Back</a>
            <h1 class="cart-title">Your Cart</h1>
        </div>

        <!-- Cart Items -->
        <div class="cart-items-container">
            <?php if (count($cart_items) > 0): ?>
                <?php foreach ($cart_items as $item): ?>
                    <div class="cart-item">
                        <div class="item-image">
                            <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['product_name']) ?>">
                        </div>
                        <div class="item-info">
                            <h3 class="product-name"><?= htmlspecialchars($item['product_name']) ?></h3>
                            <p class="product-price">Price: Rs. <?= htmlspecialchars($item['price']) ?></p>
                            <p class="product-quantity">Quantity: <?= htmlspecialchars($item['quantity']) ?></p>
                            <p class="product-shop">Shop: <?= htmlspecialchars($item['shop_name']) ?></p>
                        </div>
                        <form action="remove_from_cart.php" method="POST" class="remove-form">
                            <input type="hidden" name="cart_id" value="<?= $item['id'] ?>">
                            <button type="submit" class="remove-btn">
                                <img src="../../img/delete.png" alt="Delete" class="small-icon">
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="empty-cart-message">Your cart is empty.</p>
            <?php endif; ?>
        </div>

        <!-- Checkout Button -->
        <div class="checkout-container">
            <button class="checkout-btn" onclick="window.location.href='pay.php'">Proceed to Checkout</button>
        </div>
    </div>

    <!-- Footer -->
    <?php require '../footer/footer.php'; ?>

    <!-- JavaScript to hide success alert -->
    <script>
        setTimeout(function() {
            var alert = document.getElementById('success-alert');
            if (alert) {
                alert.style.display = 'none';
            }
        }, 10000); // 10 seconds
    </script>
</body>

</html>
