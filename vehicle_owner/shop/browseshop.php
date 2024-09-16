<?php
// Start session
session_start();

require('../navbar/nav.php');
require('../../connection.php');

// Fetch shop data from the database
$sql = "SELECT * FROM shops";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Shops</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="../navbar/style.css">
</head>
<body>

<!-- Content Section -->
<div class="shop-container">
    <h1>Shops</h1>
    <div class="shop-grid">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Dynamic shop data
                echo "
                <div class='shop-card'>
                    <h2>{$row['shop_name']}</h2>
                    <p>Branch: {$row['branch']}</p>
                    <p>Address: {$row['address']}</p>
                    <p>Phone: {$row['number']}</p>
                    <!-- Hardcoded Ratings and Reviews -->
                    <p><strong>Ratings:</strong> 4.5 ★★★★☆ (120 reviews)</p>
                    <button class='view-products-btn' onclick='viewProducts({$row['id']})'>View Products</button>
                </div>
                ";
            }
        } else {
            echo "<p>No shops available.</p>";
        }
        ?>
    </div>
</div>

<!-- Footer -->
<?php require '../footer/footer.php'; ?>

<script>
// JavaScript function to view products for the specific shop
function viewProducts(shopId) {
    window.location.href = 'products.php?shop_id=' + shopId;
}
</script>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
