<?php
session_start();
require '../../connection.php';
require '../navbar/nav.php';

if (!isset($_SESSION['email'])) {
    echo "User is not logged in.";
    exit;
}

$userEmail = $_SESSION['email'];
$query = "SELECT o.*, p.product_name, p.image_url 
          FROM orders o 
          JOIN products p ON o.product_id = p.id 
          WHERE o.email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $userEmail);
$stmt->execute();
$result = $stmt->get_result();

$orders = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="../navbar/style.css">
    <link rel="stylesheet" href="style.css">
    <title>Your Orders</title>
</head>
<body>
    <div class="main_container">
        <div class="order-header">
            <button class="back-btn" onclick="window.location.href='../products/product.php'">&larr; Back</button>
                <h1>Your Orders</h1>
        </div>
        <div class="order-card">
            
            <?php if (count($orders) > 0): ?>
                <?php foreach ($orders as $order): ?>
                    <div class="order-item">
                        <img src="<?= htmlspecialchars($order['image_url']) ?>" alt="<?= htmlspecialchars($order['product_name']) ?>">
                        <div class="order-details">
                            <h3><?= htmlspecialchars($order['product_name']) ?></h3>
                            <div>Order Reference: <?= htmlspecialchars($order['reference_number']) ?></div>
                            <div>Quantity: <?= htmlspecialchars($order['quantity']) ?></div>
                            <div>Purchase Date: <?= htmlspecialchars($order['purchase_date']) ?></div>
                            <div>Item Total: Rs. <?= htmlspecialchars($order['item_total']) ?></div>
                            <div>Total Price: Rs. <?= htmlspecialchars($order['total_price']) ?></div>
                            <div>Discount: Rs. <?= htmlspecialchars($order['discount']) ?></div>
                            <div>Status: <?= htmlspecialchars($order['status']) ?></div>
                            <button class="rate-btn" onclick="window.location.href='../ratings/rate_product.php?product_id=<?= $order['product_id'] ?>'">Rate Product</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>You have no orders.</p>
            <?php endif; ?>
        </div>
    </div>
    <?php
    require '../footer/footer.php';
    ?>
</body>
</html>
