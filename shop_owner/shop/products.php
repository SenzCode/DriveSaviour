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

$shop_name = '';
$branch = '';
if ($shop_id > 0) {
    $shop_stmt = $conn->prepare("SELECT shop_name, branch FROM shops WHERE id = ?");
    if (!$shop_stmt) {
        echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
        exit;
    }
    $shop_stmt->bind_param("i", $shop_id);
    $shop_stmt->execute();
    $shop_result = $shop_stmt->get_result();
    if ($shop_row = $shop_result->fetch_assoc()) {
        $shop_name = $shop_row['shop_name'];
        $branch = $shop_row['branch'];
    }
    $shop_stmt->close();
}

// Fetch categories from the database
$categories = [];
$cat_stmt = $conn->prepare("SELECT id, category_name FROM category ORDER BY category_name ASC");
if ($cat_stmt) {
    $cat_stmt->execute();
    $cat_result = $cat_stmt->get_result();
    while ($cat_row = $cat_result->fetch_assoc()) {
        $categories[] = $cat_row;
    }
    $cat_stmt->close();
} else {
    echo "Prepare failed for categories: (" . $conn->errno . ") " . $conn->error;
    exit;
}

// Fetch batch numbers and corresponding product names from the batch table
$batch_data = [];
if ($shop_id > 0) {
    $batch_stmt = $conn->prepare("SELECT batch_num, product_name FROM batch WHERE suplier_id IN (SELECT suplier_id FROM products WHERE shop_id = ?)");
    $batch_stmt->bind_param("i", $shop_id);
    $batch_stmt->execute();
    $batch_result = $batch_stmt->get_result();
    while ($batch_row = $batch_result->fetch_assoc()) {
        $batch_data[] = $batch_row;
    }
    $batch_stmt->close();
}

// Fetch products only for the specified shop
$product_data = [];
if ($shop_id > 0) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE shop_id = ?");
    $stmt->bind_param("i", $shop_id);
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
            <input type="hidden" name="shop_id" value="<?php echo htmlspecialchars($shop_id); ?>">
            
            <div class="form-container">
                <div class="form-row">
                    <div class="form-group">
                        <label for="batch_num">Batch Number:</label>
                        <select id="batch_num" name="batch_num" required>
                            <option value="">Select Batch</option>
                            <?php foreach ($batch_data as $batch): ?>
                                <option value="<?= htmlspecialchars($batch['batch_num']) ?>"><?= htmlspecialchars($batch['batch_num']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            <div class="form-container">
                <div class="form-row">
                    <div class="form-group">
                        <label for="product_name">Product Name:</label>
                        <input type="text" id="product_name" name="product_name" readonly>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="category_id">Category:</label>
                        <select id="category_id" name="category_id" required>
                            <option value="">-- Select Category --</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= htmlspecialchars($category['id']) ?>">
                                    <?= htmlspecialchars($category['category_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
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
                <br>
                <button type="submit" name="action" value="insert" class="batch view-link">Add Product</button>
            </div>
            
        </form>

        <div class="searchbars">
            <!-- Search bar -->
            
            <div class="search-bar">
                <label for="search">Search by Product Name:</label>
                <input type="text" id="search" class="search-select" placeholder="Product Name">
            </div>
            <br>
        </div>

        <!-- Products Table -->
        <h2>Product List for Shop: <?= htmlspecialchars($shop_name) ?> <?= htmlspecialchars($branch) ?> Branch</h2>
        <div class="table">
            <table>
                <thead>
                    <tr>
                        <th>Batch Number</th>
                        <th>Product Name</th>
                        <th>Image</th>
                        <th>Quantity Available</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="product-tbody">
                    <?php if (count($product_data) > 0): ?>
                        <?php foreach ($product_data as $row): ?>
                            <tr>
                                <td data-cell="Batch Number"><?= htmlspecialchars($row['batch_num']) ?></td>
                                <td data-cell="Product Name"><?= htmlspecialchars($row['product_name']) ?></td>
                                <td data-cell="Image"><img src="<?= htmlspecialchars($row['image_url']) ?>" width="50"></td>
                                <td data-cell="Quantity Available"><?= htmlspecialchars($row['quantity_available']) ?></td>
                                <td data-cell="Price">Rs.<?= htmlspecialchars($row['price']) ?></td>
                                <td class="manage-btn">
                                    <button class="manage-button view-link" 
                                            data-id="<?= htmlspecialchars($row['id']) ?>"
                                            data-product_name="<?= htmlspecialchars($row['product_name']) ?>"
                                            data-image_url="<?= htmlspecialchars($row['image_url']) ?>"
                                            data-quantity_available="<?= htmlspecialchars($row['quantity_available']) ?>"
                                            data-price="<?= htmlspecialchars($row['price']) ?>"
                                            data-category_id="<?= htmlspecialchars($row['cat_id']) ?>"
                                            data-batchName="<?= htmlspecialchars($row['batch_num']) ?>">
                                        Manage
                                    </button>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">No products found for this shop.</td>
                        </tr>
                    <?php endif; ?>
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
                    <input type="hidden" id="manage_shop_id" name="shop_id" value="">
                    <div class="form-group">
                        <label for="manage_batch_name">Batch Name:</label>
                        <input type="text" id="manage_batch_name" name="batch_name" readonly>
                    </div>
                    <div class="form-group">
                        <label for="manage_product_name">Product Name:</label>
                        <input type="text" id="manage_product_name" name="product_name" readonly>
                    </div>
                    <div class="form-group">
                        <label for="manage_category_id">Category:</label>
                        <select id="manage_category_id" name="category_id" required>
                            <option value="">-- Select Category --</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= htmlspecialchars($category['id']) ?>">
                                    <?= htmlspecialchars($category['category_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
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

        document.getElementById('batch_num').addEventListener('change', function() {
    var batchNum = this.value;
    
    if (batchNum) {
        // Find the product name for the selected batch number from the batch_data array
        var productName = '';
        <?php foreach ($batch_data as $batch): ?>
            if ('<?= $batch['batch_num'] ?>' === batchNum) {
                productName = '<?= $batch['product_name'] ?>';
            }
        <?php endforeach; ?>

        // Set the product name field with the corresponding product name
        document.getElementById('product_name').value = productName;
    }
});

        // Manage Product functionality
        document.querySelectorAll('.manage-button').forEach(button => {
            button.addEventListener('click', function() {
                var productId = this.dataset.id;
                var productName = this.dataset.product_name;
                var imageUrl = this.dataset.image_url;
                var quantityAvailable = this.dataset.quantity_available;
                var price = this.dataset.price;
                var categoryId = this.dataset.category_id;
                var batchName = this.dataset.batchname;
                var shopId = "<?php echo $shop_id; ?>";

                document.getElementById('manage_batch_name').value = batchName;
                document.getElementById('manage_product_id').value = productId;
                document.getElementById('manage_product_name').value = productName;
                document.getElementById('manage_image_url').value = imageUrl;
                document.getElementById('manage_quantity_available').value = quantityAvailable;
                document.getElementById('manage_price').value = price;
                document.getElementById('manage_shop_id').value = shopId;
                document.getElementById('manage_category_id').value = categoryId;

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

        // JavaScript for Search Functionality
        document.getElementById('search').addEventListener('input', function() {
            var searchQuery = this.value.toLowerCase();
            var rows = document.querySelectorAll('#product-tbody tr');
            
            rows.forEach(function(row) {
                var productName = row.querySelector('td[data-cell="Product Name"]').textContent.toLowerCase();
                if (productName.includes(searchQuery)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

    </script>
</body>

</html>