<?php
    require '../navbar/nav.php';
    require '../../connection.php';

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
    <link rel="stylesheet" href="../navbar/style.css">
    <link rel="stylesheet" href="../shop/product-list.css">
    <style>
        /* Message box styling */
.message {
    display: none;
    padding: 10px;
    margin-bottom: 20px;
    text-align: center;
    font-size: 16px;
    border-radius: 5px;
    width: 100%;
}

/* Success message (green) */
.message-success {
    background-color: #4CAF50; /* Green background */
    color: white;
    border: 1px solid #3E8E41; /* Slightly darker border */
}

/* Error message (red) */
.message-error {
    background-color: #F44336; /* Red background */
    color: white;
    border: 1px solid #D32F2F; /* Slightly darker border */
}

    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="main_container">
        <!-- Success/Error Message Container -->
        <div id="message" class="message"></div>

        <!-- View Cart Button -->
        <button class="view-cart-btn" onclick="window.location.href='view_cart.php'">View Cart</button>

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
                        <img src="<?= htmlspecialchars($row['image_url']) ?>" alt="<?= htmlspecialchars($row['product_name']) ?>">
                        <div class="product-details">
                            <h3><?= htmlspecialchars($row['product_name']) ?></h3>
                            <div class="price">Rs.<?= htmlspecialchars($row['price']) ?></div>
                            <div>Available: <?= htmlspecialchars($row['quantity_available']) ?></div>
                            <div>Shop: <?= htmlspecialchars($row['shop_name']) ?></div> <!-- Display shop name -->
                            <form class="add-to-cart-form">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <input type="hidden" name="shop_id" value="<?= $row['shop_id'] ?>">
                                <input type="number" name="quantity" value="1" min="1" max="<?= $row['quantity_available'] ?>">
                                <button type="button" class="add-to-cart-btn">Add to Cart</button>
                            </form>
                            <!-- Go to Shop Button -->
                            <button class="go-to-shop-btn" onclick="window.location.href='shop_page.php?shop_id=<?= $row['shop_id'] ?>'">Go to the Shop</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No products found.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
          $(document).ready(function() {
    // Add to cart functionality via AJAX
    $('.add-to-cart-btn').on('click', function(e) {
        e.preventDefault();
        var form = $(this).closest('.add-to-cart-form');
        var formData = form.serialize(); // Get form data

        $.ajax({
            url: 'add_to_cart.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                // Show the response message with success class
                $('#message').removeClass('message-error').addClass('message-success').text(response).fadeIn().delay(3000).fadeOut();
            },
            error: function() {
                // Show error message with error class
                $('#message').removeClass('message-success').addClass('message-error').text('Error occurred while adding to cart. Please try again.').fadeIn().delay(3000).fadeOut();
            }
        });
    });
});

    </script>
</body>
</html>
