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
$orders_data = [];

// Fetch data from orders and mech_orders tables
try {
    // Fetch orders from the orders table
    $stmt_orders = $conn->prepare("SELECT o.*, p.product_name, p.shop_id, s.shop_name, 'orders' AS source 
                                   FROM orders o 
                                   JOIN products p ON o.product_id = p.id 
                                   JOIN shops s ON p.shop_id = s.id 
                                   WHERE s.ownerEmail = ?");
    $stmt_orders->bind_param("s", $loggedInOwnerEmail);
    $stmt_orders->execute();
    $result_orders = $stmt_orders->get_result();

    while ($row = $result_orders->fetch_assoc()) {
        $orders_data[] = $row;
    }
    $stmt_orders->close();

    // Fetch orders from the mech_orders table
    $stmt_mech_orders = $conn->prepare("SELECT mo.*, p.product_name, p.shop_id, s.shop_name, 'mech_orders' AS source 
                                        FROM mech_orders mo 
                                        JOIN products p ON mo.product_id = p.id 
                                        JOIN shops s ON p.shop_id = s.id 
                                        WHERE s.ownerEmail = ?");
    $stmt_mech_orders->bind_param("s", $loggedInOwnerEmail);
    $stmt_mech_orders->execute();
    $result_mech_orders = $stmt_mech_orders->get_result();

    while ($row = $result_mech_orders->fetch_assoc()) {
        $orders_data[] = $row;
    }
    $stmt_mech_orders->close();

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit();
}

// Initialize a success message variable
$successMessage = '';
$redirect = false; // Flag to handle redirect

// Update order status if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['order_id'], $_POST['status'], $_POST['source'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    $source = $_POST['source'];

    // Determine the correct table for updating
    $table = ($source === 'orders') ? 'orders' : 'mech_orders';

    $update_stmt = $conn->prepare("UPDATE $table SET status = ? WHERE id = ?");
    $update_stmt->bind_param("si", $status, $order_id);

    if ($update_stmt->execute()) {
        $successMessage = "Order status updated successfully.";
        $redirect = true; // Set flag to trigger JavaScript redirect
    } else {
        echo "Error updating status: " . $conn->error;
    }
    $update_stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../navbar/style.css">
</head>
<body>
    <div class="main_container">
        <h2 class="title">Order Data</h2>

        <!-- Success Message Alert -->
        <?php if ($successMessage): ?>
            <div class="alert alert-success"><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <!-- JavaScript Redirect after Success Message -->
        <?php if ($redirect): ?>
            <script>
                // Redirect to orders.php after 2 seconds
                setTimeout(function() {
                    window.location.href = 'orders.php';
                }, 2000);
            </script>
        <?php endif; ?>

        <table class="table">
            <thead>
                <tr>
                    <th>Reference Number</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Purchase Date</th>
                    <th>Item Total</th>
                    <th>Seller Income</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($orders_data)): ?>
                    <?php foreach ($orders_data as $order): ?>
                        <tr>
                            <td data-cell="Reference Number"><?php echo htmlspecialchars($order['reference_number']); ?></td>
                            <td data-cell="Product Name"><?php echo htmlspecialchars($order['product_name']); ?></td>
                            <td data-cell="Quantity"><?php echo htmlspecialchars($order['quantity']); ?></td>
                            <td data-cell="Purchase Date"><?php echo htmlspecialchars($order['purchase_date']); ?></td>
                            <td data-cell="Item Total"><?php echo htmlspecialchars($order['item_total']); ?></td>
                            <td data-cell="Seller Income"><?php echo htmlspecialchars($order['seller_income']); ?></td>
                            <td data-cell="Status"><?php echo htmlspecialchars($order['status']); ?></td>
                            <td class="manage-btn">
                                <form action="" method="POST">
                                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                    <input type="hidden" name="source" value="<?php echo $order['source']; ?>">
                                    <select name="status">
                                        <option value="Pending" <?php echo $order['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="Completed" <?php echo $order['status'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
                                        <option value="Cancelled" <?php echo $order['status'] == 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                    </select>
                                    <button class="view-link" type="submit">Update</button>
                                </form>
                            </td>
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
