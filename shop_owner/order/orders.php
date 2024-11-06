<?php
require '../navbar/nav.php';
require '../../connection.php';

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    echo "User is not logged in.";
    exit();
}

// Get logged-in user's email
$loggedInOwnerEmail = $_SESSION['email'];

try {
    // Prepare and execute the SQL statement to fetch orders for the specific shop
    $stmt = $conn->prepare("SELECT o.*, p.product_name, p.shop_id, s.shop_name 
                            FROM orders o 
                            JOIN products p ON o.product_id = p.id 
                            JOIN shops s ON p.shop_id = s.id 
                            WHERE s.ownerEmail = ?");
    $stmt->bind_param("s", $loggedInOwnerEmail);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch order data
    $orders_data = [];
    while ($row = $result->fetch_assoc()) {
        $orders_data[] = $row;
    }
    $stmt->close();

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="../navbar/style.css">
</head>
<body>
    <div class="main_container">
        <h2 class="title">Order Data</h2>

        <table class="table">
            <thead>
                <tr>
                    <th>Reference Number</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Purchase Date</th>
                    <th>Item Total</th>
                    <th>Total Price</th>
                    <th>Discount</th>
                    <th>Seller Income</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($orders_data)): ?>
                    <?php foreach ($orders_data as $order): ?>
                        <tr>
                            <td data-cell="Reference Number"><?php echo $order['reference_number']; ?></td>
                            <td data-cell="Product Name"><?php echo $order['product_name']; ?></td>
                            <td data-cell="Quantity"><?php echo $order['quantity']; ?></td>
                            <td data-cell="Purchase Date"><?php echo $order['purchase_date']; ?></td>
                            <td data-cell="Item Total"><?php echo $order['item_total']; ?></td>
                            <td data-cell="Total Price"><?php echo $order['total_price']; ?></td>
                            <td data-cell="Discount"><?php echo $order['discount']; ?></td>
                            <td data-cell="Seller Income"><?php echo $order['seller_income']; ?></td>
                            <td data-cell="Status"><?php echo $order['status']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" class="alert alert-danger">No orders found for your shop.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
