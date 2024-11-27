<?php
session_start();
ob_start();
require '../../connection.php';
require '../navbar/nav.php';

if (!isset($_SESSION['email'])) {
    header('Location: ../Login/login.php');
    exit;
}

$loggedInOwnerEmail = $_SESSION['email'];

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$category = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';

$query = "SELECT p.*, s.shop_name, c.category_name, 
                 (SELECT AVG(r.rating) FROM ratings r WHERE r.product_id = p.id) AS avg_rating
          FROM products p 
          JOIN shops s ON p.shop_id = s.id 
          LEFT JOIN category c ON p.cat_id = c.id 
          WHERE 1";

// Apply search filter for product name
if ($search) {
    $query .= " AND p.product_name LIKE '%$search%'";
}

// Apply category filter
if ($category > 0) {
    $query .= " AND p.cat_id = $category";
}

// Apply sorting
if ($sort === 'price_asc') {
    $query .= " ORDER BY p.price ASC";
} elseif ($sort === 'price_desc') {
    $query .= " ORDER BY p.price DESC";
}

$result = mysqli_query($conn, $query);
$product_data = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $product_data[] = $row;
    }
}

$category_query = "SELECT * FROM category";
$category_result = mysqli_query($conn, $category_query);
$categories = [];
if ($category_result) {
    while ($cat = mysqli_fetch_assoc($category_result)) {
        $categories[] = $cat;
    }
}

$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';

// Get the current date and the date for 7 days ago
$currentDate = date('Y-m-d');
$lastWeekDate = date('Y-m-d', strtotime('-1 week'));

// Query to get the total quantity sold for each product in the last week
$trendingQuery = "
    SELECT p.id, p.product_name, p.image_url, p.shop_id, SUM(o.quantity) AS total_sold
    FROM products p
    JOIN (
        SELECT product_id, quantity FROM orders WHERE purchase_date BETWEEN '$lastWeekDate' AND '$currentDate'
        UNION ALL
        SELECT product_id, quantity FROM mech_orders WHERE purchase_date BETWEEN '$lastWeekDate' AND '$currentDate'
    ) o ON p.id = o.product_id
    GROUP BY p.id
    ORDER BY total_sold DESC
    LIMIT 5";

$trendingResult = mysqli_query($conn, $trendingQuery);
$trendingProducts = [];
if ($trendingResult) {
    while ($row = mysqli_fetch_assoc($trendingResult)) {
        $trendingProducts[] = $row;
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
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../shop/product-list.css">
    <style>
        .product-card-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 50px;
        }

        .product-card {
            position: relative;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .store-icon {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 1;
        }

        .store-icon img {
            width: 32px;
            height: 32px;
            object-fit: contain;
            transition: transform 0.3s;
        }

        .store-icon img:hover {
            transform: scale(1.1);
        }

        .product-details img {
            max-width: 100%;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .product-details h3 {
            font-size: 18px;
            margin: 10px 0;
        }

        .price {
            font-weight: bold;
            margin: 5px 0;
        }

        .star-rating {
            margin: 5px 0;
        }

        .star-rating .star {
            color: #ffd700;
            font-size: 16px;
        }

        .star-rating .star.filled {
            color: #ffa500;
        }
    </style>
</head>
<body>
<div class="main_container">
    <?php if ($message == 'insert'): ?>
        <div class="alert alert-success" id="success-alert">The Item added to the cart successfully.</div>
    <?php elseif ($message == 'update'): ?>
        <div class="alert alert-success" id="success-alert">Another Item added to the cart successfully.</div>
    <?php endif; ?>

    <div class="image-buttons-container">
        <a href="view_cart.php" class="image-link">
            <img src="../../img/cart.png" alt="Cart" class="small-icon">
        </a>
        <a href="../Loyalty_card/loyalty_card.php" class="image-link">
            <img src="../../img/loyalty card.png" alt="Loyalty Card" class="small-icon">
        </a>
        <a href="../orders/orders.php" class="image-link">
            <img src="../../img/orders.png" alt="Orders" class="small-icon">
        </a>
    </div>

    <div class="trending-products">
        <h2>Trending Products of the Week</h2>
        <div class="trending-card-container">
            <?php if (count($trendingProducts) > 0): ?>
                <?php foreach ($trendingProducts as $product): ?>
                    <div class="trending-card">
                        <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['product_name']) ?>" loading="lazy">
                        <div class="product-details">
                            <h3><?= htmlspecialchars($product['product_name']) ?></h3>
                            <form action="add_to_cart.php" method="POST">
                                <!-- Hidden fields to pass shop_id and default quantity -->
                                <input type="hidden" name="id" value="<?= $product['id'] ?>">
                                <input type="hidden" name="shop_id" value="<?= $product['shop_id'] ?>">
                                <input type="number" name="quantity" value="1" min="1">
                                <button type="submit">Add to Cart</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No trending products this week.</p>
            <?php endif; ?>
        </div>
    </div>


    <form method="GET" action="">
        <div class="search-bar">
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search by Product Name">
            <button type="submit" class="search-btn"><i class="fas fa-search"></i> Search</button>
        </div>

        <div class="category-filter">
            <select name="category">
                <option value="0">All Categories</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= ($category == $cat['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['category_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="sort-options">
            <select name="sort">
                <option value="">Sort by Price</option>
                <option value="price_asc" <?= ($sort == 'price_asc') ? 'selected' : '' ?>>Price: Low to High</option>
                <option value="price_desc" <?= ($sort == 'price_desc') ? 'selected' : '' ?>>Price: High to Low</option>
            </select>
        </div>
    </form>

    <div class="product-card-container">
        <?php if (count($product_data) > 0): ?>
            <?php foreach ($product_data as $row): ?>
                <div class="product-card">
                    <div class="store-icon">
                        <a href="shop_page.php?shop_id=<?= $row['shop_id'] ?>">
                            <img src="../../img/store.png" alt="store icon">
                        </a>
                    </div>
                    <img src="<?= htmlspecialchars($row['image_url']) ?>" alt="<?= htmlspecialchars($row['product_name']) ?>" loading="lazy">
                    <div class="product-details">
                        <h3><?= htmlspecialchars($row['product_name']) ?></h3>
                        <div class="price">Rs.<?= htmlspecialchars($row['price']) ?></div>
                        <div>Available: <?= htmlspecialchars($row['quantity_available']) ?></div>
                        <div>Category: <?= htmlspecialchars($row['category_name'] ?? '') ?></div>
                        <div>Shop: <?= htmlspecialchars($row['shop_name']) ?></div>
                        <div class="star-rating">
                            <?php
                            $averageRating = round($row['avg_rating'] ?? 0);
                            for ($i = 1; $i <= 5; $i++): ?>
                                <span class="star<?= $i <= $averageRating ? ' filled' : '' ?>">&#9733;</span>
                            <?php endfor; ?>
                            <span>(<?= number_format($row['avg_rating'] ?? 0, 1) ?>)</span>
                        </div>
                        <form action="add_to_cart.php" method="POST">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
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

<script>
    setTimeout(function() {
        var alert = document.getElementById('success-alert');
        if (alert) {
            alert.style.display = 'none';
        }
    }, 10000); // 10 seconds
</script>
<?php
require '../footer/footer.php';
?>
</body>
</html>
