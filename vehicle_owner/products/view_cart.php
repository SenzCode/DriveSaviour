<?php
session_start();
require '../../connection.php';
require '../navbar/nav.php';

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    echo "User is not logged in.";
    exit;
}

// Get the logged-in user's email
$userEmail = $_SESSION['email'];

// Fetch cart items for the logged-in user
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

// Close the statement
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="../navbar/style.css">
    <link rel="stylesheet" href="../shop/add-to-cart.css"><!-- Add your CSS file here -->
    <title>Your Cart</title>
</head>
<body>
<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
<?php elseif (isset($_GET['error'])): ?>
    <div class="alert alert-error"><?= htmlspecialchars($_GET['error']) ?></div>
<?php endif; ?>

    <div class="main_container">
        <h1>Your Cart</h1>
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
                                <i class='bx bx-trash'></i>
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
</body>
</html>
