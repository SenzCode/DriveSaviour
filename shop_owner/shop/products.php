<?php
session_start();

// Check if the session variable is set and not null
if (!isset($_SESSION['email'])) {
    echo "User is not logged in.";
    exit;
}

$loggedInOwnerEmail = $_SESSION['email'];

require('../navbar/nav.php');
include_once('../../connection.php');




$product_data = [];
$stmt = $conn->prepare("SELECT * FROM products"); // Removed WHERE clause
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $product_data[] = $row;
}
$stmt->close();

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
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="main_container">
        <?php if ($message == 'insert'): ?>
            <div class="alert alert-success">The Product was added successfully.</div>
        <?php elseif ($message == 'delete'): ?>
            <div class="alert alert-danger">The Product was deleted successfully.</div>
        <?php elseif ($message == 'edit'): ?>
            <div class="alert alert-success">The Product was updated successfully.</div>
        <?php elseif ($message == 'error'): ?>
            <div class="alert alert-danger">Something went wrong: <?= htmlspecialchars($_GET['error'] ?? '') ?></div>
        <?php endif; ?>

        <div class="title">
            <h1>Manage Products</h1>
            <br><br>
        </div>

        <!-- Add Product Form -->
        <form action="add_product.php" method="POST" enctype="multipart/form-data">
            <div class="form-container">
                <div class="form-row">
                    <div class="form-group">
                        <label for="product_name">Product Name:</label>
                        <input type="text" id="product_name" name="product_name" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="image">Product Image:</label>
                        <input type="file" id="image" name="image" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="quantity_available">Quantity Available:</label>
                        <input type="number" id="quantity_available" name="quantity_available" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="price">Price:</label>
                        <input type="text" id="price" name="price" required>
                    </div>
                </div>
                
            </div>
            <br>
            <button type="submit" name="action" value="insert" class="batch view-link">Add Product</button>
        </form>

        <div class="searchbars">
            <!-- Search bar -->
            <div class="search-bar">
                <label for="search">Search by Product Name:</label>
                <input type="text" id="search" class="search-select" placeholder="Product Name">
                <button id="search-icon"><i class="fas fa-search"></i></button>
            </div>
            <br>
        </div>

        <!-- Products Table -->
        <h2>Product List</h2>
        <div class="table">
            <table>
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Image</th>
                        <th>Quantity Available</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="product-tbody">
                    <?php foreach ($product_data as $row): ?>
                        <tr>
                            <td data-cell="Product Name"><?= htmlspecialchars($row['product_name']) ?></td>
                            <td data-cell="Image"><img src="<?= htmlspecialchars($row['image_url']) ?>" alt="<?= htmlspecialchars($row['product_name']) ?>" width="50"></td>
                            <td data-cell="Quantity"><?= htmlspecialchars($row['quantity_available']) ?></td>
                            <td data-cell="Price">Rs.<?= htmlspecialchars($row['price']) ?></td>
                            <td>
                                <button class="manage-button view-link" 
                                        data-id="<?= htmlspecialchars($row['id']) ?>"
                                        data-product_name="<?= htmlspecialchars($row['product_name']) ?>"
                                        data-image_url="<?= htmlspecialchars($row['image_url']) ?>"
                                        data-quantity_available="<?= htmlspecialchars($row['quantity_available']) ?>"
                                        data-price="<?= htmlspecialchars($row['price']) ?>">
                                    Manage
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Manage Product Modal -->
        <div id="manageProductModal" class="modal">
            <div class="modal-content">
                <span id="closeManageProductModal" class="close">&times;</span>
                <h2>Manage Product</h2>
                <form id="manageProductForm" action="product_manage.php" method="POST">
                    <input type="hidden" id="manage_product_id" name="id">
                    <div class="form-group">
                        <label for="manage_product_name">Product Name:</label>
                        <input type="text" id="manage_product_name" name="product_name" required>
                    </div>
                    <div class="form-group">
                        <label for="manage_image_url">Image URL:</label>
                        <input type="text" id="manage_image_url" name="image_url" required>
                    </div>
                    <div class="form-group">
                        <label for="manage_quantity_available">Quantity Available:</label>
                        <input type="number" id="manage_quantity_available" name="quantity_available" required>
                    </div>
                    <div class="form-group">
                        <label for="manage_price">Price:</label>
                        <input type="text" id="manage_price" name="price" required>
                    </div>
                    <br>
                    <button type="submit" name="action" value="edit" class="batch view-link">Edit</button>
                    <button type="submit" name="action" value="delete" class="batch delete-link">Delete</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        var manageProductModal = document.getElementById("manageProductModal");
        var closeManageProductModal = document.getElementById("closeManageProductModal");

        // Close modal when close button is clicked
        closeManageProductModal.onclick = function() {
            manageProductModal.style.display = "none";
        }

        // Close modal when user clicks outside of it
        window.onclick = function(event) {
            if (event.target == manageProductModal) {
                manageProductModal.style.display = "none";
            }
        }

        // Manage Product functionality
        document.querySelectorAll('.manage-button').forEach(button => {
            button.addEventListener('click', function() {
                var productId = this.dataset.id;
                var productName = this.dataset.product_name;
                var imageUrl = this.dataset.image_url;
                var quantityAvailable = this.dataset.quantity_available;
                var price = this.dataset.price;

                document.getElementById('manage_product_id').value = productId;
                document.getElementById('manage_product_name').value = productName;
                document.getElementById('manage_image_url').value = imageUrl;
                document.getElementById('manage_quantity_available').value = quantityAvailable;
                document.getElementById('manage_price').value = price;

                manageProductModal.style.display = "block";
            });
        });

        // Confirm deletion for Product
        document.getElementById("manageProductForm").addEventListener("submit", function(event) {
            var action = document.activeElement.value;
            if (action === 'delete') {
                var confirmed = confirm("Are you sure you want to delete this product?");
                if (!confirmed) {
                    event.preventDefault();
                }
            }
        });
    </script>
</body>

</html>
