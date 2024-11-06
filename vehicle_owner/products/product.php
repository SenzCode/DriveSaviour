<?php
session_start();
require '../../connection.php';
require '../navbar/nav.php';

if (!isset($_SESSION['email'])) {
    echo "User is not logged in.";
    exit;
}

$loggedInOwnerEmail = $_SESSION['email'];

// Get search, category, and sorting parameters from the form
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$category = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';

// Build the SQL query with search and sorting filters
$query = "SELECT p.*, s.shop_name, c.category_name 
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

// Fetch categories for the category filter dropdown
$category_query = "SELECT * FROM category";
$category_result = mysqli_query($conn, $category_query);
$categories = [];
if ($category_result) {
    while ($cat = mysqli_fetch_assoc($category_result)) {
        $categories[] = $cat;
    }
}

$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';
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
    <?php if ($message == 'insert'): ?>
        <div class="alert alert-success" id="success-alert">The Item added to the cart successfully.</div>
    <?php elseif ($message == 'update'): ?>
        <div class="alert alert-success" id="success-alert">Another Item added to the cart successfully.</div>
    <?php endif; ?>

    <button class="view-cart-btn" onclick="window.location.href='view_cart.php'">View Cart</button>
    <button class="view-cart-btn" onclick="window.location.href='../Loyalty_card/loyalty_card.php'">Loyalty Card</button>
    <button class="view-cart-btn" onclick="window.location.href='../orders/orders.php'">Orders</button>

    <!-- Search and Filter Form -->
    <form method="GET" action="">
        <div class="search-bar">
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search by Product Name">
            <button type="submit" class="search-btn"><i class="fas fa-search"></i> Search</button>
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
                    <a class="go-to-shop-icon" onclick="window.location.href='shop_page.php?shop_id=<?= $row['shop_id'] ?>'">
                        <i class='bx bxs-store'></i>
                    </a>
                    <img src="<?= htmlspecialchars($row['image_url']) ?>" alt="<?= htmlspecialchars($row['product_name']) ?>">
                    <div class="product-details">
                        <h3><?= htmlspecialchars($row['product_name']) ?></h3>
                        <div class="price">Rs.<?= htmlspecialchars($row['price']) ?></div>
                        <div>Available: <?= htmlspecialchars($row['quantity_available']) ?></div>
                        <div>Category: <?= htmlspecialchars($row['category_name']) ?></div>
                        <div>Shop: <?= htmlspecialchars($row['shop_name']) ?></div>
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
