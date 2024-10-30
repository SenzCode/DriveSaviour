<?php
require '../navbar/nav.php';
require '../../connection.php';

$shop_id = isset($_GET['shop_id']) ? intval($_GET['shop_id']) : 0;
$shop_data = [];
$product_data = [];

// Fetch shop information
$shop_query = "SELECT * FROM shops WHERE id = $shop_id";
$shop_result = mysqli_query($conn, $shop_query);
if ($shop_result && mysqli_num_rows($shop_result) > 0) {
    $shop_data = mysqli_fetch_assoc($shop_result);
} else {
    echo "<p>Shop not found.</p>";
    exit;
}

// Fetch products for the shop
$product_query = "SELECT * FROM products WHERE shop_id = $shop_id";
$product_result = mysqli_query($conn, $product_query);
if ($product_result) {
    while ($row = mysqli_fetch_assoc($product_result)) {
        $product_data[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($shop_data['shop_name']) ?></title>
    <link rel="stylesheet" href="../navbar/style.css">
    <link rel="stylesheet" href="../shop/product-list.css">
    
</head>
<body>
    <div class="main_container">
    <div class="shop-info">
        <h1><?= htmlspecialchars($shop_data['shop_name']) ?></h1>
        <p>Location: <?= htmlspecialchars($shop_data['branch']) ?></p>
        <p>Contact: <span class="contact-number"><?= htmlspecialchars($shop_data['number']) ?></span></p>
    </div>

        <!-- Products List -->
        <br>
        <h2>Products Available</h2>
        <div class="product-card-container" style="margin-top: 20px;">
            <?php if (count($product_data) > 0): ?>
                <?php foreach ($product_data as $product): ?>
                    <div class="product-card">
                        <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['product_name']) ?>">
                        <div class="product-details">
                            <h3><?= htmlspecialchars($product['product_name']) ?></h3>
                            <div class="price">Rs.<?= htmlspecialchars($product['price']) ?></div>
                            <div>Available: <?= htmlspecialchars($product['quantity_available']) ?></div>
                            <form action="add_to_cart.php" method="POST">
                                <input type="hidden" name="id" value="<?= $product['id'] ?>">
                                <input type="number" name="quantity" value="1" min="1" max="<?= $product['quantity_available'] ?>">
                                <button type="submit">Add to Cart</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No products available in this shop.</p>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>
