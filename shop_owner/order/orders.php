<?php
require '../navbar/nav.php';
require '../../connection.php';

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    echo "User is not logged in.";
    exit();
}

$loggedInOwnerEmail = $_SESSION['email'];
$orders_data = [];
$groupedOrders = [];

// Fetch and calculate profit for orders and mech_orders
try {
    // Function to calculate profit
    function calculateProfit($conn, $product_id, $quantity, $seller_income) {
        $stmt_product = $conn->prepare("SELECT batch_num FROM products WHERE id = ?");
        $stmt_product->bind_param("i", $product_id);
        $stmt_product->execute();
        $result_product = $stmt_product->get_result();
        $product = $result_product->fetch_assoc();
        $batch_num = $product['batch_num'] ?? null;
        $stmt_product->close();

        if (!$batch_num) return 0;

        $stmt_batch = $conn->prepare("SELECT purchase_price FROM batch WHERE batch_num = ?");
        $stmt_batch->bind_param("s", $batch_num);
        $stmt_batch->execute();
        $result_batch = $stmt_batch->get_result();
        $batch = $result_batch->fetch_assoc();
        $purchase_price = $batch['purchase_price'] ?? 0;
        $stmt_batch->close();

        return $seller_income - ($purchase_price * $quantity);
    }

    // Common function to fetch and process orders
    function fetchOrders($conn, $table, $loggedInOwnerEmail) {
        $stmt = $conn->prepare("
            SELECT o.*, p.product_name, p.shop_id, s.shop_name, ? AS source
            FROM $table o
            JOIN products p ON o.product_id = p.id
            JOIN shops s ON p.shop_id = s.id
            WHERE s.ownerEmail = ?");
        $stmt->bind_param("ss", $table, $loggedInOwnerEmail);
        $stmt->execute();
        $result = $stmt->get_result();

        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $profit = calculateProfit($conn, $row['product_id'], $row['quantity'], $row['seller_income']);
            $row['profit'] = $profit;

            $update_profit = $conn->prepare("UPDATE $table SET profit = ? WHERE id = ?");
            $update_profit->bind_param("di", $profit, $row['id']);
            $update_profit->execute();
            $update_profit->close();

            $orders[] = $row;
        }
        $stmt->close();

        return $orders;
    }

    // Fetch orders from both tables
    $orders_data = array_merge(
        fetchOrders($conn, 'orders', $loggedInOwnerEmail),
        fetchOrders($conn, 'mech_orders', $loggedInOwnerEmail)
    );

    // Group orders by status
    foreach ($orders_data as $order) {
        $status = $order['status'] ?? 'Unknown';
        $groupedOrders[$status][] = $order;
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit();
}

// Handle status updates
$successMessage = '';
$redirect = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['order_id'], $_POST['status'], $_POST['source'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    $source = $_POST['source'];

    $table = ($source === 'orders') ? 'orders' : 'mech_orders';

    $update_stmt = $conn->prepare("UPDATE $table SET status = ? WHERE id = ?");
    $update_stmt->bind_param("si", $status, $order_id);

    if ($update_stmt->execute()) {
        $successMessage = "Order status updated successfully.";
        $redirect = true;
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
    <title>Order Management</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../navbar/style.css">
    <style>
        .tabs { display: flex; cursor: pointer; margin-bottom: 20px; border-bottom: 2px solid #ddd; }
        .tab { padding: 10px 20px; text-align: center; flex: 1; border: 1px solid #ddd; border-bottom: none; background-color: #f9f9f9; }
        .tab.active { background-color: #fff; font-weight: bold; border-bottom: 2px solid #fff; }
        .tab-content { display: none; }
        .tab-content.active { display: block; }
    </style>
</head>
<body>
    <div class="main_container">
        <h2 class="title">Order Management</h2>
        <?php if ($successMessage): ?>
            <div class="alert alert-success"><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <?php if ($redirect): ?>
            <script>
                setTimeout(function() {
                    window.location.href = 'orders.php';
                }, 2000);
            </script>
        <?php endif; ?>

        <div class="tabs">
            <?php foreach (['Pending', 'Ready to Pick', 'Completed', 'Cancelled'] as $status): ?>
                <div class="tab <?php echo strtolower($status) === 'pending' ? 'active' : ''; ?>" data-tab="<?php echo $status; ?>">
                    <?php echo $status; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <?php foreach (['Pending', 'Ready to Pick', 'Completed', 'Cancelled'] as $status): ?>
            <div class="tab-content <?php echo strtolower($status) === 'pending' ? 'active' : ''; ?>" id="<?php echo $status; ?>">
                <h3><?php echo $status; ?> Orders</h3>
                <?php if (!empty($groupedOrders[$status])): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Reference Number</th>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Purchase Date</th>
                                <th>Item Total</th>
                                <th>Seller Income</th>
                                <th>Profit</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($groupedOrders[$status] as $order): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($order['reference_number']); ?></td>
                                    <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                                    <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                                    <td><?php echo htmlspecialchars($order['purchase_date']); ?></td>
                                    <td><?php echo htmlspecialchars($order['item_total']); ?></td>
                                    <td><?php echo htmlspecialchars($order['seller_income']); ?></td>
                                    <td><?php echo htmlspecialchars(number_format($order['profit'], 2)); ?></td>
                                    <td><?php echo htmlspecialchars($order['status']); ?></td>
                                    <td>
                                        <form action="" method="POST">
                                            <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                            <input type="hidden" name="source" value="<?php echo $order['source']; ?>">
                                            <select name="status">
                                                <option value="Pending" <?php echo $order['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                                <option value="Ready to Pick" <?php echo $order['status'] == 'Ready to Pick' ? 'selected' : ''; ?>>Ready to Pick</option>
                                                <option value="Completed" <?php echo $order['status'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
                                                <option value="Cancelled" <?php echo $order['status'] == 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                            </select>
                                            <button type="submit" class="view-link">Update</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No <?php echo $status; ?> orders.</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', () => {
                document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));

                tab.classList.add('active');
                document.getElementById(tab.getAttribute('data-tab')).classList.add('active');
            });
        });
    </script>
</body>
</html>
