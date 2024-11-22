<?php
session_start();
ob_start();
require '../../connection.php';
require '../navbar/nav.php';

if (!isset($_SESSION['email'])) {
    header("Location: ../Login/login.php");
    exit;
}

$loggedInOwnerEmail = $_SESSION['email'];

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$category = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';

$query = "SELECT p.*, s.shop_name, c.category_name, 
                 (SELECT AVG(r.rating) FROM mech_ratings r WHERE r.product_id = p.id) AS avg_rating
          FROM products p 
          JOIN shops s ON p.shop_id = s.id 
          LEFT JOIN category c ON p.cat_id = c.id 
          WHERE 1";

if ($search) {
    $query .= " AND p.product_name LIKE '%$search%'";
}

if ($category > 0) {
    $query .= " AND p.cat_id = $category";
}

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
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="../navbar/style.css">
    <link rel="stylesheet" href="../../vehicle_owner/shop/product-list.css">

    <link rel="stylesheet" href="product-list.css">
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
 <!-- Search and Filter Form -->
    <form method="GET" action="">
        <div class="search-bar">
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search by Product Name">
            <button type="submit" class="search-btn"><i class="fas fa-search"></i></button>

        </div>

        <!-- Category Filter -->
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

        <!-- Sorting Options -->
        <div class="sort-options">
            <select name="sort">
                <option value="">Sort by Price</option>
                <option value="price_asc" <?= ($sort == 'price_asc') ? 'selected' : '' ?>>Price: Low to High</option>
                <option value="price_desc" <?= ($sort == 'price_desc') ? 'selected' : '' ?>>Price: High to Low</option>
            </select>
        </div>
    </form>

    <!-- Product Display -->
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
<?php require '../../vehicle_owner/footer/footer.php';?>
</body>
</html>
