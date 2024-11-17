<?php
session_start();

if (!isset($_SESSION['email'])) {
    echo "User is not logged in.";
    exit;
}

$loggedInOwnerEmail = $_SESSION['email'];

require('../navbar/nav.php');
include_once('../../connection.php');

$shop_id = isset($_GET['shop_id']) ? intval($_GET['shop_id']) : 0;
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';

$product_data = [];
if ($shop_id > 0) {
    $query = "SELECT * FROM products WHERE shop_id = ?";

    if (!empty($search_query)) {
        $query .= " AND product_name LIKE ?";
    }

    $stmt = $conn->prepare($query);
    
    if (!empty($search_query)) {
        $search_param = '%' . $search_query . '%';
        $stmt->bind_param("is", $shop_id, $search_param);
    } else {
        $stmt->bind_param("i", $shop_id);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $product_data[] = $row;
    }
    
    $stmt->close();
}

$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link rel="stylesheet" href="../navbar/style.css">
    <link rel="stylesheet" href="product-list.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
</head>

<body>
    <div class="main_container">
        <button class="view-cart-btn" onclick="window.location.href='view_cart.php'">View Cart</button>

        <form method="GET" action="">
            <input type="hidden" name="shop_id" value="<?= $shop_id ?>">
            <div class="search-bar">
                <input type="text" name="search" placeholder="Search by Product Name" value="<?= htmlspecialchars($search_query) ?>">
                <button type="submit" class="search-btn"><i class="fas fa-search"></i> Search</button>
            </div>
        </form>

        <div class="product-card-container">
            <?php if (count($product_data) > 0): ?>
                <?php foreach ($product_data as $row): ?>
                    <div class="product-card">
                        <img src="<?= htmlspecialchars($row['image_url']) ?>" alt="<?= htmlspecialchars($row['product_name']) ?>">
                        <div class="product-details">
                            <h3><?= htmlspecialchars($row['product_name']) ?></h3>
                            <div class="price">Rs.<?= htmlspecialchars($row['price']) ?></div>
                            <div>Available: <?= htmlspecialchars($row['quantity_available']) ?></div>
                            <form action="add_to_cart.php" method="POST">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <input type="hidden" name="shop_id" value="<?= $shop_id ?>">
                                <input type="number" name="quantity" value="1" min="1" max="<?= $row['quantity_available'] ?>">
                                <button type="submit">Add to Cart</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No products found for this shop.</p>
            <?php endif; ?>
        </div>

    </div>
</body>

</html>
