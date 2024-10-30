<?php
session_start(); // Start the session at the beginning
require '../../connection.php';
require '../navbar/nav.php';

if (!isset($_SESSION['email'])) {
    echo "User is not logged in.";
    exit;
}

$loggedInOwnerEmail = $_SESSION['email'];

// Fetch products
$product_data = [];
$query = "SELECT p.*, s.shop_name FROM products p JOIN shops s ON p.shop_id = s.id";
$result = mysqli_query($conn, $query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
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
    <title>Product List</title>
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="../navbar/style.css">
    <link rel="stylesheet" href="../shop/product-list.css">
</head>
<body>
    <div class="main_container">
        <!-- View Cart Button -->
        <button class="view-cart-btn" onclick="window.location.href='view_cart.php'">View Cart</button>

        <button class="view-cart-btn" onclick="window.location.href='../Loyalty_card/loyalty_card.php'">Loyalty Card</button>
 
        <!-- Search Bar -->
        <form method="GET" action="">
            <div class="search-bar">
                <input type="text" name="search" placeholder="Search by Product Name">
                <button type="submit" class="search-btn"><i class="fas fa-search"></i> Search</button>
            </div>
        </form>
 
        <div class="product-card-container">
    <?php if (count($product_data) > 0): ?>
        <?php foreach ($product_data as $row): ?>
            <div class="product-card">
            <a class="go-to-shop-icon" onclick="window.location.href='shop_page.php?shop_id=<?= $row['shop_id'] ?>'">
        <i class='bx bxs-store'></i>
    </a>
                <img src="<?= htmlspecialchars($row['image_url']) ?>" alt="<?= htmlspecialchars($row['product_name']) ?>">
                <div class="product-details">
                    <h3><?= htmlspecialchars($row['product_name']) ?></h3>
                    <div class="price">Rs.<?= htmlspecialchars($row['price']) ?></div>
                    <div>Available: <?= htmlspecialchars($row['quantity_available']) ?></div>
                    <div>Shop: <?= htmlspecialchars($row['shop_name']) ?></div> <!-- Display shop name -->
                    <form action="add_to_cart.php" method="POST">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <input type="hidden" name="shop_id" value="<?= $row['shop_id'] ?>">
                        <input type="number" name="quantity" value="1" min="1" max="<?= $row['quantity_available'] ?>">
                        <button type="submit">Add to Cart</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No products found.</p>
    <?php endif; ?>
</div>

    </div>
</body>
</html>